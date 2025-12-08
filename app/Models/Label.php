<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $table = 'servex_labels';

    protected $fillable = [
        'code',
        'active',
        'order',
        'route',
    ];

    public function menuLabels()
    {
        return $this->belongsToMany('App\Models\Customer');
    }
}
