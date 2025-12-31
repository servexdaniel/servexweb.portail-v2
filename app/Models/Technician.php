<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Technician extends Model
{
    use HasFactory;
    protected $table = 'servex_technicians';
    protected $fillable = [
        'customer_id',
        'TeNumber',
        'TeName'
    ];
}
