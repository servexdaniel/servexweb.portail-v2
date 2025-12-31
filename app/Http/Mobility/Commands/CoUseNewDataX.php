<?php

namespace App\Http\Mobility\Commands;
use App\Http\Mobility\Interfaces\IServexCommand;
use App\Http\Mobility\Commands\ServexCommandHeader;

use Stomp\Transport\Message;
class CoUseNewDataX implements IServexCommand
{
    public function getParams($messageUIID, array $criteria = []): ServexCommandHeader
    {
        $command = "CoUseNewDataX";

        //Frame à envoyer via rabbitmq
        $commandFrame = array(
            'Command'       => $command,
            'SQL'           => "",
            'SearchType'    => "",
            'SearchString'  => "",
            'Fields'        => "",
            'Body'          => "",
            'PIN'           => "",
            'Version'       => "",
            'callNumber'    => "",
            'UserNumber'    => '',
            'TokenValue'    => 'win32',
            'MessageID'     => $messageUIID
        );

        $OutputCommandFrame     = json_encode($commandFrame, JSON_UNESCAPED_UNICODE);
        $command                = base64_encode($OutputCommandFrame);
        $msg                    = new Message($command);

        return new ServexCommandHeader($msg);
    }
}
