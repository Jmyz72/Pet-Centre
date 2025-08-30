<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['label', 'description'];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_size');
    }
    
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    
}
