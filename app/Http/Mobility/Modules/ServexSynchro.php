<?php

namespace App\Http\Mobility\Modules;

use Illuminate\Support\Facades\Log;
use App\Http\Mobility\Interfaces\IServexSynchro;
use App\Http\Mobility\ServexMobilityClient;
use App\Http\Mobility\Commands\SrCustomerInfo;
use Exception;
use App\Models\Contact;
use App\Servex\Traits\UsesDomainTrait;


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
        Log::info("----> Module ServexSynchro : cuNumber=" . $cunumber . "  contactId=" . $contactId);

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

            return collect($customerInfo);
        } catch (\Exception $e) {
            dd("getCustomerInfo() " . $e->getMessage());
        }
    }
}
