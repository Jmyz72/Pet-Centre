<?php

namespace App\Bookings;

use InvalidArgumentException;

/**
 * BookingFactory
 *
 * Simple factory that returns the correct concrete BookingTemplate
 * implementation based on a booking_type string.
 *
 * Usage in controller:
 *
 *   $flow = BookingFactory::make($validated['booking_type']);
 *   $booking = $flow->process($validated);
 */
class BookingFactory
{
    /**
     * Create the appropriate booking flow.
     *
     * @param  string  $type   'adoption' | 'service' | 'package'
     * @return \App\Bookings\BookingTemplate
     */
    public static function make(string $type): BookingTemplate
    {
        $key = strtolower(trim($type));

        return match ($key) {
            'adoption' => new AdoptionBooking(),
            'service'  => new ServiceBooking(),
            'package'  => new PackageBooking(),
            default    => throw new InvalidArgumentException("Unknown booking type: {$type}")
        };
    }
}