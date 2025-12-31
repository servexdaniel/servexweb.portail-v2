<?php
namespace App\Http\Mobility\Commands;

use Stomp\Transport\Message;
use App\Http\Mobility\Interfaces\IServexCommand;
use App\Http\Mobility\Commands\ServexCommandHeader;

class SrCustomerInfo implements IServexCommand
{

    public function getParams(String $messageUIID, array $criteria=[]) : ServexCommandHeader
    {
        $command = "coGetRecordsSQL";
        $searchType = "srCustomerInfo";
        //$cunumber = getCuNumber();
        $contactId   =  (array_key_exists("contactId", $criteria)) ? $criteria['contactId'] : 0;
        $cunumber   =  (array_key_exists("cunumber", $criteria)) ? $criteria['cunumber'] : 0;
        $fields   = "CuNumberþCuNameþCuCareOfþCuAddressþCuCityþCuISOCountryCodeþCuPostalCodeþCuActiveþCuPhoneNumber1þ";

        //Frame à envoyer via rabbitmq
        $commandFrame = array(  'Command'       => $command,
                                'SQL'           => $cunumber,
                                'SearchType'    => $searchType,
                                'whereQuery'    => '',
                                'Fields'        => $fields,
                                'Body'          => '',
                                'UserNumber'    => '',
                                'CcCustomerNumber'=> '',
                                'CcMail'        => '',
                                'TokenValue'    => 'win32',
                                'SearchString'  => '',
                                'MessageID'     => $messageUIID
                            );

        $OutputCommandFrame     = json_encode($commandFrame,JSON_UNESCAPED_UNICODE);
        $command                = base64_encode($OutputCommandFrame);
        $msg                    = new Message($command);

        return new ServexCommandHeader($msg, $fields);
    }
}
