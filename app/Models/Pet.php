<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    /**
     * Pet status constants
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_ADOPTED = 'adopted';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'merchant_id',
        'pet_type_id',
        'pet_breed_id',
        'date_of_birth',
        'name',
        'weight_kg',
        'sex',
        'status',
        'adoption_fee',
        'adopted_at',
        'image',
        'description',
        'size_id',
        'vaccinated'
    ];

    public function petType()
    {
        return $this->belongsTo(PetType::class);
    }

    public function petBreed()
    {
        return $this->belongsTo(PetBreed::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * Check if pet is available for adoption
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Check if pet is reserved for adoption
     */
    public function isReserved(): bool
    {
        return $this->status === self::STATUS_RESERVED;
    }

    /**
     * Check if pet is adopted
     */
    public function isAdopted(): bool
    {
        return $this->status === self::STATUS_ADOPTED;
    }

    protected static function booted(): void
    {
        static::saving(function (Pet $pet) {
            $weight = $pet->weight_kg;

            if ($weight !== null && $weight !== '') {
                $w = (float) $weight;

                // Find size where min_weight ≤ weight ≤ max_weight (NULL bounds are open-ended)
                $size = Size::select('id')
                    ->whereRaw('? >= COALESCE(min_weight, -1e9)', [$w])
                    ->whereRaw('? <= COALESCE(max_weight,  1e9)', [$w])
                    ->orderBy('min_weight')
                    ->first();

                $pet->size_id = $size?->id;
            } else {
                $pet->size_id = null;
            }
        });
    }
}