<?php

namespace App\Bookings;

use App\Models\Service;
use Carbon\Carbon;
use InvalidArgumentException;

/**
 * ServiceBooking
 *
 * Concrete booking flow for CLINIC services.
 * Requires a staff assignment and schedules a block with that staff.
 */
class ServiceBooking extends BookingTemplate
{
    /**
     * Guard that a staff member is selected for service bookings.
     */
    protected function afterGuardAvailability(array $data, Carbon $start, Carbon $end): void
    {
        if (empty($data['staff_id'])) {
            throw new InvalidArgumentException('A staff member must be selected for service bookings.');
        }
    }

    /**
     * Duration comes from the service record (fallback to 60 minutes).
     */
    protected function getDurationMinutes(array $data): int
    {
        $serviceId = (int) ($data['service_id'] ?? 0);
        if ($serviceId <= 0) {
            throw new InvalidArgumentException('service_id is required for service bookings.');
        }

        $svc = Service::findOrFail($serviceId);

        return (int) ($svc->duration_minutes ?? 60);
    }

    /**
     * Price comes from the service record (fallback to 0.0).
     */
    protected function computeAmount(array $data): float
    {
        $serviceId = (int) ($data['service_id'] ?? 0);
        if ($serviceId <= 0) {
            throw new InvalidArgumentException('service_id is required for service bookings.');
        }

        $svc = Service::findOrFail($serviceId);

        return (float) ($svc->price ?? 0.0);
    }
}