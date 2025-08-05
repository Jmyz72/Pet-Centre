<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pets extends Model
{
    protected $fillable = [
        'merchant_id',
        'name',
        'pet_type_id',
        'breed',
        'age',
        'image',
        'description',
    ];

    public function petType()
    {
        return $this->belongsTo(PetType::class);
    }
}
