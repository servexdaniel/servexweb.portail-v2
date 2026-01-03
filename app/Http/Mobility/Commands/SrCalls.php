<?php

namespace App\Http\Mobility\Commands;
use DateTimeZone;
use Stomp\Transport\Message;

use App\Http\Mobility\Interfaces\IServexCommand;
use App\Http\Mobility\Commands\ServexCommandHeader;

class SrCalls implements IServexCommand
{
    public function getParams($messageUIID, array $criteria = []): ServexCommandHeader
    {
        $command    = "coGetRecordsSQL";
        $CuNumber = getCuNumber();
        $whereQuery = $CuNumber;
        $isCallComplete   =  (array_key_exists("isCallComplete", $criteria)) ? $criteria['isCallComplete'] : 0;
        $fields   =  (array_key_exists("fields", $criteria)) ? $criteria['fields'] : '';
        if (!$isCallComplete) {
            $searchType = "srCallsWEB";
        } else {
            $searchType = "srCallCompleteCustomerWEB";

            $fromdate = getHistoryFromDate();
            $fromdate = $fromdate['date'];
            $new_format_fromdate = date("d/m/Y", strtotime($fromdate));

            $now = now();
            $timezone = config('app.timezone');
            $now->setTimezone(new DateTimeZone($timezone));
            $yesterday = date('Y-m-d', (strtotime('-1 day', strtotime($now))));

            //Ajouter événtuellement la date limite pour la liste d'historique d'appels
            if (!is_null($fromdate) && $fromdate < $yesterday) {
                $whereQuery = $CuNumber . "|" . $new_format_fromdate;
            }
        }

        //Frame à envoyer via rabbitmq
        $commandFrame = array(
            'Command'       => $command,
            'SQL'           => $whereQuery,
            'SearchType'    => $searchType,
            'SearchString'  => "",
            'Fields'        => $fields,
            'Body'          => '',
            'UserNumber'    => '',
            'CcCustomerNumber' => '',
            'CcMail'        => '',
            'TokenValue'    => 'win32',
            'MessageID'     => $messageUIID
        );

        $OutputCommandFrame     = json_encode($commandFrame, JSON_UNESCAPED_UNICODE);
        $command                = base64_encode($OutputCommandFrame);
        $msg                    = new Message($command);

        return new ServexCommandHeader($msg, $fields);
    }
}
