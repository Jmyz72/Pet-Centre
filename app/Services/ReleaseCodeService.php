<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Notifications\BookingCompletedNotification;
use App\Mail\BookingCompletedMail;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

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

        // Send completion notifications to both customer and merchant
        $this->sendCompletionNotifications($booking, $transaction);
    }

    /**
     * Send completion notifications to customer and merchant
     */
    private function sendCompletionNotifications(Booking $booking, WalletTransaction $transaction): void
    {
        \Log::info('ReleaseCodeService::sendCompletionNotifications called', ['booking_id' => $booking->id, 'transaction_id' => $transaction->id]);
        
        // Load the customer relationship for notification
        $booking->load('customer');
        
        // Send notification to merchant (User model supports database + email notifications)
        $merchant = User::find($booking->merchant_id);
        if ($merchant) {
            \Log::info('Sending BookingCompletedNotification to merchant', ['merchant_id' => $merchant->id, 'booking_id' => $booking->id]);
            $merchant->notify(new BookingCompletedNotification($booking, $transaction, 'merchant'));
        } else {
            \Log::warning('Merchant not found for booking completion', ['merchant_id' => $booking->merchant_id, 'booking_id' => $booking->id]);
        }
        
        // Send notification to customer (User model supports database + email notifications)
        if ($booking->customer) {
            \Log::info('Sending BookingCompletedNotification to customer', ['customer_id' => $booking->customer->id, 'booking_id' => $booking->id]);
            $booking->customer->notify(new BookingCompletedNotification($booking, $transaction, 'customer'));
        } else {
            \Log::warning('Customer not found for booking completion', ['customer_id' => $booking->customer_id, 'booking_id' => $booking->id]);
        }
    }
}