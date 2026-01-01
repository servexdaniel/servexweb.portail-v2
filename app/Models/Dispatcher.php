<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispatcher extends Model
{
    use HasFactory;
    protected $table = 'servex_dispatchers';
    protected $fillable = [
        'customer_id',
        'DiNumber',
        'DiName'
    ];
}
