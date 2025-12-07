<?php

namespace App\Servex\Traits;

trait UsesCustomTenantModel
{
    protected function getTenantModel(): string
    {
        return config('multitenancy.tenant_model');
    }
}
