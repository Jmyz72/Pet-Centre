<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'merchant_id',
        'package_type_id',
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
    ];
}
