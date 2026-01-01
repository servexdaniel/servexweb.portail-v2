<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cpa extends Model
{
    use HasFactory;
    protected $table = 'servex_cpa';
    protected $fillable = [
        'customer_id',
        'CpUnique',
        'CpTitle',
        'CpNumber',
        'CpPortalSelection',
    ];
}
