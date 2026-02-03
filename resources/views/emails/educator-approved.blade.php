<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educator Account Approved</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .congratulations { font-size: 18px; margin-bottom: 20px; color: #28a745; font-weight: 600; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .next-steps { background-color: #f8f9fa; padding: 30px; margin: 30px 0; border-radius: 8px; border-left: 4px solid #28a745; }
        .step { margin-bottom: 15px; display: flex; align-items: flex-start; }
        .step-number { background: #28a745; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #28a745; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Account Approved!</h1>
        </div>

        <div class="content">
            <div class="congratulations">
                Congratulations, {{ $educator->full_name }}!
            </div>

            <p>We're excited to inform you that your educator application has been reviewed and <strong>approved</strong>! Welcome to the Ed-Cademy educator community.</p>

            <p>You can now create and publish courses, conduct live sessions, and start earning by sharing your knowledge with students worldwide.</p>

            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="cta-button">Access Your Dashboard</a>
            </div>

            <div class="next-steps">
                <h3 style="margin-top: 0; color: #333;">Next Steps to Get Started:</h3>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Complete your educator profile with detailed information about your expertise</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Create your first course or set up your availability for live sessions</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Configure your payment methods to receive earnings</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div>Promote your courses and start attracting students</div>
                </div>
            </div>

            <p><strong>Resources available to you:</strong></p>
            <ul>
                <li>Educator dashboard with analytics and insights</li>
                <li>Course creation tools with multimedia support</li>
                <li>Student management and communication tools</li>
                <li>Earnings tracking and payout management</li>
                <li>24/7 support for educators</li>
            </ul>

            <p>If you have any questions about getting started, our educator success team is here to help.</p>

            <p>Welcome aboard!</p>
            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $educator->email }} regarding your educator account approval.
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