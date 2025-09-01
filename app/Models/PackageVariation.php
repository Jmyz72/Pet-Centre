<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageVariation extends Model
{
    protected $fillable = [
        'package_id',
        'package_pet_type_id',
        'package_size_id',
        'package_breed_id',
        'price',
        'duration_minutes',
        'is_active',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function petTypePivot()
    {
        return $this->belongsTo(PackagePetType::class, 'package_pet_type_id');
    }

    public function sizePivot()
    {
        return $this->belongsTo(PackageSize::class, 'package_size_id');
    }

    public function breedPivot()
    {
        return $this->belongsTo(PackageBreed::class, 'package_breed_id');
    }
}
