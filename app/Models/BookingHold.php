<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingHold extends Model
{
    /**
     * Status constants for booking holds.
     */
    public const STATUS_HELD      = 'held';        // freshly created hold
    public const STATUS_CONVERTED = 'converted';   // turned into a booking
    public const STATUS_RELEASED  = 'released';    // manually released/cancelled
    public const STATUS_EXPIRED   = 'expired';     // auto-expired

    /**
     * Default attributes.
     */
    protected $attributes = [
        'status' => self::STATUS_HELD,
    ];

    protected $fillable = [
        'merchant_id','staff_id','customer_pet_id','pet_id',
        'service_id','package_id','start_at','end_at',
        'status','expires_at','idempotency_key','meta'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'expires_at' => 'datetime',
        'meta' => 'array',
    ];

    public function merchant()    { return $this->belongsTo(MerchantProfile::class); }
    public function staff()       { return $this->belongsTo(Staff::class); }
    public function service()     { return $this->belongsTo(Service::class); }
    public function package()     { return $this->belongsTo(Package::class); }
    public function customerPet() { return $this->belongsTo(CustomerPet::class, 'customer_pet_id'); }
    public function merchantPet() { return $this->belongsTo(Pet::class, 'pet_id'); }
}
