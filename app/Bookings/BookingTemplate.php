<?php

namespace App\Bookings;

use App\Models\Booking;
use App\Models\BookingHold;
use App\Models\Schedule;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\BookingCreatedNotification;
use App\Mail\BookingCreatedMail;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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
     * Stores the payment ID created during processing for later linking
     */
    protected ?int $paymentId = null;

    /**
     * Create a hold for the booking without finalising it.
     *
     * Runs steps 1–4: normalise, compute duration, compute amount, calc window,
     * guard availability, then create and return the hold.
     *
     * @param array $data
     * @return BookingHold
     */
    public function holdOnly(array $data): BookingHold
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

            return $hold;
        });
    }

    /**
     * Complete a booking from an existing BookingHold object.
     * 
     * @param BookingHold $hold
     * @return Booking
     */
    public function completeFromHold(BookingHold $hold): Booking
    {
        return DB::transaction(function () use ($hold) {
            // Convert hold data back to array format for processing
            $data = [
                'merchant_id' => $hold->merchant_id,
                'customer_id' => $hold->customer_id,
                'staff_id' => $hold->staff_id,
                'customer_pet_id' => $hold->customer_pet_id,
                'pet_id' => $hold->pet_id,
                'service_id' => $hold->service_id,
                'package_id' => $hold->package_id,
                'booking_type' => $hold->booking_type,
                'start_at' => $hold->start_at->format('Y-m-d H:i:s'),
                'idempotency_key' => $hold->idempotency_key,
                'meta' => $hold->meta,
            ];

            // Get duration and amount
            $duration = $this->getDurationMinutes($data);
            $amount = $hold->calculateAmount();

            // Use the Carbon object directly from the hold
            [$start, $end] = $this->calcWindow($hold->start_at, $duration);

            // 5) Take payment (default: COD/no-op). Subclass may override.
            $this->processPayment($hold, $amount, $data);

            // 6) Persist final booking row (before schedule so booking_id is available)
            $booking = $this->finaliseBooking($data, $start, $end, $amount);

            // 7) Create schedule block (staff may be null for adoption) with non-null booking_id
            $schedule = $this->createSchedule($data, $start, $end, $booking->id);

            // 8) Attach any payment created during this transaction
            if (isset($this->paymentId)) {
                $payment = Payment::find($this->paymentId);
                if ($payment) {
                    $payment->update(['booking_id' => $booking->id]);

                    // Also update the booking with the payment reference
                    $booking->update(['payment_ref' => $payment->payment_ref]);
                }
            }

            // 9) Mark hold as converted
            $hold->update(['status' => BookingHold::STATUS_CONVERTED]);

            // 10) Add payment to merchant's pending wallet
            $this->addToMerchantWallet($booking, $amount);

            // 11) Run post-finalisation logic (optional hook)
            $this->afterFinalised($booking, $data);

            return $booking->fresh();
        });
    }

    /**
     * Finalise a booking from an existing hold.
     *
     * Runs steps 5–9: process payment, create booking, create schedule,
     * attach payment via idempotency_key, update hold status to converted,
     * run afterFinalised(), and return fresh booking.
     *
     * @param array $data
     * @return Booking
     */
    public function finaliseFromHold(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // 1) Normalise & enrich context common to all bookings
            $data = $this->normalise($data);

            // 2) Let subclass provide duration (minutes) and price (amount)
            $duration = $this->getDurationMinutes($data);
            $amount   = $this->computeAmount($data);

            [$start, $end] = $this->calcWindow($data['start_at'], $duration);

            // Retrieve existing hold by idempotency_key or other identifier
            $hold = BookingHold::query()
                ->where('idempotency_key', $data['idempotency_key'])
                ->lockForUpdate()
                ->firstOrFail();

            // 5) Take payment (default: COD/no-op). Subclass may override.
            $this->processPayment($hold, $amount, $data);

            // 6) Persist final booking row (before schedule so booking_id is available)
            $booking = $this->finaliseBooking($data, $start, $end, $amount);

            // 7) Create schedule block (staff may be null for adoption) with non-null booking_id
            $schedule = $this->createSchedule($data, $start, $end, $booking->id);

            // Attach any payment created during this transaction using the same idempotency key
            if (!empty($data['idempotency_key'])) {
                $payment = Payment::query()
                    ->whereNull('booking_id')
                    ->where('idempotency_key', $data['idempotency_key'])
                    ->latest('id')
                    ->first();
                    
                if ($payment) {
                    $payment->update(['booking_id' => $booking->id]);
                    
                    // Also update the booking with the payment reference
                    $booking->update(['payment_ref' => $payment->payment_ref]);
                }
            }

            // 8) Link schedule→booking & flip hold status
            // booking_id is already set on schedule, so no update here
            $hold->update(['status' => \App\Models\BookingHold::STATUS_CONVERTED]);

            // 9) Optional post-processing hook
            $this->afterFinalised($booking, $data);

            return $booking->fresh();
        });
    }

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

            // 6) Persist final booking row (before schedule so booking_id is available)
            $booking = $this->finaliseBooking($data, $start, $end, $amount);

            // 7) Create schedule block (staff may be null for adoption) with non-null booking_id
            $schedule = $this->createSchedule($data, $start, $end, $booking->id);

            // Attach any payment created during this transaction using the same idempotency key
            if (!empty($data['idempotency_key'])) {
                $payment = Payment::query()
                    ->whereNull('booking_id')
                    ->where('idempotency_key', $data['idempotency_key'])
                    ->latest('id')
                    ->first();
                    
                if ($payment) {
                    $payment->update(['booking_id' => $booking->id]);
                    
                    // Also update the booking with the payment reference
                    $booking->update(['payment_ref' => $payment->payment_ref]);
                }
            }

            // 8) Link schedule→booking & flip hold status
            // booking_id is already set on schedule, so no update here
            $hold->update(['status' => \App\Models\BookingHold::STATUS_CONVERTED]);

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
        \Log::info('BookingTemplate::afterFinalised called', ['booking_id' => $booking->id]);
        
        // Load the customer relationship for notification
        $booking->load('customer');
        
        // Send notification to merchant (User model supports database + email notifications)
        $merchant = User::find($booking->merchant_id);
        if ($merchant) {
            \Log::info('Sending BookingCreatedNotification to merchant', ['merchant_id' => $merchant->id, 'booking_id' => $booking->id]);
            $merchant->notify(new BookingCreatedNotification($booking, 'merchant'));
        } else {
            \Log::warning('Merchant not found for booking', ['merchant_id' => $booking->merchant_id, 'booking_id' => $booking->id]);
        }
        
        // Send notification to customer (User model supports database + email notifications)
        if ($booking->customer) {
            \Log::info('Sending BookingCreatedNotification to customer', ['customer_id' => $booking->customer->id, 'booking_id' => $booking->id]);
            $booking->customer->notify(new BookingCreatedNotification($booking, 'customer'));
        } else {
            \Log::warning('Customer not found for booking', ['customer_id' => $booking->customer_id, 'booking_id' => $booking->id]);
        }
    }

    /**
     * Payment step – by default we assume COD (no external charge).
     * Subclasses can override to integrate card/FPX later.
     */
    protected function processPayment(BookingHold $hold, float $amount, array $data): void
    {
        // Basic Strategy-less mock implementation that persists a payment row.
        // You can later swap this for a real gateway via a Strategy/Factory.
        $method = $data['payment_method'] ?? 'fpx'; // 'fpx' | 'card'

        // Create a provider reference + meta depending on method
        if ($method === 'card') {
            $digits = preg_replace('/\D+/', '', (string)($data['card_number'] ?? ''));
            $last4  = $digits ? substr($digits, -4) : null;
            $ref    = 'card_'.bin2hex(random_bytes(6));
            $meta   = [
                'card_name'   => $data['card_name']   ?? null,
                'card_brand'  => $data['card_brand']  ?? null,
                'card_expiry' => $data['card_expiry'] ?? null,
                'last4'       => $last4,
            ];
            $provider = 'card';
        } else {
            // default to FPX
            $ref      = 'fpx_'.bin2hex(random_bytes(6));
            $meta     = [ 'bank' => $data['bank'] ?? null ];
            $provider = 'fpx';
        }

        // Generate a unique idempotency key for the payment (different from booking hold key)
        $paymentIdempotencyKey = 'payment_' . bin2hex(random_bytes(16));

        // Persist payment now (mocked as succeeded). We'll link to booking after it is created.
        $payment = Payment::query()->create([
            'booking_id'      => null,
            'payment_ref'     => $ref,
            'amount'          => $amount,
            'currency'        => 'MYR',
            'status'          => 'succeeded', // mock success; change if you implement redirects
            'provider'        => $provider,
            'idempotency_key' => $paymentIdempotencyKey,
            'meta'            => $meta,
        ]);

        // Store payment ID for later linking
        $this->paymentId = $payment->id;
    }

    /**
     * Add booking payment to merchant's pending wallet balance
     */
    protected function addToMerchantWallet(Booking $booking, float $amount): void
    {
        $merchant = \App\Models\MerchantProfile::find($booking->merchant_id);
        if (!$merchant) {
            return;
        }

        $wallet = $merchant->getWallet();
        $transaction = $wallet->addPendingFunds(
            $amount,
            "Payment for booking #{$booking->id}",
            $booking->id
        );

        // Store the release code in booking metadata for easy access
        $booking->update([
            'meta' => array_merge($booking->meta ?? [], [
                'release_code' => $transaction->release_code
            ])
        ]);
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
        $data['idempotency_key'] = $data['idempotency_key'] ?? bin2hex(random_bytes(16));

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
            'customer_id'     => $data['customer_id'],
            'staff_id'        => $data['staff_id'] ?? null,
            'customer_pet_id' => $data['customer_pet_id'] ?? null,
            'pet_id'          => $data['pet_id'] ?? null,
            'service_id'      => $data['service_id'] ?? null,
            'package_id'      => $data['package_id'] ?? null,
            'booking_type'    => $data['booking_type'],
            'start_at'        => $start,
            'status'          => \App\Models\BookingHold::STATUS_HELD,
            'expires_at'      => $expiresAt,
            'idempotency_key' => $data['idempotency_key'] ?? bin2hex(random_bytes(16)),
            'meta'            => $data['meta'] ?? null,
        ]);
    }

    /**
     * Create a schedule block for this booking.
     */
    protected function createSchedule(array $data, Carbon $start, Carbon $end, ?int $bookingId = null): ?Schedule
    {
        // Adoption might not have staff
        return Schedule::query()->create([
            'merchant_id' => $data['merchant_id'],
            'staff_id'    => $data['staff_id'] ?? null,
            'start_at'    => $start,
            'end_at'      => $end,
            'block_type'  => 'booking',
            'booking_id'  => $bookingId,
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

            // Write to both keys; whichever column exists/ is fillable will be persisted.
            'price_amount'    => $amount,

            'status'          => 'confirmed',
            'idempotency_key' => $data['idempotency_key'],
            'meta'            => $data['meta'] ?? null,
        ];

        return Booking::query()->create($payload);
    }
}
