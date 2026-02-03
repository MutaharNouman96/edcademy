<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Submitted Successfully</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .success-message { font-size: 18px; margin-bottom: 20px; color: #17a2b8; font-weight: 600; }
        .course-details { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #17a2b8; }
        .timeline { background-color: #e9ecef; padding: 30px; margin: 30px 0; border-radius: 8px; }
        .timeline-step { display: flex; align-items: center; margin-bottom: 15px; }
        .timeline-icon { width: 40px; height: 40px; background: #17a2b8; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-right: 15px; }
        .timeline-content { flex: 1; }
        .timeline-content h4 { margin: 0 0 5px 0; color: #333; }
        .timeline-content p { margin: 0; color: #666; font-size: 14px; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #17a2b8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Course Submitted Successfully!</h1>
        </div>

        <div class="content">
            <div class="success-message">
                Hi {{ $educator->full_name }},
            </div>

            <p>Great news! Your course "<strong>{{ $course->title }}</strong>" has been successfully submitted to Ed-Cademy and is now under review.</p>

            <div class="course-details">
                <h3 style="margin-top: 0; color: #333;">Course Details:</h3>
                <p><strong>Title:</strong> {{ $course->title }}</p>
                <p><strong>Subject:</strong> {{ $course->subject }}</p>
                <p><strong>Level:</strong> {{ $course->level }}</p>
                <p><strong>Price:</strong> ${{ number_format($course->price, 2) }}</p>
                <p><strong>Submitted:</strong> {{ $course->created_at->format('M j, Y \a\t g:i A') }}</p>
            </div>

            <div class="timeline">
                <h3 style="margin-top: 0; color: #333; text-align: center;">What Happens Next?</h3>

                <div class="timeline-step">
                    <div class="timeline-icon">📝</div>
                    <div class="timeline-content">
                        <h4>Review Process (1-3 business days)</h4>
                        <p>Our quality team reviews your course content, structure, and materials</p>
                    </div>
                </div>

                <div class="timeline-step">
                    <div class="timeline-icon">✅</div>
                    <div class="timeline-content">
                        <h4>Approval Decision</h4>
                        <p>You'll receive an email with approval or feedback for improvements</p>
                    </div>
                </div>

                <div class="timeline-step">
                    <div class="timeline-icon">🚀</div>
                    <div class="timeline-content">
                        <h4>Course Goes Live</h4>
                        <p>Once approved, your course becomes available to students worldwide</p>
                    </div>
                </div>

                <div class="timeline-step">
                    <div class="timeline-icon">💰</div>
                    <div class="timeline-content">
                        <h4>Start Earning</h4>
                        <p>Begin earning from enrollments and build your reputation</p>
                    </div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="cta-button">View in Dashboard</a>
            </div>

            <p><strong>While you wait:</strong></p>
            <ul>
                <li>Prepare additional materials or updates if needed</li>
                <li>Set up your availability for live sessions</li>
                <li>Configure your payout methods for earnings</li>
                <li>Explore our educator resources and best practices</li>
            </ul>

            <p>You'll receive email updates throughout the review process. If you have any questions, our support team is here to help.</p>

            <p>Thank you for choosing Ed-Cademy as your teaching platform!</p>

            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $educator->email }} regarding your course submission.
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