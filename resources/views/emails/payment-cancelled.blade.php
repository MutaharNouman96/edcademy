<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .status { font-size: 18px; margin-bottom: 20px; color: #fd7e14; font-weight: 600; }
        .order-details { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #fd7e14; }
        .retry-section { background-color: #f0f8ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 25px; margin: 30px 0; }
        .retry-title { font-weight: 600; color: #0056b3; margin-bottom: 15px; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #007bff 0%, #6610f2 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Cancelled</h1>
        </div>

        <div class="content">
            <div class="status">
                Hi {{ $user->full_name }},
            </div>

            <p>Your payment for Order #{{ $order->id }} has been cancelled. No charges have been made to your payment method.</p>

            <div class="order-details">
                <h3 style="margin-top: 0; color: #333;">Order Details:</h3>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Items:</strong> {{ $order->items->count() }} item(s)</p>
                <p><strong>Total Amount:</strong> ${{ number_format($order->items->sum('total'), 2) }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
                <p><strong>Status:</strong> <span style="color: #dc3545; font-weight: 600;">Cancelled</span></p>
            </div>

            <div class="retry-section">
                <div class="retry-title">Want to complete your purchase?</div>
                <p>You can retry your payment at any time. Your cart items are saved and ready for checkout.</p>

                <div style="text-align: center;">
                    <a href="{{ route('web.cart.checkout') }}" class="cta-button">Retry Payment</a>
                </div>
            </div>

            <p><strong>Why was the payment cancelled?</strong></p>
            <ul>
                <li>You clicked "Cancel" during the payment process</li>
                <li>The payment window expired</li>
                <li>There was an issue with the payment information</li>
            </ul>

            <p>If you encountered any issues during payment or need assistance, please contact our support team.</p>

            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $user->email }} regarding your cancelled payment.
            </p>
            <p>
                <a href="{{ route('web.privacy.policy') }}">Privacy Policy</a> |
                <a href="{{ route('web.terms.and.conditions') }}">Terms of Service</a> |
                <a href="{{ route('web.contact.us') }}">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>