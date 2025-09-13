<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking
    ) {}

    public function envelope(): Envelope
    {
        $bookingType = ucfirst($this->booking->booking_type);
        return new Envelope(
            subject: 'Booking Confirmation - ' . $bookingType,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.booking-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
