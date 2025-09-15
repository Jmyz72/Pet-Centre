<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public WalletTransaction $transaction,
        public string $recipientType // 'customer' or 'merchant'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $bookingType = ucfirst($this->booking->booking_type);
        $amount = number_format($this->transaction->amount, 2);
        $customerName = $this->booking->customer->name ?? 'Customer';
        
        if ($this->recipientType === 'customer') {
            return (new MailMessage)
                ->subject('Service Completed - ' . $bookingType)
                ->greeting('Hello ' . $customerName . '!')
                ->line('Your booking has been completed successfully!')
                ->line('**Service Details:**')
                ->line('Type: ' . $bookingType)
                ->line('Date: ' . $this->booking->start_at->format('d M Y, g:i A'))
                ->line('Reference: ' . $this->booking->payment_ref)
                ->line('**Payment Released:** RM ' . $amount)
                ->line('The merchant has confirmed completion of your service and funds have been released.')
                ->line('Thank you for choosing our services!');
        } else {
            // Merchant-facing email
            return (new MailMessage)
                ->subject('Payment Released - ' . $bookingType)
                ->greeting('Hello!')
                ->line('A booking has been completed and payment has been released to your wallet.')
                ->line('**Booking Details:**')
                ->line('Customer: ' . $customerName)
                ->line('Type: ' . $bookingType)
                ->line('Date: ' . $this->booking->start_at->format('d M Y, g:i A'))
                ->line('Reference: ' . $this->booking->payment_ref)
                ->line('**Amount Received:** RM ' . $amount)
                ->action('View Wallet', url('/merchant/wallet'))
                ->line('Thank you for providing excellent service!');
        }
    }

    public function toArray(object $notifiable): array
    {
        $bookingType = ucfirst($this->booking->booking_type);
        $amount = number_format($this->transaction->amount, 2);
        $customerName = $this->booking->customer->name ?? 'Customer';
        
        if ($this->recipientType === 'customer') {
            $title = 'Service Completed';
            $message = "Your {$bookingType} service has been completed successfully. Payment of RM {$amount} has been released.";
            $actionUrl = url('/bookings/' . $this->booking->id);
        } else {
            $title = 'Payment Released';
            $message = "Payment of RM {$amount} has been released to your wallet for the {$bookingType} booking with {$customerName}.";
            $actionUrl = url('/merchant/wallet');
        }

        return [
            'title' => $title,
            'message' => $message,
            'booking_id' => $this->booking->id,
            'booking_type' => $this->booking->booking_type,
            'customer_name' => $customerName,
            'start_at' => $this->booking->start_at,
            'payment_ref' => $this->booking->payment_ref,
            'amount' => $this->transaction->amount,
            'recipient_type' => $this->recipientType,
            'completed_at' => now(),
            'action_url' => $actionUrl,
        ];
    }
}
