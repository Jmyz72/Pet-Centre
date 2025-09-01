<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetType extends Model
{
    protected $fillable = ['name', 'description'];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function breeds()
    {
        return $this->hasMany(PetBreed::class);
    }
}
