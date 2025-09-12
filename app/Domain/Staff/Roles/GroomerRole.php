<?php
namespace App\Domain\Staff\Roles;

use App\Domain\Staff\StaffRole;
use App\Models\Staff;

class GroomerRole implements StaffRole
{
    public function prepare(array $data): array
    {
        $data['role']   = 'groomer';
        $data['status'] = $data['status'] ?? 'active';
        return $data;
    }

    /**
     * Groomers manage PACKAGES only.
     * Ensure packages are synced and services are cleared.
     */
    public function afterCreate(Staff $staff, array $data): void
    {
        $staff->packages()->sync($data['packages'] ?? []);
        $staff->services()->sync([]);
    }

    /**
     * Keep behavior consistent on update as well.
     */
    public function afterSave(Staff $staff, array $data): void
    {
        $staff->packages()->sync($data['packages'] ?? []);
        $staff->services()->sync([]);
    }
}