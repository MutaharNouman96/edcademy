<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payout Released</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #006b7d 0%, #0c4b94 100%); padding: 36px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 26px; }
        .content { padding: 36px 30px; color: #333; line-height: 1.6; }
        .total-box { background: #f0fafb; padding: 24px; border-radius: 8px; text-align: center; border-left: 4px solid #006b7d; margin: 20px 0; }
        .total-box .amount { font-size: 28px; font-weight: bold; color: #006b7d; }
        .item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .footer { background: #f8f9fa; padding: 24px; text-align: center; color: #666; font-size: 13px; }
        .cta { display: inline-block; background: #006b7d; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; }
    </style>
</head>
<body>
<div class="container">
    <div class="header"><h1>Payout Released</h1></div>
    <div class="content">
        <p>Hi {{ $educator->full_name }},</p>
        <p>Your payout batch <strong>#{{ $batch->id }}</strong> has been processed successfully.</p>

        <div class="total-box">
            <div>Total released</div>
            <div class="amount">{{ $currency === 'USD' ? '$' : $currency . ' ' }}{{ number_format($totalAmount, 2) }}</div>
            <small>{{ $payments->count() }} payment(s) included</small>
        </div>

        @foreach($payments as $payment)
            <div class="item">
                <span>{{ $payment->course?->title ?? 'Payment #' . $payment->id }}</span>
                <strong>{{ $currency === 'USD' ? '$' : '' }}{{ number_format(\App\Http\Controllers\Educator\PayoutController::payableAmount($payment), 2) }}</strong>
            </div>
        @endforeach

        <p style="text-align:center;margin-top:24px;">
            <a href="{{ route('educator.payouts.index') }}" class="cta">View payout history</a>
        </p>
    </div>
    <div class="footer">Sent to {{ $educator->email }} · Ed-Cademy Finance</div>
</div>
</body>
</html>
