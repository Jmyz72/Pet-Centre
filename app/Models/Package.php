<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'merchant_id',
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
    ];

    public function merchantProfile()
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }

    public function packageTypes()
    {
        return $this->belongsToMany(
            PackageType::class,
            'package_package_types', // pivot table
            'package_id',            // this model's FK on pivot
            'package_type_id'        // related model's FK on pivot
        );
    }

    public function petTypes()
    {
        return $this->belongsToMany(
            PetType::class,
            'package_pet_types', // pivot table
            'package_id',        // this model's FK on pivot
            'pet_type_id'        // related model's FK on pivot
        );
    }
    
    public function petBreeds()
    {
        return $this->belongsToMany(
            PetBreed::class,
            'package_breeds', // pivot table
            'package_id',     // this model's FK on pivot
            'pet_breed_id'    // related model's FK on pivot
        );
    }
    
    public function packageSizes()
    {
        return $this->belongsToMany(
            Size::class,
            'package_sizes', // pivot table
            'package_id',    // this model's FK on pivot
            'size_id'        // related model's FK on pivot
        );
    }

    public function variations()
    {
        return $this->hasMany(PackageVariation::class, 'package_id');
    }
}
