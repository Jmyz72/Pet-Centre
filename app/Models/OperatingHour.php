<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatingHour extends Model
{
    protected $fillable = [
        'merchant_profile_id',
        'day_of_week',
        'block_index',
        'start_time',
        'end_time',
        'block_type',
        'label',
    ];
}
