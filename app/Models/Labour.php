<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labour extends Model
{
    use HasFactory;
    protected $table = 'servex_labours';
    protected $fillable = [
        'customer_id',
        'LaNumber',
        'LaDesc'
    ];
}