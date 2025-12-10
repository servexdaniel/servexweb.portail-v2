<?php
namespace App\Http\Mobility\Interfaces;
use App\Http\Mobility\Commands\ServexCommandHeader;

interface IServexCommand
{
    public function getParams(String $messageUIID, array $criteria=[]) : ServexCommandHeader;
}
