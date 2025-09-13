<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public WalletTransaction $transaction
    ) {}

    public function envelope(): Envelope
    {
        $bookingType = ucfirst($this->booking->booking_type);
        return new Envelope(
            subject: 'Service Completed - ' . $bookingType,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.booking-completed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
