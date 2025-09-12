<?php

namespace App\Bookings;

use App\Models\Booking;
use App\Models\BookingHold;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Template Method for all booking flows (adoption, service, package).
 *
 * Controllers should collect/validate request data and then call:
 *
 *   $booking = $concreteBooking->process($payloadArray);
 *
 * The skeleton of the algorithm is fixed here. Concrete subclasses only
 * customise the variable steps by overriding abstract methods / hooks.
 */
abstract class BookingTemplate
{
    /**
     * Orchestrate the end‑to‑end booking transaction.
     *
     * @param  array  $data Normalised input:
     *                      - merchant_id (int)
     *                      - customer_id (int)
     *                      - staff_id (int|null)   // null for adoption
     *                      - start_at (string|Carbon)
     *                      - idempotency_key (string|null)
     *                      - plus any item-specific identifiers (service_id/package_id/pet_id/customer_pet_id)
     * @return \App\Models\Booking
     */
    public function process(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // 1) Normalise & enrich context common to all bookings
            $data = $this->normalise($data);

            // 2) Let subclass provide duration (minutes) and price (amount)
            $duration = $this->getDurationMinutes($data);
            $amount   = $this->computeAmount($data);

            [$start, $end] = $this->calcWindow($data['start_at'], $duration);

            // 3) Guard rails: availability checks (subclasses can extend via hook)
            $this->guardAvailability($data, $start, $end);
            $this->afterGuardAvailability($data, $start, $end);

            // 4) Create a hold to prevent race conditions
            $hold = $this->createHold($data, $start, $end);

            // 5) Take payment (default: COD/no-op). Subclass may override.
            $this->processPayment($hold, $amount, $data);

            // 6) Create schedule block (staff may be null for adoption)
            $schedule = $this->createSchedule($data, $start, $end);

            // 7) Persist final booking row
            $booking = $this->finaliseBooking($data, $start, $end, $amount);

            // 8) Link schedule→booking & flip hold status
            if ($schedule) {
                $schedule->update(['booking_id' => $booking->id]);
            }
            $hold->update(['status' => 'converted']);

            // 9) Optional post-processing hook
            $this->afterFinalised($booking, $data);

            return $booking->fresh();
        });
    }

    // ─────────────────────────────────────────────────────────────────────────────
    //                              Abstract steps
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Calculate booking duration (minutes) for the item (service/package/adoption).
     */
    abstract protected function getDurationMinutes(array $data): int;

    /**
     * Calculate the total payable amount for this booking.
     */
    abstract protected function computeAmount(array $data): float;

    // ─────────────────────────────────────────────────────────────────────────────
    //                                   Hooks
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Allow subclasses to add extra guards (e.g., inventory, merchant rules).
     */
    protected function afterGuardAvailability(array $data, Carbon $start, Carbon $end): void
    {
        // default: no extra checks
    }

    /**
     * Optional after-finalised hook (e.g., notifications).
     */
    protected function afterFinalised(Booking $booking, array $data): void
    {
        // default: do nothing
    }

    /**
     * Payment step – by default we assume COD (no external charge).
     * Subclasses can override to integrate card/FPX later.
     */
    protected function processPayment(BookingHold $hold, float $amount, array $data): void
    {
        // default: accept without charging
    }

    // ─────────────────────────────────────────────────────────────────────────────
    //                           Fixed/common implementations
    // ─────────────────────────────────────────────────────────────────────────────

    protected function normalise(array $data): array
    {
        // Convert start_at to Carbon, keep original string for persistence
        if (!($data['start_at'] instanceof Carbon)) {
            $data['start_at'] = Carbon::parse($data['start_at']);
        }
        // Ensure required minimal keys exist
        $data['merchant_id'] = (int) ($data['merchant_id'] ?? 0);
        $data['customer_id'] = (int) ($data['customer_id'] ?? 0);
        $data['staff_id']    = Arr::has($data, 'staff_id') ? (int) $data['staff_id'] : null;

        return $data;
    }

    /**
     * Compute start/end window for the booking.
     */
    protected function calcWindow(Carbon $start, int $minutes): array
    {
        $end = (clone $start)->addMinutes($minutes);
        return [$start, $end];
    }

    /**
     * Basic availability checks:
     *  - No overlapping schedules for the same staff
     *  - (Hook available for more rules)
     */
    protected function guardAvailability(array $data, Carbon $start, Carbon $end): void
    {
        if (!empty($data['staff_id'])) {
            $overlap = Schedule::query()
                ->where('staff_id', $data['staff_id'])
                ->where(function ($q) use ($start, $end) {
                    // Canonical interval overlap: [A,B) overlaps [C,D) iff A < D AND C < B
                    $q->where('start_at', '<', $end)
                      ->where('end_at', '>', $start);
                })
                ->lockForUpdate()
                ->exists();

            if ($overlap) {
                throw ValidationException::withMessages([
                    'start_at' => 'Selected staff is not available for the chosen time window.',
                ]);
            }
        }
    }

    /**
     * Create a booking hold row to reserve the slot while we charge & schedule.
     */
    protected function createHold(array $data, Carbon $start, Carbon $end): BookingHold
    {
        $expiresAt = now()->addMinutes(15);

        return BookingHold::query()->create([
            'merchant_id'     => $data['merchant_id'],
            'staff_id'        => $data['staff_id'] ?? null,
            'customer_pet_id' => $data['customer_pet_id'] ?? null,
            'pet_id'          => $data['pet_id'] ?? null,
            'service_id'      => $data['service_id'] ?? null,
            'package_id'      => $data['package_id'] ?? null,
            'start_at'        => $start,
            'status'          => 'held',
            'expires_at'      => $expiresAt,
            'idempotency_key' => $data['idempotency_key'] ?? bin2hex(random_bytes(16)),
            'meta'            => $data['meta'] ?? null,
        ]);
    }

    /**
     * Create a schedule block for this booking.
     */
    protected function createSchedule(array $data, Carbon $start, Carbon $end): ?Schedule
    {
        // Adoption might not have staff
        return Schedule::query()->create([
            'merchant_id' => $data['merchant_id'],
            'staff_id'    => $data['staff_id'] ?? null,
            'start_at'    => $start,
            'end_at'      => $end,
            'block_type'  => 'booking',
            'booking_id'  => null,
        ]);
    }

    /**
     * Persist the final booking row.
     */
    protected function finaliseBooking(array $data, Carbon $start, Carbon $end, float $amount): Booking
    {
        $payload = [
            'merchant_id'     => $data['merchant_id'],
            'customer_id'     => $data['customer_id'],
            'staff_id'        => $data['staff_id'] ?? null,
            'customer_pet_id' => $data['customer_pet_id'] ?? null,
            'pet_id'          => $data['pet_id'] ?? null,
            'service_id'      => $data['service_id'] ?? null,
            'package_id'      => $data['package_id'] ?? null,
            'booking_type'    => $data['booking_type'], // 'adoption' | 'service' | 'package'
            'start_at'        => $start,
            'end_at'          => $end,
            'amount'          => $amount,
            'status'          => 'confirmed',
            'meta'            => $data['meta'] ?? null,
        ];

        return Booking::query()->create($payload);
    }
}
