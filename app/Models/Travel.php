<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Travel extends Model
{
    use HasFactory;
    protected $table = 'servex_travels';
    protected $fillable = [
        'id',
        'TrNumber',
        'TrDesc',
        'customer_id'
    ];
}
