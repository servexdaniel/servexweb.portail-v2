<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'servex_settings';
    protected $fillable = [
        'customer_id',
        'name',
        'value',
    ];

    public function tenant()
    {
        return $this->belongsTo('App\Models\Customer', 'id');
    }
}
