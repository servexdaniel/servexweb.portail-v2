<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCallColumn extends Model
{
    protected $table = 'servex_customer_call_columns';

    protected $fillable = [
        'customer_id',
        'column_id',
    ];

}
