<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

/**
 * ReleaseCodeService
 * ------------------------------------------------------------
 * Encapsulates the business logic for generating and verifying
 * a one-time release code that authorizes payout and completes
 * a booking. Designed for reuse by web controllers and JSON APIs.
 */
class ReleaseCodeService
{
    /** Length of the one-time code (digits only). */
    public const CODE_LENGTH = 6;

    /** Expiry window in minutes for the generated code. */
    public const EXPIRY_MINUTES = 30;

    /**
     * Generate a one-time code for the given booking, store a hash + expiry
     * inside Booking.meta, and return the plain code + expiry (to be shown
     * to the customer once only).
     */
    public function generate(Booking $booking): array
    {
        $code = $this->generateNumericCode(static::CODE_LENGTH);
        $expiresAt = now()->addMinutes(static::EXPIRY_MINUTES);

        $meta = $this->asArray($booking->meta);
        $meta['release_code_hash'] = Hash::make($code);
        $meta['release_code_expires_at'] = $expiresAt->toIso8601String();

        $booking->meta = $meta;
        $booking->save();

        return [
            'code' => $code,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Verify a submitted code and, if valid and unexpired, mark the booking
     * as completed and clear the stored code artifacts.
     */
    public function release(Booking $booking, string $code): void
    {
        $meta = $this->asArray($booking->meta);

        $hash = $meta['release_code_hash'] ?? null;
        $expires = isset($meta['release_code_expires_at'])
            ? Carbon::parse($meta['release_code_expires_at'])
            : null;

        // Validate presence and expiry
        abort_unless($hash && $expires && now()->lt($expires), 422, 'Code missing or expired');

        // Validate correctness
        abort_unless(Hash::check($code, $hash), 422, 'Invalid code');

        // Business effect: complete the booking and stamp released_at
        $booking->status = 'completed';
        $meta['released_at'] = now()->toIso8601String();

        // Clean up sensitive data
        unset($meta['release_code_hash'], $meta['release_code_expires_at']);
        $booking->meta = $meta;
        $booking->save();
    }

    /** Convert mixed JSON/array meta into array safely. */
    private function asArray(mixed $meta): array
    {
        if (is_array($meta)) {
            return $meta;
        }
        if (is_string($meta) && $meta !== '') {
            $decoded = json_decode($meta, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return [];
    }

    /** Generate a numeric string of given length using a CSPRNG. */
    private function generateNumericCode(int $length): string
    {
        $min = (int) str_pad('1', $length, '0'); // e.g., 100000
        $max = (int) str_pad('', $length, '9');  // e.g., 999999
        return (string) random_int($min, $max);
    }
}