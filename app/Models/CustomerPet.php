<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPet extends Model
{
    protected $fillable = [
        'user_id','pet_type_id','pet_breed_id','size_id',
        'name','sex','birthdate','weight_kg','photo_path','description',
    ];
    protected $casts = ['birthdate' => 'date','weight_kg' => 'decimal:2'];

    public function owner() { return $this->belongsTo(User::class,'user_id'); }
    public function type()  { return $this->belongsTo(PetType::class,'pet_type_id'); }
    public function breed() { return $this->belongsTo(PetBreed::class,'pet_breed_id'); }
    public function size()  { return $this->belongsTo(Size::class,'size_id'); }

    protected static function booted(): void
    {
        static::saving(function (CustomerPet $pet) {
            $weight = $pet->weight_kg;

            if ($weight !== null && $weight !== '') {
                $w = (float) $weight;

                $size = Size::select('id')
                    ->whereRaw('? >= COALESCE(min_weight, -1e9)', [$w])
                    ->whereRaw('? <= COALESCE(max_weight,  1e9)', [$w])
                    ->orderBy('min_weight')
                    ->first();

                $pet->size_id = $size?->id;
            } else {
                $pet->size_id = null;
            }
        });
    }
}
