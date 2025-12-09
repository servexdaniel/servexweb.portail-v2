<?php

namespace App\Models;

use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Servex\Traits\UsesTenantSettingsTrait;
use App\Models\Label;

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

    public function menuLabels()
    {
        /*
        return $this->belongsToMany(
            'App\Models\Label',
            'servex_customer_labels', // nom de la table pivot ex: 'servex_customer_labels'
            'customer_id',
            'label_id'
        );
        */

        return $this->belongsToMany(Label::class, 'servex_customer_labels', 'customer_id', 'label_id');
    }
}
