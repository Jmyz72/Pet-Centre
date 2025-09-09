<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPet extends Model
{
    protected $fillable = [
        'user_id',
        'pet_type_id',
        'pet_breed_id',
        'size_id',
        'name',
        'sex',
        'birthdate',
        'weight_kg',
        'photo_path',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function petType()
    {
        return $this->belongsTo(PetType::class);
    }
    public function petBreed()
    {
        return $this->belongsTo(PetBreed::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
