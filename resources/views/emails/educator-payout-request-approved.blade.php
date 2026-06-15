<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payout Request Approved</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: #006b7d; color: #fff; padding: 32px 30px; text-align: center; }
        .content { padding: 32px 30px; color: #333; line-height: 1.6; }
        .highlight { background: #f0fafb; border-left: 4px solid #006b7d; padding: 16px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header"><h1 style="margin:0;">Payout Request Approved</h1></div>
    <div class="content">
        <p>Hi {{ $educator->full_name }},</p>
        <p>Your payout request <strong>#{{ $payoutRequest->id }}</strong> has been approved by our team.</p>

        <div class="highlight">
            Funds will be released within approximately <strong>{{ $delayMinutes }} minute(s)</strong>.
            You will receive another email once the transfer is complete.
        </div>

        @if($payoutRequest->admin_notes)
            <p><strong>Admin note:</strong> {{ $payoutRequest->admin_notes }}</p>
        @endif

        <p style="margin-top:24px;"><a href="{{ route('educator.payout-requests.index') }}">Track your request</a></p>
    </div>
</div>
</body>
</html>
