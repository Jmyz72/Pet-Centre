<?php
namespace App\Domain\Staff;
use App\Models\Staff;

interface StaffRole {
    public function prepare(array $data): array;              // before create
    public function afterCreate(Staff $staff, array $data): void; // after create
}