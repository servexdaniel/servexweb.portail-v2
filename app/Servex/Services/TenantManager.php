<?php

namespace App\Servex\Services;

use App\Models\Customer;

class TenantManager
{
    /*
     * @var null|App\Tenant
     */
    private $tenant;

    public function setTenant(?Customer $tenant)
    {
        $this->tenant = $tenant;
        $this->tenant->makeCurrent();
        return $this;
    }

    public function getTenant(): ?Customer
    {
        return $this->tenant;
    }

    public function loadTenant($identifier): bool
    {
        // Identify the tenant
        $tenant = Customer::query()->where('domain', '=', $identifier)->first();
        if ($tenant) {
            $this->setTenant($tenant);
            return true;
        }

        return false;
    }
}
