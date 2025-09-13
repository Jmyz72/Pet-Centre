<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Service Completed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #d4edda; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .success-badge { background-color: #155724; color: white; padding: 10px 20px; border-radius: 20px; display: inline-block; font-weight: bold; margin-bottom: 15px; }
        .payment-info { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .amount { font-size: 2em; font-weight: bold; color: #155724; margin: 10px 0; }
        .details { background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 0.9em; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-badge">✅ Service Completed</div>
            <h1>Your service has been completed successfully!</h1>
            <p>Hello {{ $booking->customer->name ?? 'Customer' }},</p>
            <p>Great news! Your booking has been completed and payment has been released.</p>
        </div>

        <div class="details">
            <h2>Service Details</h2>
            <p><strong>Type:</strong> {{ ucfirst($booking->booking_type) }}</p>
            <p><strong>Date & Time:</strong> {{ $booking->start_at->format('d M Y, g:i A') }}</p>
            <p><strong>Reference:</strong> {{ $booking->payment_ref }}</p>
        </div>

        <div class="payment-info">
            <h2>💳 Payment Released</h2>
            <div class="amount">RM {{ number_format($transaction->amount, 2) }}</div>
            <p>The merchant has confirmed completion of your service and funds have been released.</p>
        </div>

        <div class="footer">
            <p>Thank you for choosing our services!</p>
            <p>We hope you had a great experience. Feel free to book with us again!</p>
        </div>
    </div>
</body>
</html>