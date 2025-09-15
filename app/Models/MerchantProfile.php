<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Pet;
use App\Models\Service;
use App\Models\Package;

class MerchantProfile extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'name',
        'phone',
        'address',
        'registration_number',
        'license_number',
        'document_path',
        'photo',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function operatingHours()
    {
        return $this->hasMany(\App\Models\OperatingHour::class);
    }

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class, 'merchant_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'merchant_id');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'merchant_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(\App\Models\Booking::class, 'merchant_id');
    }

    public function wallet()
    {
        return $this->hasOne(MerchantWallet::class, 'merchant_id');
    }
    public function getWallet(): MerchantWallet
    {
        return $this->wallet()->firstOrCreate([
            'merchant_id' => $this->id,
        ], [
            'currency' => 'MYR',
            'balance' => 0.00,
            'pending_balance' => 0.00,
            'is_active' => true,
        ]);
    }
}
