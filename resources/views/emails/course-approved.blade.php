<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Approved - Now Live!</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .celebration { font-size: 20px; margin-bottom: 20px; color: #28a745; font-weight: 600; text-align: center; }
        .course-details { background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745; }
        .course-details h3 { margin-top: 0; color: #28a745; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin: 30px 0; }
        .stat-box { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 24px; font-weight: bold; color: #28a745; display: block; }
        .stat-label { font-size: 14px; color: #666; margin-top: 5px; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .next-steps { background-color: #f0f8ff; padding: 30px; margin: 30px 0; border-radius: 8px; border-left: 4px solid #007bff; }
        .step { margin-bottom: 15px; display: flex; align-items: flex-start; }
        .step-number { background: #007bff; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #28a745; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Course Approved & Live!</h1>
        </div>

        <div class="content">
            <div class="celebration">
                Congratulations, {{ $educator->full_name }}!
            </div>

            <p>Your course "<strong>{{ $course->title }}</strong>" has been reviewed and <strong>approved</strong>! It's now live on Ed-Cademy and available for students to enroll.</p>

            <div class="course-details">
                <h3>Course Information:</h3>
                <p><strong>Title:</strong> {{ $course->title }}</p>
                <p><strong>Subject:</strong> {{ $course->subject }}</p>
                <p><strong>Level:</strong> {{ $course->level }}</p>
                <p><strong>Price:</strong> ${{ number_format($course->price, 2) }}</p>
                <p><strong>Approved:</strong> {{ now()->format('M j, Y \a\t g:i A') }}</p>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-number">{{ $course->lessons->count() }}</span>
                    <span class="stat-label">Lessons Created</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $course->sections->count() }}</span>
                    <span class="stat-label">Sections</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $courseUrl }}" class="cta-button">View Your Course</a>
            </div>

            <div class="next-steps">
                <h3 style="margin-top: 0; color: #333;">Maximize Your Course Success:</h3>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Share your course on social media and professional networks</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Monitor student engagement and respond to questions promptly</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Check your earnings dashboard regularly</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div>Consider creating complementary courses or sessions</div>
                </div>
            </div>

            <p><strong>Earnings Information:</strong></p>
            <ul>
                <li>You earn {{ 100 - (setting('platform_commission', 20) ?? 20) }}% of each enrollment</li>
                <li>Payouts are processed monthly for earnings over $50</li>
                <li>Track all earnings and payouts in your dashboard</li>
            </ul>

            <p>Welcome to the ranks of published educators on Ed-Cademy! We're excited to see your course succeed and help students learn.</p>

            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $educator->email }} regarding your course approval.
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