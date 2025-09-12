<?php

namespace App\Domain\Staff;

use App\Domain\Staff\UnsupportedRoleException;
use InvalidArgumentException;
use App\Domain\Staff\Roles\ClinicRole;
use App\Domain\Staff\Roles\GroomerRole;

/**
 * StaffFactory implements the Factory Method design pattern.
 *
 * It centralizes the creation of StaffRole objects (e.g., ClinicRole, GroomerRole).
 * This ensures that StaffResource and other clients remain decoupled from
 * the concrete role classes, relying only on the StaffRole interface.
 *
 * Benefits:
 * - Encapsulation of object creation logic
 * - Easier extensibility: new roles (e.g., TrainerRole) can be added with minimal changes
 * - Improved readability and maintainability
 */
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
            default   => throw new UnsupportedRoleException("Unsupported staff role: {$role}"),
        };
    }
}
