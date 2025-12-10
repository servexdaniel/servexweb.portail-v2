<?php
namespace App\Http\Mobility;

interface IServexCommand
{
    public function getParams(String $messageUIID, array $criteria=[]) : ServexCommandHeader;
}
