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

    /**
     * Clinic staff manage SERVICES only.
     * Ensure services are synced and packages are cleared.
     */
    public function afterCreate(Staff $staff, array $data): void
    {
        $staff->services()->sync($data['services'] ?? []);
        $staff->packages()->sync([]);
    }

    /**
     * Keep behavior consistent on update as well.
     */
    public function afterSave(Staff $staff, array $data): void
    {
        $staff->services()->sync($data['services'] ?? []);
        $staff->packages()->sync([]);
    }
}