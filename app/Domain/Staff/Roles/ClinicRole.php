<?php

namespace App\Domain\Staff\Roles;

use App\Domain\Staff\StaffRole;
use App\Models\Staff;

class ClinicRole implements StaffRole
{
    public function prepare(array $data): array
    {
        $data['role']   = 'clinic';
        $data['status'] = $data['status'] ?? 'active';
        return $data;
    }

    public function afterCreate(Staff $staff, array $data): void
    {
        // Attach services or packages here if needed
        // $staff->services()->sync($data['services'] ?? []);
        // $staff->packages()->sync($data['packages'] ?? []);
    }
}