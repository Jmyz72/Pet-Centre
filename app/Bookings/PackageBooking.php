<?php

namespace App\Bookings;

use App\Models\Package;
use App\Models\PackageVariation;
use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;

/**
 * PackageBooking
 *
 * Concrete booking flow for GROOMER packages.
 * Requires a staff assignment and schedules a block with that staff.
 */
class PackageBooking extends BookingTemplate
{
    /**
     * Guard that a staff member is selected for package bookings.
     */
    protected function afterGuardAvailability(array $data, Carbon $start, Carbon $end): void
    {
        if (empty($data['staff_id'])) {
            throw new InvalidArgumentException('A staff member must be selected for package bookings.');
        }
    }

    /**
     * Duration comes from the package record (fallback to 60 minutes).
     */
    protected function getDurationMinutes(array $data): int
    {
        $packageId = (int) ($data['package_id'] ?? 0);
        if ($packageId <= 0) {
            throw new InvalidArgumentException('package_id is required for package bookings.');
        }

        $pkg = Package::findOrFail($packageId);

        return (int) ($pkg->duration_minutes ?? 60);
    }

    /**
     * Compute price using PackageVariation rules:
     * - package_pet_type_id is REQUIRED to match any variation
     * - If breed_id is provided, prefer exact breed match
     * - Else if size_id is provided, prefer exact size match
     * - Else fall back to pet_type-only variation
     * - If no variation matches, use package base_price
     */
    protected function computeAmount(array $data): float
    {
        $packageId   = (int) ($data['package_id'] ?? 0);
        $petTypeId   = isset($data['pet_type_id']) ? (int) $data['pet_type_id'] : null;
        $sizeId      = isset($data['size_id']) ? (int) $data['size_id'] : null;
        $breedId     = isset($data['breed_id']) ? (int) $data['breed_id'] : null;

        if ($packageId <= 0) {
            throw new InvalidArgumentException('package_id is required for package bookings.');
        }

        $pkg = Package::findOrFail($packageId);
        $base = (float) ($pkg->price ?? 0.0);

        // If pet type is not provided, we cannot resolve variations – fall back to base
        if (!$petTypeId) {
            return $base;
        }

        // Resolve variations by pet type via the pivot table (package_pet_types).
        // NOTE: package_variations.package_pet_type_id references package_pet_types.id (NOT pet_types.id)
        $pivotIds = DB::table('package_pet_types')
            ->where('package_id', $packageId)
            ->when($petTypeId, fn($q) => $q->where('pet_type_id', $petTypeId))
            ->pluck('id');

        if ($pivotIds->isEmpty()) {
            return $base; // no mapping for this pet type → use package price
        }

        $variations = PackageVariation::query()
            ->where('package_id', $packageId)
            ->whereIn('package_pet_type_id', $pivotIds)
            ->where('is_active', 1)
            ->get();

        if ($variations->isEmpty()) {
            return $base; // no variations for this type → use package price
        }

        // Choose the most specific match: breed > size > fallback
        $chosen = null;

        if ($breedId) {
            $chosen = $variations->firstWhere('package_breed_id', $breedId);
        }

        if (!$chosen && $sizeId) {
            $chosen = $variations->firstWhere('package_size_id', $sizeId);
        }

        if (!$chosen) {
            $chosen = $variations->first();
        }

        return (float) (optional($chosen)->price ?? $base);
    }
}