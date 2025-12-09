<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Label extends Model
{
    use HasFactory;

    protected $table = 'servex_labels';

    protected $fillable = [
        'code',
        'active',
        'order',
        'route',
    ];

    public function menuLabels()
    {
        /*
        return $this->belongsToMany(
            'App\Models\Customer',
            'servex_customer_labels', // nom de la table pivot ex: 'servex_customer_labels'
            'label_id',
            'customer_id'
        );
        */

        return $this->belongsToMany(Customer::class, 'servex_customer_labels', 'label_id', 'customer_id');
    }
}
