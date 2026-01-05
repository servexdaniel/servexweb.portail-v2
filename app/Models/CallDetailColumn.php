<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallDetailColumn extends Model
{
    protected $table = 'servex_call_detail_columns';

    protected $fillable = [
        'name',
        'description',
        'isdefault',
        'ismandatory',
        'display_order',
        'is_cbo',
        'is_input',
        'cbo_type',
        'cbo_items',
        'section_id',
    ];
}
