<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['label', 'description'];
    
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    
}
