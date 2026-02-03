<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Review Update</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .status { font-size: 18px; margin-bottom: 20px; color: #fd7e14; font-weight: 600; }
        .reason-box { background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 25px; margin: 20px 0; border-left: 4px solid #fd7e14; }
        .reason-title { font-weight: 600; color: #856404; margin-bottom: 10px; }
        .course-details { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .next-steps { background-color: #f8f9fa; padding: 30px; margin: 30px 0; border-radius: 8px; border-left: 4px solid #007bff; }
        .step { margin-bottom: 15px; display: flex; align-items: flex-start; }
        .step-number { background: #007bff; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #007bff 0%, #6610f2 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .resources { background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Course Review Update</h1>
        </div>

        <div class="content">
            <div class="status">
                Hi {{ $educator->full_name }},
            </div>

            <p>Thank you for submitting your course to Ed-Cademy. After careful review, we need some improvements before we can approve and publish it.</p>

            <div class="course-details">
                <h3 style="margin-top: 0; color: #333;">Course Details:</h3>
                <p><strong>Title:</strong> {{ $course->title }}</p>
                <p><strong>Subject:</strong> {{ $course->subject }}</p>
                <p><strong>Submitted:</strong> {{ $course->created_at->format('M j, Y') }}</p>
            </div>

            @if($reason)
            <div class="reason-box">
                <div class="reason-title">Review Feedback:</div>
                <p>{{ $reason }}</p>
            </div>
            @endif

            <div class="next-steps">
                <h3 style="margin-top: 0; color: #333;">How to Resubmit:</h3>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Address the feedback provided in your course content</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Make necessary improvements and updates</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Resubmit your course for another review</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="cta-button">Edit Course</a>
            </div>

            <div class="resources">
                <h4 style="margin-top: 0; color: #0056b3;">Quality Guidelines:</h4>
                <ul>
                    <li>Clear, comprehensive course content</li>
                    <li>High-quality video/audio materials</li>
                    <li>Well-structured curriculum and learning objectives</li>
                    <li>Accurate course descriptions and prerequisites</li>
                    <li>Engaging and interactive elements</li>
                </ul>
            </div>

            <p>Don't be discouraged! Many successful courses on our platform went through revisions. Our team is here to support you in creating an outstanding learning experience.</p>

            <p>If you have questions about the feedback or need guidance on making improvements, please contact our educator support team.</p>

            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $educator->email }} regarding your course review.
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