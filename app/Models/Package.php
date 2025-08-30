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
        return $this->belongsToMany(PackageType::class, 'package_package_type');
    }

    public function petTypes()
    {
        return $this->belongsToMany(PetType::class, 'package_pet_type');
    }

    public function petBreeds()
    {
        return $this->belongsToMany(PetBreed::class, 'package_breed');
    }
    
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'package_size');
    }
}
