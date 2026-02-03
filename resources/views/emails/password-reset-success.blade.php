<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed Successfully</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .success-message { font-size: 18px; margin-bottom: 20px; color: #28a745; font-weight: 600; }
        .confirmation-box { background: linear-gradient(135deg, #f8fff8 0%, #e8f5e8 100%); border: 1px solid #c8e6c9; border-radius: 8px; padding: 25px; margin: 20px 0; border-left: 4px solid #28a745; }
        .confirmation-title { font-weight: 600; color: #2e7d32; margin-bottom: 15px; }
        .reset-details { background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin: 15px 0; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #dee2e6; }
        .detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .detail-label { font-weight: 600; color: #666; }
        .detail-value { color: #333; text-align: right; }
        .security-tips { background-color: #f8f9fa; padding: 25px; margin: 30px 0; border-radius: 8px; border-left: 4px solid #17a2b8; }
        .tips-title { font-weight: 600; color: #17a2b8; margin-bottom: 15px; }
        .tip { margin-bottom: 10px; display: flex; align-items: flex-start; }
        .tip::before { content: "🔒"; margin-right: 10px; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #28a745; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Password Changed Successfully</h1>
        </div>

        <div class="content">
            <div class="success-message">
                Hi {{ $user->full_name }}!
            </div>

            <p>Your password has been successfully changed. Your account security has been updated.</p>

            <div class="confirmation-box">
                <div class="confirmation-title">🔐 Password Change Confirmed</div>
                <p>Your password was successfully updated. You can now log in with your new password.</p>

                <div class="reset-details">
                    <div class="detail-row">
                        <span class="detail-label">Changed:</span>
                        <span class="detail-value">{{ $resetTime }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Account:</span>
                        <span class="detail-value">{{ $user->email }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value" style="color: #28a745; font-weight: 600;">Successful</span>
                    </div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="cta-button">Login Now</a>
            </div>

            <div class="security-tips">
                <div class="tips-title">Security Recommendations:</div>
                <div class="tip">Use a strong password with at least 8 characters, including uppercase, lowercase, numbers, and symbols</div>
                <div class="tip">Don't reuse passwords across different websites</div>
                <div class="tip">Consider using a password manager to generate and store secure passwords</div>
                <div class="tip">Enable two-factor authentication if available for additional security</div>
                <div class="tip">Regularly monitor your account activity and login notifications</div>
            </div>

            <p><strong>If you didn't make this change:</strong> Please contact our support team immediately. We recommend changing your password again and reviewing your account security settings.</p>

            <p>Your security is our top priority. Thank you for taking steps to keep your account safe!</p>

            <p><strong>The Ed-Cademy Security Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This confirmation was sent to {{ $user->email }}.
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