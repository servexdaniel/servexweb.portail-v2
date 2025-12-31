<?php

namespace App\Http\Mobility\Modules;

use Exception;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;
use App\Http\Mobility\Commands\SrCustomerInfo;
use App\Servex\Traits\UsesDomainTrait;
use App\Http\Mobility\ServexMobilityClient;
use App\Http\Mobility\Interfaces\IServexSynchro;


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
}
