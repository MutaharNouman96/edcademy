<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educator Application Update</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .status { font-size: 18px; margin-bottom: 20px; color: #dc3545; font-weight: 600; }
        .reason-box { background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0; border-left: 4px solid #fd7e14; }
        .reason-title { font-weight: 600; color: #856404; margin-bottom: 10px; }
        .next-steps { background-color: #f8f9fa; padding: 30px; margin: 30px 0; border-radius: 8px; border-left: 4px solid #007bff; }
        .step { margin-bottom: 15px; display: flex; align-items: flex-start; }
        .step-number { background: #007bff; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #007bff 0%, #6610f2 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Educator Application Update</h1>
        </div>

        <div class="content">
            <div class="status">
                Hi {{ $educator->full_name }},
            </div>

            <p>Thank you for your interest in becoming an educator on Ed-Cademy. After careful review of your application, we regret to inform you that it does not meet our current requirements.</p>

            @if($reason)
            <div class="reason-box">
                <div class="reason-title">Review Feedback:</div>
                <p>{{ $reason }}</p>
            </div>
            @endif

            <p>Don't be discouraged! Many successful educators on our platform started with applications that needed some adjustments.</p>

            <div class="next-steps">
                <h3 style="margin-top: 0; color: #333;">How to Reapply:</h3>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Review the feedback provided above and address the concerns</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Strengthen your application with additional qualifications or experience</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Reapply with the improved application</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $retryUrl }}" class="cta-button">Apply Again</a>
            </div>

            <p><strong>Educator Requirements Reminder:</strong></p>
            <ul>
                <li>Relevant teaching experience or subject matter expertise</li>
                <li>Clear identification documents</li>
                <li>Educational qualifications or certifications</li>
                <li>Professional teaching approach and communication skills</li>
            </ul>

            <p>If you have questions about this decision or need guidance on improving your application, please don't hesitate to contact our support team.</p>

            <p>We appreciate your interest in joining our educator community and wish you the best in your teaching journey.</p>

            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $educator->email }} regarding your educator application.
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