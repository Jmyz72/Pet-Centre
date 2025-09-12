<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id','payment_ref','amount','currency','status','provider','idempotency_key','meta'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function booking() { return $this->belongsTo(Booking::class); }
}
