<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Login to Your Account</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #17a2b8 0%, #6c757d 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .alert-message { font-size: 18px; margin-bottom: 20px; color: #17a2b8; font-weight: 600; }
        .security-box { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 25px; margin: 20px 0; }
        .security-title { font-weight: 600; color: #17a2b8; margin-bottom: 15px; font-size: 16px; }
        .login-details { background-color: #e9ecef; padding: 20px; border-radius: 6px; margin: 15px 0; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #dee2e6; }
        .detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .detail-label { font-weight: 600; color: #666; }
        .detail-value { color: #333; text-align: right; }
        .warning-box { background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0; border-left: 4px solid #ffc107; }
        .warning-title { font-weight: 600; color: #856404; margin-bottom: 10px; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #17a2b8 0%, #6c757d 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #17a2b8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Account Security Alert</h1>
        </div>

        <div class="content">
            <div class="alert-message">
                Hi {{ $user->full_name }}!
            </div>

            <p>We detected a new login to your Ed-Cademy account. For your security, we're sending you this notification.</p>

            <div class="security-box">
                <div class="security-title">📍 Login Details:</div>

                <div class="login-details">
                    <div class="detail-row">
                        <span class="detail-label">Time:</span>
                        <span class="detail-value">{{ $loginTime }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">IP Address:</span>
                        <span class="detail-value">{{ $ipAddress ?: 'Unknown' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Device/Browser:</span>
                        <span class="detail-value">{{ $userAgent ?: 'Unknown' }}</span>
                    </div>
                    @if($location)
                    <div class="detail-row">
                        <span class="detail-label">Location:</span>
                        <span class="detail-value">{{ $location }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="warning-box">
                <div class="warning-title">⚠️ Was this you?</div>
                <p>If you recognize this login, no action is needed. This is just a security notification.</p>
                <p><strong>If this wasn't you:</strong> Please change your password immediately and contact our support team.</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $securityUrl }}" class="cta-button">Review Security Settings</a>
            </div>

            <p><strong>Security Best Practices:</strong></p>
            <ul>
                <li>Use a strong, unique password</li>
                <li>Enable two-factor authentication when available</li>
                <li>Log out from shared devices</li>
                <li>Monitor your account activity regularly</li>
                <li>Contact support if you notice suspicious activity</li>
            </ul>

            <p>If you have any concerns about this login or your account security, please contact our support team immediately.</p>

            <p>Thank you for helping us keep your account secure!</p>

            <p><strong>The Ed-Cademy Security Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This security notification was sent to {{ $user->email }}.
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