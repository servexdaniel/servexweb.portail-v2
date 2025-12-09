<?php
namespace App\Http\Mobility\Commands;

interface IServexCommand
{
    public function getParams(String $messageUIID, array $criteria=[]) : ServexCommandHeader;
}
