<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\WalletTransaction;
use Exception;

/**
 * ReleaseCodeService
 * ------------------------------------------------------------
 * Handles the release of funds using customer release codes
 * and completes bookings when payment is authorized.
 */
class ReleaseCodeService
{
    /**
     * Release payment using customer's release code
     */
    public function release(Booking $booking, string $releaseCode): void
    {
        // Find the wallet transaction with this release code
        $transaction = WalletTransaction::where('release_code', $releaseCode)
            ->where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            throw new Exception('Invalid release code or transaction already completed.');
        }

        // Get the merchant wallet
        $wallet = $transaction->wallet;
        if (!$wallet) {
            throw new Exception('Merchant wallet not found.');
        }

        // Release the funds (this handles fee calculations and platform wallets)
        $success = $wallet->releaseFunds($releaseCode);
        
        if (!$success) {
            throw new Exception('Failed to release funds. Please try again.');
        }

        // Update booking status to completed
        $booking->update(['status' => 'completed']);
    }
}