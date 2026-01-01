<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataX extends Model
{
    use HasFactory;
    protected $table = 'servex_datax';
    protected $fillable = [
        'customer_id',
        'dataxname',
        'fieldtype',
        'fieldlabel',
        'fieldname',
        'fielditems'
    ];
}
