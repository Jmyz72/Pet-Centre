<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantProfile extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'name',
        'phone',
        'address',
        'registration_number',
        'license_number',
        'document_path',
        'photo',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }
}
