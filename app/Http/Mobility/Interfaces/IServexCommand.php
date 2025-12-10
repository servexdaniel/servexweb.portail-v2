<?php
namespace App\Http\Mobility\Interfaces;

interface IServexCommand
{
    public function getParams(String $messageUIID, array $criteria=[]) : ServexCommandHeader;
}
