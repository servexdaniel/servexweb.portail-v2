<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Priority extends Model
{
    use HasFactory;
    protected $table = 'servex_priorities';
    protected $fillable = [
        'customer_id',
        'PrNumber',
        'PrDesc'
    ];
}
