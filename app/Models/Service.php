<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'merchant_id',
        'service_type_id',
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function merchantProfile()
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }

    public function staff()
    {
        return $this->belongsToMany(
            Staff::class,
            'staff_service',
            'service_id',
            'staff_id'
        );
    }

}
