<?php

namespace App\Http\Mobility;

use Stomp\Transport\Message;

class CoValidateWebLogin implements IServexCommand
{
    public function getParams($messageUIID, array $criteria = []): ServexCommandHeader
    {
        $command  = "CoValidateWebLogin";
        $ccCustomerNumber =  (array_key_exists("ccCustomerNumber", $criteria)) ? $criteria['ccCustomerNumber'] : '';
        $username =  (array_key_exists("username", $criteria)) ? $criteria['username'] : '';
        $password =  (array_key_exists("password", $criteria)) ? $criteria['password'] : '';
        $fields   = "";

        //Frame à envoyer via rabbitmq
        $commandFrame = array(
            'Command'       => $command,
            'SQL'           => 0,
            'SearchType'    => '',
            'Fields'        => '',
            'Body'          => '',
            'UserNumber'    => '',
            'CcCustomerNumber' => $ccCustomerNumber,
            'Email'         => $username,
            'Password'      => $password,
            'TokenValue'    => 'win32',
            'MessageID'     => $messageUIID
        );

        $OutputCommandFrame     = json_encode($commandFrame, JSON_UNESCAPED_UNICODE);
        $command                = base64_encode($OutputCommandFrame);
        $msg                    = new Message($command);

        return new ServexCommandHeader($msg, $fields);
    }
}
