<?php

namespace App\Http\Mobility\Modules;

use Exception;
use App\Models\Code;
use App\Models\Labour;
use App\Models\Travel;
use App\Models\Contact;
use App\Models\Priority;
use App\Models\Dispatcher;
use App\Models\Technician;
use Illuminate\Support\Facades\Log;
use App\Http\Mobility\Commands\SrCode;
use App\Servex\Traits\UsesDomainTrait;
use App\Http\Mobility\Commands\SrLabour;
use App\Http\Mobility\Commands\SrTravel;
use App\Http\Mobility\Commands\SrPriority;
use App\Http\Mobility\Commands\SrWebConfig;
use App\Http\Mobility\ServexMobilityClient;
use App\Http\Mobility\Commands\SrDispatcher;
use App\Http\Mobility\Commands\SrTechnician;
use App\Http\Mobility\Commands\CoUseNewDataX;
use App\Http\Mobility\Commands\SrCompanyExtra;
use App\Http\Mobility\Commands\SrCustomerInfo;
use App\Http\Mobility\Interfaces\IServexSynchro;
use App\Http\Mobility\Commands\CoGetWindowsServiceVersion;


class ServexSynchro implements IServexSynchro
{
    use UsesDomainTrait;
    private ServexMobilityClient $servexMobilityClient;
    private $separator;
    private String $messageId;

    public function __construct()
    {
        $this->servexMobilityClient = new ServexMobilityClient();
        $this->separator  = mb_chr(254, 'UTF-8');
        $this->messageId = $this->servexMobilityClient->getMessageId();
    }

    public function getCustomerInfo($cunumber, $contactId)
    {
        Log::info("----> Module getCustomerInfo : cuNumber=" . $cunumber . "  contactId=" . $contactId);

        $customerInfo = array();
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("getCustomerInfo : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrCustomerInfo())->getParams($this->messageId, [
                'cunumber'     => $cunumber,
                'contactId'    => $contactId
            ]);


            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $keys           = explode($this->separator, $commandHdr->fields);
            array_pop($keys);
            $values           = explode($this->separator, $response);
            array_pop($values);

            //Si aucun résultat reçu pour les valeurs
            $customerInfo =  array_combine($keys, array_fill(0, count($keys), ''));

            if (!empty($values)) {
                $customerInfo = array_combine($keys, $values);
            }

            $client = $this->getCurrentTenant();
            Contact::where('id', $contactId)
                ->where('CuNumber', $cunumber)
                ->where('customer_id', $client->id)
                ->update([
                    'CuAddress' => $customerInfo['CuAddress'],
                    'CuCity'   => $customerInfo['CuCity'],
                    'CuPostalCode' => $customerInfo['CuPostalCode']
                ]);

            return collect($customerInfo);
        } catch (\Exception $e) {
            dd("getCustomerInfo() " . $e->getMessage());
        }
    }

    function syncWindowsServiceVersion()
    {
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncWindowsServiceVersion : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new CoGetWindowsServiceVersion())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $client = $this->getCurrentTenant();
            $client->setSetting('SERVICE_VERSION', $response);
            $this->servexMobilityClient->disconnect();
            return true;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    function CoUseNewDataX()
    {
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("CoUseNewDataX : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();
            //Construction des paramètres de cette commande
            $commandHdr = (new CoUseNewDataX())->getParams($this->messageId);
            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $isUseNewDataX  = ($response == "-1") ? true : false;

            $client = $this->getCurrentTenant();
            $client->setSetting('isUseNewDataX', $isUseNewDataX);

            $this->servexMobilityClient->disconnect();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function syncWebConfig()
    {
        $isOk = false;
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncWebConfig : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrWebConfig())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $webconfig           = explode($this->separator, $response);
            array_pop($webconfig);

            $client = $this->getCurrentTenant();

            $client->deleteSettings(['CPA_SUBMISSION', 'CPA_SUBMISSION_ACCEPTED', 'CPA_SUBMISSION_REJECTED', 'CPA_WEB', 'CPA_CANCEL_WEB', 'CPA_CALL_MODIFIED', 'DEFAULT_TECH_WEB', 'DEFAULT_DISPATCH_WEB']);

            if (count($webconfig) > 0) {
                $isOk = true;
                if (array_key_exists('0', $webconfig)) {
                    $defaultCPA = $webconfig[0];
                    $client->setSetting('CPA_WEB', $defaultCPA);
                }
                if (array_key_exists('1', $webconfig)) {
                    $defaultCancelCPA = $webconfig[1];
                    $client->setSetting('CPA_CANCEL_WEB', $defaultCancelCPA);
                }
                if (array_key_exists('2', $webconfig)) {
                    $defaultTech = $webconfig[2];
                    $client->setSetting('DEFAULT_TECH_WEB', $defaultTech);
                }
                if (array_key_exists('3', $webconfig)) {
                    $defaultDispatch = $webconfig[3];
                    $client->setSetting('DEFAULT_DISPATCH_WEB', $defaultDispatch);
                }
                if (array_key_exists('4', $webconfig)) {
                    $cpaCallModified = $webconfig[4];
                    $client->setSetting('CPA_CALL_MODIFIED', $cpaCallModified);
                }
                if (array_key_exists('5', $webconfig)) {
                    $cpaSubmission = $webconfig[5];
                    $client->setSetting('CPA_SUBMISSION', $cpaSubmission);
                }
                if (array_key_exists('6', $webconfig)) {
                    $cpaSubmissionAccepted = $webconfig[6];
                    $client->setSetting('CPA_SUBMISSION_ACCEPTED', $cpaSubmissionAccepted);
                }
                if (array_key_exists('7', $webconfig)) {
                    $cpaSubmissionRejected = $webconfig[7];
                    $client->setSetting('CPA_SUBMISSION_REJECTED', $cpaSubmissionRejected);
                }
            }
            $this->servexMobilityClient->disconnect();
            return $isOk;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function syncCodes()
    {
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncCodes : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrCode())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $keys           = explode($this->separator, $commandHdr->fields);
            array_pop($keys);
            $values           = explode($this->separator, $response);
            array_pop($values);

            $countFields            = sizeof($keys);
            if ($countFields > 0) {

                $codes_arr    = collect(array_chunk($values, $countFields, false));

                //Convertir en un taleau de clé/valeur
                $codes = array();
                if (count($codes_arr) > 0) {
                    foreach ($codes_arr as $code) {
                        array_push(
                            $codes,
                            array_combine($keys, $code)
                        );
                    }
                }

                $client = $this->getCurrentTenant();
                Code::where('customer_id', $client->id)->delete();

                foreach ($codes as $code) {
                    try {
                        Code::updateOrCreate([
                            'customer_id'       => $client->id,
                            'CoNumber'          => $code['CoNumber'],
                            'CoDesc'            => $code['CoDesc']
                        ]);
                    } catch (\Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }
            $dataset = Code::where('customer_id', $client->id)->get();
            $this->servexMobilityClient->disconnect();
            return $dataset;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function syncTravels()
    {
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncTravels : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrTravel())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $keys           = explode($this->separator, $commandHdr->fields);
            array_pop($keys);
            $values           = explode($this->separator, $response);
            array_pop($values);

            $countFields            = sizeof($keys);
            if ($countFields > 0) {

                $travels_arr    = collect(array_chunk($values, $countFields, false));

                //Convertir en un taleau de clé/valeur
                $travels = array();
                if (count($travels_arr) > 0) {
                    foreach ($travels_arr as $travel) {
                        array_push(
                            $travels,
                            array_combine($keys, $travel)
                        );
                    }
                }

                $client = $this->getCurrentTenant();
                Travel::where('customer_id', $client->id)->delete();

                foreach ($travels as $travel) {
                    try {
                        Travel::updateOrCreate([
                            'customer_id'       => $client->id,
                            'TrNumber'          => $travel['TrNumber'],
                            'TrDesc'            => $travel['TrDesc']
                        ]);
                    } catch (\Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }
            $dataset = Travel::where('customer_id', $client->id)->get();
            $this->servexMobilityClient->disconnect();
            return $dataset;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function syncLabours()
    {
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncLabours : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrLabour())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $keys           = explode($this->separator, $commandHdr->fields);
            array_pop($keys);
            $values           = explode($this->separator, $response);
            array_pop($values);

            $countFields            = sizeof($keys);
            if ($countFields > 0) {

                $labours_arr    = collect(array_chunk($values, $countFields, false));

                //Convertir en un taleau de clé/valeur
                $labours = array();
                if (count($labours_arr) > 0) {
                    foreach ($labours_arr as $labour) {
                        array_push(
                            $labours,
                            array_combine($keys, $labour)
                        );
                    }
                }

                $client = $this->getCurrentTenant();
                Labour::where('customer_id', $client->id)->delete();

                foreach ($labours as $labour) {
                    try {
                        Labour::updateOrCreate([
                            'customer_id'       => $client->id,
                            'LaNumber'          => $labour['LaNumber'],
                            'LaDesc'            => $labour['LaDesc']
                        ]);
                    } catch (\Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }
            $dataset = Labour::where('customer_id', $client->id)->get();
            $this->servexMobilityClient->disconnect();
            return $dataset;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function syncPriorities()
    {
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncPriorities : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrPriority())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $keys           = explode($this->separator, $commandHdr->fields);
            array_pop($keys);
            $values           = explode($this->separator, $response);
            array_pop($values);

            $countFields            = sizeof($keys);
            if ($countFields > 0) {

                $priorities_arr    = collect(array_chunk($values, $countFields, false));

                //Convertir en un taleau de clé/valeur
                $priorities = array();
                if (count($priorities_arr) > 0) {
                    foreach ($priorities_arr as $priority) {
                        array_push(
                            $priorities,
                            array_combine($keys, $priority)
                        );
                    }
                }

                $client = $this->getCurrentTenant();
                Priority::where('customer_id', $client->id)->delete();

                foreach ($priorities as $priority) {
                    try {
                        Priority::updateOrCreate([
                            'customer_id'       => $client->id,
                            'PrNumber'          => $priority['PrNumber'],
                            'PrDesc'            => $priority['PrDesc']
                        ]);
                    } catch (\Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }
            $dataset = Priority::where('customer_id', $client->id)->get();
            $this->servexMobilityClient->disconnect();
            return $dataset;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function syncTechnicians()
    {
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncTechnicians : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrTechnician())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $keys           = explode($this->separator, $commandHdr->fields);
            array_pop($keys);
            $values           = explode($this->separator, $response);
            array_pop($values);

            $countFields            = sizeof($keys);
            if ($countFields > 0) {

                $technicians_arr    = collect(array_chunk($values, $countFields, false));

                //Convertir en un taleau de clé/valeur
                $technicians = array();
                if (count($technicians_arr) > 0) {
                    foreach ($technicians_arr as $tech) {
                        array_push(
                            $technicians,
                            array_combine($keys, $tech)
                        );
                    }
                }

                $client = $this->getCurrentTenant();
                Technician::where('customer_id', $client->id)->delete();

                foreach ($technicians as $tech) {
                    try {
                        Technician::updateOrCreate([
                            'customer_id'       => $client->id,
                            'TeNumber'          => $tech['TeNumber'],
                            'TeName'            => $tech['TeName']
                        ]);
                    } catch (\Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }
            $dataset = Technician::where('customer_id', $client->id)->get();
            $this->servexMobilityClient->disconnect();
            return $dataset;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function syncDispatchers()
    {
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncDispatchers : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrDispatcher())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $keys           = explode($this->separator, $commandHdr->fields);
            array_pop($keys);
            $values           = explode($this->separator, $response);
            array_pop($values);

            $countFields            = sizeof($keys);
            if ($countFields > 0) {

                $dispatchers_arr    = collect(array_chunk($values, $countFields, false));

                //Convertir en un taleau de clé/valeur
                $dispatchers = array();
                if (count($dispatchers_arr) > 0) {
                    foreach ($dispatchers_arr as $disp) {
                        array_push(
                            $dispatchers,
                            array_combine($keys, $disp)
                        );
                    }
                }

                $client = $this->getCurrentTenant();
                Dispatcher::where('customer_id', $client->id)->delete();

                foreach ($dispatchers as $disp) {
                    try {
                        if ($disp['DiName'] != "") {
                            Dispatcher::updateOrCreate([
                                'customer_id'       => $client->id,
                                'DiNumber'          => $disp['DiNumber'],
                                'DiName'            => $disp['DiName']
                            ]);
                        }
                    } catch (\Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }
            $dataset = Dispatcher::where('customer_id', $client->id)->get();
            $this->servexMobilityClient->disconnect();
            return $dataset;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function syncCompanyExtraInfo()
    {
        $isOk = false;
        try {
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("syncCompanyExtraInfo : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new SrCompanyExtra())->getParams($this->messageId);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();

            $keys           = explode($this->separator, $commandHdr->fields);
            array_pop($keys);
            $companyExtraInfo           = explode($this->separator, $response);
            array_pop($companyExtraInfo);

            $client = $this->getCurrentTenant();
            if (count($companyExtraInfo) > 0) {
                $isOk = true;
                if (array_key_exists('0', $companyExtraInfo)) {
                    $CiDefPriorityNumber = $companyExtraInfo[0];
                    $client->setSetting('DEFAULT_PriorityNumber', $CiDefPriorityNumber);
                }
                if (array_key_exists('1', $companyExtraInfo)) {
                    $CiDefTravelNumber = $companyExtraInfo[1];
                    $client->setSetting('DEFAULT_TravelNumber', $CiDefTravelNumber);
                }
                if (array_key_exists('2', $companyExtraInfo)) {
                    $CiDefCodeNumber = $companyExtraInfo[2];
                    $client->setSetting('DEFAULT_CodeNumber', $CiDefCodeNumber);
                }
            }
            $this->servexMobilityClient->disconnect();
            return $isOk;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
