<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'merchant_id','customer_id','booking_type',
        'service_id','package_id','customer_pet_id','pet_id','staff_id',
        'start_at','end_at','status','price_amount','payment_ref','idempotency_key','meta'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'price_amount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function merchant()     { return $this->belongsTo(MerchantProfile::class); }
    public function customer()     { return $this->belongsTo(User::class, 'customer_id'); }
    public function service()      { return $this->belongsTo(Service::class); }         // clinic
    public function package()      { return $this->belongsTo(Package::class); }         // groomer
    public function customerPet()  { return $this->belongsTo(CustomerPet::class, 'customer_pet_id'); }
    public function merchantPet()  { return $this->belongsTo(Pet::class, 'pet_id'); }   // shelter’s pet
    public function staff()        { return $this->belongsTo(Staff::class); }
    public function schedule()     { return $this->hasOne(Schedule::class); }
    public function payments()     { return $this->hasMany(Payment::class); }
    public function reviews()      { return $this->hasMany(Review::class); }
}
