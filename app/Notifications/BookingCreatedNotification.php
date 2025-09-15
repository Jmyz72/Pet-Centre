<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $recipientType // 'customer' or 'merchant'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $bookingType = ucfirst($this->booking->booking_type);
        $releaseCode = $this->booking->meta['release_code'] ?? null;
        $customerName = $this->booking->customer->name ?? 'Customer';
        
        if ($this->recipientType === 'customer') {
            $mail = (new MailMessage)
                ->subject('Booking Confirmation - ' . $bookingType)
                ->greeting('Hello ' . $customerName . '!')
                ->line('Your booking has been confirmed and payment processed successfully.')
                ->line('**Booking Details:**')
                ->line('Type: ' . $bookingType)
                ->line('Date: ' . $this->booking->start_at->format('d M Y, g:i A'))
                ->line('Reference: ' . $this->booking->payment_ref);
                
            if ($releaseCode) {
                $mail->line('**Important: Your Release Code**')
                     ->line('Please provide this code to the merchant to complete your service:')
                     ->line('**' . $releaseCode . '**')
                     ->line('Keep this code safe - you will need it!');
            }
            
            return $mail->line('Thank you for choosing our services!');
        } else {
            // Merchant-facing email
            return (new MailMessage)
                ->subject('New Booking Received - ' . $bookingType)
                ->greeting('Hello!')
                ->line('You have received a new booking.')
                ->line('**Booking Details:**')
                ->line('Customer: ' . $customerName)
                ->line('Type: ' . $bookingType)
                ->line('Date: ' . $this->booking->start_at->format('d M Y, g:i A'))
                ->line('Reference: ' . $this->booking->payment_ref)
                ->action('View Booking', url('/merchant/bookings/' . $this->booking->id))
                ->line('The customer will provide a release code when the service is completed.');
        }
    }

    public function toArray(object $notifiable): array
    {
        $bookingType = ucfirst($this->booking->booking_type);
        $customerName = $this->booking->customer->name ?? 'Customer';
        
        if ($this->recipientType === 'customer') {
            $title = 'Booking Confirmed';
            $message = "Your {$bookingType} booking has been confirmed. Payment processed successfully. Reference: {$this->booking->payment_ref}";
            $actionUrl = url('/bookings/' . $this->booking->id);
        } else {
            $title = 'New Booking Received';
            $message = "New {$bookingType} booking from {$customerName} on {$this->booking->start_at->format('d M Y, g:i A')}";
            $actionUrl = url('/merchant/bookings/' . $this->booking->id);
        }

        return [
            'title' => $title,
            'message' => $message,
            'booking_id' => $this->booking->id,
            'booking_type' => $this->booking->booking_type,
            'customer_name' => $customerName,
            'start_at' => $this->booking->start_at,
            'payment_ref' => $this->booking->payment_ref,
            'recipient_type' => $this->recipientType,
            'release_code' => $this->booking->meta['release_code'] ?? null,
            'action_url' => $actionUrl,
        ];
    }
}
