<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .release-code { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .release-code-number { font-size: 2em; font-weight: bold; color: #856404; font-family: monospace; letter-spacing: 4px; }
        .details { background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 0.9em; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmation</h1>
            <p>Hello {{ $booking->customer->name ?? 'Customer' }},</p>
            <p>Your booking has been confirmed and payment processed successfully!</p>
        </div>

        <div class="details">
            <h2>Booking Details</h2>
            <p><strong>Type:</strong> {{ ucfirst($booking->booking_type) }}</p>
            <p><strong>Date & Time:</strong> {{ $booking->start_at->format('d M Y, g:i A') }}</p>
            <p><strong>Reference:</strong> {{ $booking->payment_ref }}</p>
            <p><strong>Amount:</strong> RM {{ number_format($booking->price_amount, 2) }}</p>
        </div>

        @if(isset($booking->meta['release_code']))
        <div class="release-code">
            <h2>🔑 Important: Your Release Code</h2>
            <p>Please provide this code to the merchant when your service is completed:</p>
            <div class="release-code-number">{{ $booking->meta['release_code'] }}</div>
            <p><strong>Keep this code safe - you will need it to complete your service!</strong></p>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for choosing our services!</p>
            <p>If you have any questions, please contact us.</p>
        </div>
    </div>
</body>
</html>