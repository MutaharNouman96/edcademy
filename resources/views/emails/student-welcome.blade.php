<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Ed-Cademy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .welcome-message { font-size: 18px; margin-bottom: 20px; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .features { background-color: #f8f9fa; padding: 30px; margin: 30px 0; border-radius: 8px; }
        .feature-item { margin-bottom: 15px; display: flex; align-items: center; }
        .feature-item::before { content: "✓"; color: #28a745; font-weight: bold; margin-right: 10px; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #667eea; text-decoration: none; }
        .social-links { margin-top: 20px; }
        .social-links a { margin: 0 10px; text-decoration: none; color: #667eea; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Ed-Cademy!</h1>
        </div>

        <div class="content">
            <div class="welcome-message">
                Hi {{ $user->full_name }}!
            </div>

            <p>We're thrilled to have you join our learning community! Ed-Cademy is your gateway to quality education with expert educators from around the world.</p>

            <p>Your account has been successfully created and verified. You can now explore thousands of courses, book sessions with top educators, and start your learning journey.</p>

            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="cta-button">Start Learning Now</a>
            </div>

            <div class="features">
                <h3 style="margin-top: 0; color: #333;">What you can do now:</h3>
                <div class="feature-item">Browse and enroll in courses from expert educators</div>
                <div class="feature-item">Book one-on-one sessions with tutors</div>
                <div class="feature-item">Track your learning progress</div>
                <div class="feature-item">Earn certificates upon course completion</div>
                <div class="feature-item">Join our community discussions</div>
            </div>

            <p>If you have any questions or need assistance, our support team is here to help.</p>

            <p>Happy learning!</p>
            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $user->email }} because you recently created an account on Ed-Cademy.
            </p>
            <p>
                <a href="{{ route('web.privacy.policy') }}">Privacy Policy</a> |
                <a href="{{ route('web.terms.and.conditions') }}">Terms of Service</a> |
                <a href="{{ route('web.contact.us') }}">Contact Support</a>
            </p>
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">LinkedIn</a> |
                <a href="#">Instagram</a>
            </div>
        </div>
    </div>
</body>
</html>