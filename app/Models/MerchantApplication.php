<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

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
