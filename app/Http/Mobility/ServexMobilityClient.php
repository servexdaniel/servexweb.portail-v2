<?php

namespace App\Http\Mobility;

use App\Models\Customer;
use Stomp\Client;
use Stomp\StatefulStomp;
use \App\Utilities\CustomDomainTenantFinder;
use Illuminate\Support\Facades\Log;
use Stomp\Transport\Message;

class ServexMobilityClient
{
    private Client $consumer;
    private StatefulStomp $stomp;
    private Customer $client;
    private String $vhost;
    private String $messageId;
    private String $MySubID;

    public function __construct()
    {
        if (Customer::checkCurrent()) {
            $this->client = app('currentTenant');
        } else {
            $tenantFinder = app(CustomDomainTenantFinder::class);
            $this->client = $tenantFinder->findForRequest(request());
        }

        $this->vhost = $this->client->serialnumber;
        $this->messageId  = time() . '-' . uniqid(true);
        $this->MySubID    = $this->vhost . "_" . $this->messageId;

        $this->consumer = new Client('tcp://' . $this->client->rabbimq_host . ':' . $this->client->rabbitmq_port);

        // set clientId on a consumer to make it durable
        $this->consumer->setClientId($this->MySubID);

        // we want the server to send us signals every 4000ms
        $this->consumer->setHeartbeat(4000, 4000);

        //Set credentials
        $this->consumer->setLogin($this->client->serialnumber, $this->client->securitykey);

        //Set virtual host
        $this->consumer->setVhostname($this->vhost);

        //Set seconds to wait for a receipt.
        $this->consumer->setReceiptWait(5000);

        //Instancier le protocole stomp
        $this->stomp      = new StatefulStomp($this->consumer);

        //Subscription
        $this->stomp->subscribe("/topic/" . $this->MySubID, null, 'auto', array('ack' => 'client', 'id' => $this->MySubID));
    }

    public function connect()
    {
        return $this->consumer->connect();
    }

    public function disconnect()
    {
        $this->consumer->disconnect();
        unset($stomp);
    }

    public function beginTransaction()
    {
        return $this->stomp->begin();
    }

    public function send(Message $msg)
    {
        $this->stomp->send("/topic/" . $this->vhost, $msg, array("transaction" => "tx1"));
    }

    public function commitTransaction()
    {
        return $this->stomp->commit();
    }

    public function rollbackTransaction()
    {
        return $this->stomp->abort();
    }

    public function read()
    {
        //Ajout du timeout de 2 sec
        $this->consumer->getConnection()->setReadTimeout(2);

        $hasDataToRead     = $this->consumer->getConnection()->hasDataToRead();
        $isConnected     = $this->consumer->getConnection()->isConnected();

        if ($hasDataToRead && $isConnected) {
            //Reception de la réponse
            $message            = $this->consumer->readFrame();
            if (!is_bool($message) && !$message->isErrorFrame()) {
                $response = $message->getBody();
                $messagedecoded     = base64_decode($response, true);
                $decodedarray       = json_decode($messagedecoded, true);
                $body               = $decodedarray["body"];
                return $body;
            }
            return "";
        }
    }

    public function readError()
    {
        //Ajout du timeout de 2 sec
        $this->consumer->getConnection()->setReadTimeout(2);

        $hasDataToRead     = $this->consumer->getConnection()->hasDataToRead();
        $isConnected     = $this->consumer->getConnection()->isConnected();

        if ($hasDataToRead && $isConnected) {
            //Reception de la réponse
            $message            = $this->consumer->readFrame();
            if (!is_bool($message) && !$message->isErrorFrame()) {
                $response = $message->getBody();
                $messagedecoded     = base64_decode($response, true);
                $decodedarray       = json_decode($messagedecoded, true);
                $error               = $decodedarray["error"];
                return $error;
            }
            return "";
        }
    }

    public function getConsumer()
    {
        return $this->consumer;
    }

    public function getStompClient()
    {
        return $this->stomp;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }
}
