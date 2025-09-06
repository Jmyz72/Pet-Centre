<?php
namespace App\Domain\Staff\Roles;
use App\Domain\Staff\StaffRole;
use App\Models\Staff;

class GroomerRole implements StaffRole {
    public function prepare(array $data): array {
        $data['role']   = 'groomer';
        $data['status'] = $data['status'] ?? 'active';
        return $data;
    }
    public function afterCreate(Staff $staff, array $data): void {
        // attach services/packages later if you add those form fields
    }
}