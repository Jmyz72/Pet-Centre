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
            'package_package_types',
            'package_id',
            'package_type_id'
        );
    }

    public function petTypes()
    {
        return $this->belongsToMany(
            PetType::class,
            'package_pet_types',
            'package_id',
            'pet_type_id'
        );
    }
    
    public function petBreeds()
    {
        return $this->belongsToMany(
            PetBreed::class,
            'package_breeds',
            'package_id',
            'pet_breed_id'
        );
    }
    
    public function packageSizes()
    {
        return $this->belongsToMany(
            Size::class,
            'package_sizes',
            'package_id',
            'size_id'
        );
    }

    public function variations()
    {
        return $this->hasMany(PackageVariation::class, 'package_id');
    }

    public function staff()
    {
        return $this->belongsToMany(
            Staff::class,
            'staff_package',
            'package_id',
            'staff_id'
        );
    }
}
