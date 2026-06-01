<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings Released</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .success-message { font-size: 20px; margin-bottom: 20px; color: #28a745; font-weight: 600; text-align: center; }
        .total-box { background: linear-gradient(135deg, #f8fff8 0%, #e8f5e8 100%); padding: 25px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745; text-align: center; }
        .total-box .label { color: #666; font-weight: 600; }
        .total-box .amount { font-size: 28px; font-weight: bold; color: #28a745; margin-top: 6px; }
        .payouts { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .payout-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e9ecef; }
        .payout-item:last-child { border-bottom: none; }
        .payout-desc { flex: 1; color: #666; padding-right: 12px; }
        .payout-amount { font-weight: 600; color: #28a745; white-space: nowrap; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #28a745; text-decoration: none; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Earnings Released!</h1>
        </div>

        <div class="content">
            <div class="success-message">
                Great news, {{ $educator->full_name }}!
            </div>

            <p>Your earnings from recent course sales have been released and are on their way to your account.</p>

            <div class="total-box">
                <div class="label">Total Released</div>
                <div class="amount">${{ number_format($totalAmount, 2) }}</div>
            </div>

            <div class="payouts">
                <h4 style="margin-top: 0; color: #333;">Payout Breakdown:</h4>
                @foreach($payouts as $payout)
                <div class="payout-item">
                    <span class="payout-desc">
                        {{ $payout->description ?? 'Course sale payout' }}
                        <br><small style="color:#999;">Payout #{{ $payout->id }} &middot; {{ optional($payout->processed_at)->format('M j, Y g:i A') }}</small>
                    </span>
                    <span class="payout-amount">${{ number_format($payout->amount, 2) }}</span>
                </div>
                @endforeach
            </div>

            <p>The funds should appear in your designated payment method within 3-5 business days.</p>

            <div style="text-align: center;">
                <a href="{{ route('educator.payouts.index') }}" class="cta-button">View Payout History</a>
            </div>

            <p>Thank you for being part of the Ed-Cademy community!</p>

            <p><strong>The Ed-Cademy Finance Team</strong></p>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $educator->email }} regarding your payout.</p>
            <p>
                <a href="{{ route('web.privacy.policy') }}">Privacy Policy</a> |
                <a href="{{ route('web.terms.and.conditions') }}">Terms of Service</a> |
                <a href="{{ route('web.contact.us') }}">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>
