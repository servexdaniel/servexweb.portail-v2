<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Contact extends Authenticatable
{
    use HasFactory;
    protected $table = 'servex_contacts';

    protected $fillable = [
        'id',
        'sessionid',
        'customer_id',
        'connected_at',
        'username',
        'email',
        'password',
        'CcUnique',
        'CcName',
        'CuNumber',
        'CuName',
        'CuAddress',
        'CuCity',
        'CuPostalCode',
        'CcIsManager',
        'CcPortailAdmin',
        'CcPhoneNumber',
        'CcPhoneExt',
        'CcCellNumber',
        'CcEmail',
        'LoginSuccess',
        'ReasonLogin',
    ];
}
