<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallColumn extends Model
{
    protected $table = 'servex_call_columns';

    protected $fillable = [
        'column',
        'description',
        'isdefault',
        'ismandatory',
        'display_in_grid',
        'display_in_form',
        'display_order',
    ];
}
