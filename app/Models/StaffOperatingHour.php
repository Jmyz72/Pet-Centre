<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffOperatingHour extends Model
{
    protected $fillable = [
        'staff_id',
        'day_of_week',
        'block_index',
        'start_time',
        'end_time',
        'block_type',
        'label',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}