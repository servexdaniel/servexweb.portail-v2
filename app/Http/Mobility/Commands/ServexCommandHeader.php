<?php
namespace App\Http\Mobility\Commands;

use Stomp\Transport\Message;

class ServexCommandHeader
{
    public Message $message;
    public ?String $fields;

    public function __construct($message, $fields="")
    {
        $this->message  = $message;
        $this->fields  = $fields;
    }
}
