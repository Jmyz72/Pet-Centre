<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantApplication extends Model
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
        'status',
        'rejection_reason',
        'can_reapply',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
