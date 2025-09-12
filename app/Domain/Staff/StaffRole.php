<?php
namespace App\Domain\Staff;

use App\Models\Staff;

interface StaffRole
{
    /**
     * Validate role-specific rules BEFORE creating/saving the Staff model.
     * Should throw ValidationException on failures.
     */
    public function validate(array $data): void;

    /**
     * Mutate/prepare data before creating the Staff model.
     */
    public function prepare(array $data): array;

    /**
     * Role-specific work right after Staff is created (e.g., sync pivots).
     */
    public function afterCreate(Staff $staff, array $data): void;

    /**
     * Role-specific work after Staff is updated (e.g., resync pivots).
     */
    public function afterSave(Staff $staff, array $data): void;

    /**
     * Cleanup or notifications after Staff is deleted.
     */
    public function afterDelete(Staff $staff): void;
}