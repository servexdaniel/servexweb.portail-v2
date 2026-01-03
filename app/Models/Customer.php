<?php

namespace App\Models;

use App\Models\Label;
use App\Models\Setting;
use App\Models\CallColumn;
use Spatie\Multitenancy\Models\Tenant;
use App\Servex\Traits\UsesTenantSettingsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function settings()
    {
        return $this->hasMany(Setting::class,'customer_id');
    }

    public function callColumns()
    {
        return $this->belongsToMany(
            CallColumn::class,                    // Modèle lié (la colonne d’appel)
            'servex_customer_call_columns',       // Nom de la table pivot
            'customer_id',                        // Clé étrangère dans le pivot qui pointe vers LE MODÈLE PROPRIÉTAIRE (ce modèle-ci, ex. Client)
            'column_id'                           // Clé étrangère dans le pivot qui pointe vers le modèle lié (CallColumn)
        )
        ->withPivot('id')                         // Optionnel : récupère aussi la colonne 'id' du pivot
        ->withTimestamps();
    }

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
