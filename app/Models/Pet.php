<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = [
        'name',
        'pet_type_id',
        'pet_breed_id',
        'size_id',
        'sex',
        'age_months',
        'photo_path',
        'status',
    ];

    public function petType()   { return $this->belongsTo(PetType::class,  'pet_type_id'); }
    public function petBreed()  { return $this->belongsTo(PetBreed::class, 'pet_breed_id'); }
    public function size()      { return $this->belongsTo(Size::class,     'size_id'); }

    public function type()  { return $this->petType(); }
    public function breed() { return $this->petBreed(); }
  
    public function scopeAdoptable($q)
    {
        return $q->where('status', 'available');
    }


    public function getPhotoUrlAttribute(): string
    {
        $p = $this->photo_path;
        if (!$p) return asset('images/placeholder/pet.png');
        return str_starts_with($p, 'http') ? $p : asset('storage/' . ltrim($p, '/'));
    }

    // “2 yrs 3 mo” style age
    public function getAgeHumanAttribute(): string
    {
        $m = (int) ($this->age_months ?? 0);
        $y = intdiv($m, 12);
        $r = $m % 12;
        $parts = [];
        if ($y) $parts[] = $y.' yr'.($y > 1 ? 's' : '');
        if ($r) $parts[] = $r.' mo';
        return $parts ? implode(' ', $parts) : '—';
    }
}
