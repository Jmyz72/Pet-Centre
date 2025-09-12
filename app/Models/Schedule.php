<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'merchant_id','staff_id','start_at','end_at','booking_id','block_type'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function merchant() { return $this->belongsTo(MerchantProfile::class); }
    public function staff()    { return $this->belongsTo(Staff::class); }
    public function booking()  { return $this->belongsTo(Booking::class); }
}
