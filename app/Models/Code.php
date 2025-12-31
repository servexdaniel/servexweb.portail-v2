<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Code extends Model
{
    use HasFactory;
    protected $table = 'servex_codes';
    protected $fillable = [
        'id',
        'CoNumber',
        'CoDesc',
        'customer_id'
    ];
}
