<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePetType extends Model
{
    public $timestamps = false;

    protected $fillable = ['package_id', 'pet_type_id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function petType()
    {
        return $this->belongsTo(PetType::class, 'pet_type_id');
    }
}
