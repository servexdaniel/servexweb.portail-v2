<?php
namespace App\Http\Mobility\Commands;

use Stomp\Transport\Message;
use App\Http\Mobility\Interfaces\IServexCommand;
use App\Http\Mobility\Commands\ServexCommandHeader;

class CoGetWindowsServiceVersion implements IServexCommand
{
    public function getParams($messageUIID, Array $criteria=[]) : ServexCommandHeader
    {
        $command = "coGetWindowsServiceVersion";
        $language  = (app()->getLocale() == "fr") ? 0 : ( app()->getLocale() == "en" ? 1 : 0 );
        $fields = "";

        //Frame à envoyer via rabbitmq
        $commandFrame = array(  'Command'       => $command,
                                'SQL'           => "",
                                'SearchType'    => "",
                                'SearchString'  => "",
                                'Fields'        => $fields,
                                'Body'          => "",
                                'PIN'           => "",
                                'Version'       => "",
                                'callNumber'    => "",
                                'UserNumber'    => '',
                                'TokenValue'    => 'win32',
                                'WebLanguage'   => $language,
                                'MessageID'     => $messageUIID
                            );

        $OutputCommandFrame     = json_encode($commandFrame,JSON_UNESCAPED_UNICODE);
        $command                = base64_encode($OutputCommandFrame);
        $msg                    = new Message($command);

        return new ServexCommandHeader($msg, $fields);
    }
}
