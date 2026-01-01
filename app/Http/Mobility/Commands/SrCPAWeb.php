<?php

namespace App\Http\Mobility\Commands;
use App\Http\Mobility\Interfaces\IServexCommand;
use App\Http\Mobility\Commands\ServexCommandHeader;

use Stomp\Transport\Message;

class SrCPAWeb implements IServexCommand
{
    public function getParams($messageUIID, array $criteria = []): ServexCommandHeader
    {
        $command    = "coGetRecordsSQL";
        $searchType = "srCPAWEB";
        $fields     = "CpUniqueþCpTitleþCpNumberþCpPortalSelectionþ";

        //Frame à envoyer via rabbitmq
        $commandFrame = array(
            'Command'       => $command,
            'SQL'           => 0,
            'SearchType'    => $searchType,
            'Fields'        => $fields,
            'Body'          => '',
            'UserNumber'    => '',
            'CcCustomerNumber' => '',
            'CcMail'        => '',
            'TokenValue'    => 'win32',
            'MessageID'     => $messageUIID
        );

        $outputCommandFrame     = json_encode($commandFrame, JSON_UNESCAPED_UNICODE);
        $command                = base64_encode($outputCommandFrame);
        $msg                    = new Message($command);

        return new ServexCommandHeader($msg, $fields);
    }
}
