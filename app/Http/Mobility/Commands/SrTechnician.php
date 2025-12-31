<?php

namespace App\Http\Mobility\Commands;
use App\Http\Mobility\Interfaces\IServexCommand;
use App\Http\Mobility\Commands\ServexCommandHeader;

use Stomp\Transport\Message;

class SrTechnician implements IServexCommand
{
    public function getParams($messageUIID, Array $criteria=[]) : ServexCommandHeader
    {
        $command    = "coGetRecordsSQL";
        $searchType = "srTechnicians";
        $fields     = "TeNumberþTeNameþ";

        //Frame à envoyer via rabbitmq
        $commandFrame = array(  'Command'       => $command,
                                'SQL'           => 0,
                                'SearchType'    => $searchType,
                                'Fields'        => $fields,
                                'Body'          => '',
                                'UserNumber'    => '',
                                'CcCustomerNumber'=> '',
                                'CcMail'        => '',
                                'TokenValue'    => 'win32',
                                'MessageID'     => $messageUIID
                            );

        $OutputCommandFrame     = json_encode($commandFrame,JSON_UNESCAPED_UNICODE);
        $command                = base64_encode($OutputCommandFrame);
        $msg                    = new Message($command);

        return new ServexCommandHeader($msg, $fields);
    }
}
