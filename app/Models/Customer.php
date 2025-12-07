<?php

namespace App\Models;

use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Servex\Traits\UsesTenantSettingsTrait;

class Customer extends Tenant
{
    use HasFactory, UsesTenantSettingsTrait;

    protected $table = 'servex_customers';

    protected $fillable = [
        'name',
        'domain',
        'rabbimq_host',
        'rabbitmq_port',
        'serialnumber',
        'securitykey',
        'email_admin',
        'remoteServer',
        'remoteServerPort'
    ];
}
