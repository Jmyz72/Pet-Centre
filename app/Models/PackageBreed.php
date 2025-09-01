<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBreed extends Model
{
    public $timestamps = false;
    protected $fillable = ['package_id', 'pet_breed_id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function breed()
    {
        return $this->belongsTo(PetBreed::class, 'pet_breed_id');
    }
}
