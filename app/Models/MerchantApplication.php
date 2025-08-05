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
    ];
}
