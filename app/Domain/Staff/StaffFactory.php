<?php

namespace App\Domain\Staff;

use App\Domain\Staff\Roles\ClinicRole;
use App\Domain\Staff\Roles\GroomerRole;

class StaffFactory
{
    /**
     * Return a role-specific behavior object for Staff creation.
     *
     * @param  string  $role  Expected values: 'groomer' | 'clinic'
     */
    public static function make(string $role): StaffRole
    {
        return match (strtolower($role)) {
            'clinic'  => new ClinicRole(),
            'groomer' => new GroomerRole(),
            default   => new GroomerRole(), // safe default
        };
    }
}
