<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'staff';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'merchant_id',
        'name',
        'email',
        'phone',
        'role',    // e.g. 'groomer', 'clinic'
        'status',  // e.g. 'active', 'inactive'
    ];

    /**
     * Relationships
     */

    // Each staff belongs to a merchant profile
    public function merchant()
    {
        return $this->belongsTo(MerchantProfile::class);
    }

    // Many-to-many: staff can perform many services
    public function services()
    {
        return $this->belongsToMany(Service::class, 'staff_service');
    }

    // Many-to-many: staff can be assigned to many packages
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'staff_package');
    }

    // One-to-many: per-staff operating hours
    public function operatingHours()
    {
        return $this->hasMany(StaffOperatingHour::class);
    }
}
