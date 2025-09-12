<?php


namespace App\Bookings;

use App\Models\Pet;

/**
 * AdoptionBooking
 *
 * Concrete implementation of BookingTemplate for adoption bookings
 * (shelter role merchant). These bookings do not require staff assignment,
 * but they do require a scheduled visit to the shelter.
 */
class AdoptionBooking extends BookingTemplate
{
    /**
     * Adoption visits are fixed duration (e.g., 60 minutes).
     */
    protected function getDurationMinutes(array $data): int
    {
        return 60;
    }

    /**
     * Adoption bookings are charged based on the pet's adoption_fee column.
     * Fallback to 0.0 if not set.
     */
    protected function computeAmount(array $data): float
    {
        $petId = (int) ($data['pet_id'] ?? 0);
        if ($petId <= 0) {
            // If no pet specified, treat as zero fee (or throw in controller before calling)
            return 0.0;
        }

        $pet = Pet::findOrFail($petId);

        return (float) ($pet->adoption_fee ?? 0.0);
    }
}