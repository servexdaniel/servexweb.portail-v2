<?php

namespace App\Http\Mobility\Interfaces;

interface IServexSynchro
{
    public function getCustomerInfo($cunumber, $contactId);
}
