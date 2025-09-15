<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Pet;
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
     * Releases payment using the customer’s 6‑digit release code
     * and completes the associated booking.
     */
    public function release(Booking $booking, string $releaseCode): void
    {
        // Look up the pending wallet transaction that matches the code and booking
        $transaction = WalletTransaction::where('release_code', $releaseCode)
            ->where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            throw new Exception('Invalid release code or transaction already completed.');
        }

        // Make sure we can resolve the merchant’s wallet
        $wallet = $transaction->wallet;
        if (!$wallet) {
            throw new Exception('Merchant wallet not found.');
        }

        // Release the funds. The wallet handles fee calculations and platform allocations.
        $success = $wallet->releaseFunds($releaseCode);
        
        if (!$success) {
            throw new Exception('Failed to release funds. Please try again.');
        }

        // Mark the booking as completed
        $booking->update(['status' => 'completed']);

        // For adoptions, also mark the pet as adopted
        $this->updatePetStatusIfAdoption($booking);

        // Let both the customer and merchant know we’re done
        $this->sendCompletionNotifications($booking, $transaction);
    }

    /**
     * If this booking is for an adoption, update the pet to “Adopted”.
     */
    private function updatePetStatusIfAdoption(Booking $booking): void
    {
        if ($booking->booking_type === 'adoption' && $booking->pet_id) {
            try {
                $pet = Pet::find($booking->pet_id);
                if ($pet) {
                    $pet->update([
                        'status' => Pet::STATUS_ADOPTED,
                        'adopted_at' => now()
                    ]);

                    \Log::info('Pet status updated to adopted', [
                        'pet_id' => $pet->id,
                        'booking_id' => $booking->id,
                        'adopted_at' => now()
                    ]);
                } else {
                    \Log::warning('Pet not found for adoption booking', [
                        'pet_id' => $booking->pet_id,
                        'booking_id' => $booking->id
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to update pet status to adopted', [
                    'pet_id' => $booking->pet_id,
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
        * Notifies both parties (customer and merchant) that the booking was completed
        * and payment was released.
        */
    private function sendCompletionNotifications(Booking $booking, WalletTransaction $transaction): void
    {
        \Log::info('ReleaseCodeService::sendCompletionNotifications called', ['booking_id' => $booking->id, 'transaction_id' => $transaction->id]);
        
        // Ensure the customer relation is available for notifications
        $booking->load('customer');
        
        // Notify the merchant (database + email)
        $merchant = User::find($booking->merchant_id);
        if ($merchant) {
            \Log::info('Sending BookingCompletedNotification to merchant', ['merchant_id' => $merchant->id, 'booking_id' => $booking->id]);
            $merchant->notify(new BookingCompletedNotification($booking, $transaction, 'merchant'));
        } else {
            \Log::warning('Merchant not found for booking completion', ['merchant_id' => $booking->merchant_id, 'booking_id' => $booking->id]);
        }
        
        // Notify the customer (database + email)
        if ($booking->customer) {
            \Log::info('Sending BookingCompletedNotification to customer', ['customer_id' => $booking->customer->id, 'booking_id' => $booking->id]);
            $booking->customer->notify(new BookingCompletedNotification($booking, $transaction, 'customer'));
        } else {
            \Log::warning('Customer not found for booking completion', ['customer_id' => $booking->customer_id, 'booking_id' => $booking->id]);
        }
    }
}
