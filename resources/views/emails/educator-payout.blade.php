<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educator Payout Notification</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .success-message { font-size: 20px; margin-bottom: 20px; color: #28a745; font-weight: 600; text-align: center; }
        .error-message { font-size: 20px; margin-bottom: 20px; color: #dc3545; font-weight: 600; text-align: center; }
        .payout-details { background: linear-gradient(135deg, #f8fff8 0%, #e8f5e8 100%); padding: 25px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745; }
        .payout-details.error { background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%); border-left-color: #dc3545; }
        .payout-details h3 { margin-top: 0; color: #28a745; }
        .payout-details.error h3 { color: #dc3545; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #dee2e6; }
        .detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .detail-label { font-weight: 600; color: #666; }
        .detail-value { color: #333; text-align: right; }
        .earnings-breakdown { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .earning-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9ecef; }
        .earning-item:last-child { border-bottom: none; }
        .earning-description { flex: 1; color: #666; }
        .earning-amount { font-weight: 600; color: #28a745; }
        .next-steps { background-color: #f8f9fa; padding: 25px; margin: 30px 0; border-radius: 8px; border-left: 4px solid #007bff; }
        .steps-title { font-weight: 600; color: #007bff; margin-bottom: 15px; }
        .step { margin-bottom: 10px; display: flex; align-items: flex-start; }
        .step-number { background: #007bff; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .cta-button.error { background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #28a745; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>@if($isSuccessful)💰 Payment Sent!@else❌ Payment Failed@endif</h1>
        </div>

        <div class="content">
            @if($isSuccessful)
                <div class="success-message">
                    Great news, {{ $educator->full_name }}!
                </div>

                <p>Your payout has been successfully processed and sent to your account. The funds should appear in your designated payment method within 3-5 business days.</p>
            @else
                <div class="error-message">
                    Payment Processing Issue
                </div>

                <p>Unfortunately, there was an issue processing your payout. Our team has been notified and will resolve this as soon as possible.</p>
            @endif

            <div class="payout-details @if(!$isSuccessful)error@endif">
                <h3>@if($isSuccessful)Payment Details@elsePayment Attempt Details@endif</h3>
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value" style="font-size: 18px; font-weight: bold; color: #28a745;">${{ number_format($payout->amount, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payout ID:</span>
                    <span class="detail-value">#{{ $payout->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Processed Date:</span>
                    <span class="detail-value">{{ $payout->processed_at ? $payout->processed_at->format('M j, Y \a\t g:i A') : 'Pending' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: @if($isSuccessful)#28a745; font-weight: 600@else#dc3545; font-weight: 600@endif">
                        {{ ucfirst($payout->status) }}
                    </span>
                </div>
                @if($payout->description)
                <div class="detail-row">
                    <span class="detail-label">Description:</span>
                    <span class="detail-value">{{ $payout->description }}</span>
                </div>
                @endif
            </div>

            @if($earnings && $earnings->count() > 0)
            <div class="earnings-breakdown">
                <h4 style="margin-top: 0; color: #333;">Earnings Breakdown:</h4>
                @foreach($earnings as $earning)
                <div class="earning-item">
                    <span class="earning-description">
                        {{ $earning->description }}
                        @if($earning->source_type === 'course')
                            (Course: {{ $earning->course->title ?? 'N/A' }})
                        @elseif($earning->source_type === 'session')
                            (Session: {{ $earning->session->subject ?? 'N/A' }})
                        @endif
                    </span>
                    <span class="earning-amount">${{ number_format($earning->net_amount, 2) }}</span>
                </div>
                @endforeach
                <hr style="border: none; border-top: 2px solid #dee2e6; margin: 15px 0;">
                <div class="earning-item">
                    <span class="earning-description" style="font-weight: 600;">Total Paid</span>
                    <span class="earning-amount" style="font-size: 16px;">${{ number_format($payout->amount, 2) }}</span>
                </div>
            </div>
            @endif

            @if($isSuccessful)
            <div class="next-steps">
                <div class="steps-title">What's Next:</div>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Check your bank/paypal account in 3-5 business days</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Continue creating great content and sessions</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Monitor your earnings in the dashboard</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('educator.payouts.index') }}" class="cta-button">View Payout History</a>
            </div>
            @else
            <div class="next-steps">
                <div class="steps-title">What We're Doing:</div>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Investigating the payment processing issue</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Contacting you with updates within 24 hours</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Resolving the issue and reprocessing the payment</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="mailto:support@ed-cademy.com" class="cta-button error">Contact Support</a>
            </div>
            @endif

            <p><strong>Questions about your payout?</strong> Our finance team is here to help. You can view all your payout history and earnings in your educator dashboard.</p>

            <p>Thank you for being part of the Ed-Cademy community!</p>

            <p><strong>The Ed-Cademy Finance Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $educator->email }} regarding your payout.
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