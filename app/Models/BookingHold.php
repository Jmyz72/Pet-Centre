<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'merchant_id','staff_id','customer_id','customer_pet_id','pet_id',
        'service_id','package_id','start_at','end_at',
        'booking_type','status','expires_at','idempotency_key','meta'
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

    /**
     * Calculate the amount for this booking hold based on the booking type and related models.
     */
    public function calculateAmount(): float
    {
        if ($this->service_id) {
            // Service booking
            return (float) ($this->service->price ?? 0.0);
        }

        if ($this->package_id) {
            // Package booking - use variation logic like in PackageBooking
            $package = $this->package;
            $base = (float) ($package->price ?? 0.0);
            
            // Get pet characteristics from customer pet
            $customerPet = $this->customerPet;
            if (!$customerPet) {
                return $base;
            }
            
            $petTypeId = $customerPet->pet_type_id;
            $sizeId = $customerPet->size_id;
            $breedId = $customerPet->pet_breed_id;
            
            if (!$petTypeId) {
                return $base;
            }

            // Find pivot records for this package + pet type
            $pivotIds = DB::table('package_pet_types')
                ->where('package_id', $package->id)
                ->where('pet_type_id', $petTypeId)
                ->pluck('id');

            if ($pivotIds->isEmpty()) {
                return $base;
            }

            // Get active variations for this package + pet type
            $variations = \App\Models\PackageVariation::query()
                ->where('package_id', $package->id)
                ->whereIn('package_pet_type_id', $pivotIds)
                ->where('is_active', 1)
                ->get();

            if ($variations->isEmpty()) {
                return $base;
            }

            // Choose the most specific match: breed > size > fallback
            $chosen = null;
            if ($breedId) {
                $chosen = $variations->firstWhere('package_breed_id', $breedId);
            }
            if (!$chosen && $sizeId) {
                $chosen = $variations->firstWhere('package_size_id', $sizeId);
            }
            if (!$chosen) {
                $chosen = $variations->first();
            }

            return (float) (optional($chosen)->price ?? $base);
        }

        if ($this->pet_id) {
            // Adoption booking
            return (float) ($this->merchantPet->adoption_fee ?? 0.0);
        }

        return 0.0;
    }
}
