<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'merchant_id',
        'name',
        'email',
        'phone',
        'role',
        'status',
    ];

    public function merchant()
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'staff_service', 'staff_id', 'service_id');
    }
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'staff_package', 'staff_id', 'package_id');    }
    public function operatingHours()
    {
        return $this->hasMany(StaffOperatingHour::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
