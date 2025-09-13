<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ReleaseCodeService;
use Illuminate\Http\Request;

class BookingReleaseController extends Controller
{
    public function __construct(private ReleaseCodeService $service)
    {
        // Expect these routes to be registered under web.php with auth + throttle
        // e.g., Route::middleware(['auth','throttle:6,1'])->group(...)
    }

    /**
     * POST /bookings/{booking}/generate-release-code
     * Customer generates a one-time code to authorize payout release.
     */
    public function generate(Booking $booking)
    {
        // Only the owner (customer) can generate
        abort_unless(auth()->id() === (int) $booking->customer_id, 403, 'Not your booking');

        $data = $this->service->generate($booking);

        return response()->json([
            'booking_id' => $booking->id,
            'code'       => $data['code'],                   // shown once
            'expires_at' => $data['expires_at']->toIso8601String(),
        ], 201);
    }

    /**
     * POST /bookings/{booking}/release
     * Merchant submits the code to complete booking and release payment.
     */
    public function release(Booking $booking, Request $request)
    {
        $request->validate([
            'code' => ['required','string','digits:6'],
        ]);

        // Only the owning merchant can release
        $merchantId = optional(auth()->user()->merchantProfile)->id;
        abort_unless($merchantId && (int) $booking->merchant_id === (int) $merchantId, 403, 'Not your booking');

        $this->service->release($booking, (string) $request->input('code'));

        return response()->json([
            'booking_id' => $booking->id,
            'status'     => 'released',
        ]);
    }
}
