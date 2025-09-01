<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'merchant_id',
        'service_type_id',
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
    ];
}
