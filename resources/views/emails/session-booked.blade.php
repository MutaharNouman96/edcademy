<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Booked Successfully</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .success-message { font-size: 18px; margin-bottom: 20px; color: #6f42c1; font-weight: 600; }
        .session-details { background: linear-gradient(135deg, #f8f9ff 0%, #fff5f8 100%); padding: 25px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #6f42c1; }
        .session-details h3 { margin-top: 0; color: #6f42c1; }
        .detail-row { display: flex; margin-bottom: 10px; }
        .detail-label { font-weight: 600; width: 120px; color: #666; }
        .detail-value { color: #333; }
        .important-info { background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0; border-left: 4px solid #ffc107; }
        .important-title { font-weight: 600; color: #856404; margin-bottom: 10px; }
        .preparation-tips { background-color: #f8f9fa; padding: 25px; margin: 30px 0; border-radius: 8px; }
        .tip { margin-bottom: 10px; display: flex; align-items: flex-start; }
        .tip::before { content: "💡"; margin-right: 10px; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #6f42c1; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📅 Session Booked Successfully!</h1>
        </div>

        <div class="content">
            <div class="success-message">
                @if($isForStudent)
                    Hi {{ $student->full_name }}!
                @else
                    Hi {{ $educator->full_name }}!
                @endif
            </div>

            @if($isForStudent)
                <p>Great news! Your session with <strong>{{ $educator->full_name }}</strong> has been successfully booked. We're excited for your learning session!</p>
            @else
                <p>Awesome! You have a new session booked with <strong>{{ $student->full_name }}</strong>. Get ready to share your knowledge!</p>
            @endif

            <div class="session-details">
                <h3>Session Details:</h3>
                <div class="detail-row">
                    <span class="detail-label">@if($isForStudent)Educator:@elseStudent:@endif</span>
                    <span class="detail-value">@if($isForStudent){{ $educator->full_name }}@else{{ $student->full_name }}@endif</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Subject:</span>
                    <span class="detail-value">{{ $session->subject ?? 'General Tutoring' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value">{{ $session->scheduled_at->format('l, F j, Y \a\t g:i A') }} ({{ $session->scheduled_at->getTimezone()->getName() }})</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value">{{ $session->duration ?? 60 }} minutes</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Platform:</span>
                    <span class="detail-value">{{ $session->platform ?? 'Zoom' }}</span>
                </div>
                @if($session->meeting_link)
                <div class="detail-row">
                    <span class="detail-label">Meeting Link:</span>
                    <span class="detail-value"><a href="{{ $session->meeting_link }}" style="color: #6f42c1;">{{ $session->meeting_link }}</a></span>
                </div>
                @endif
            </div>

            <div class="important-info">
                <div class="important-title">⚠️ Important Information:</div>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Please join the session 5 minutes early to test your connection</li>
                    <li>Have a stable internet connection and working camera/microphone</li>
                    <li>Prepare any materials or questions you want to discuss</li>
                    <li>Both parties will receive reminders 24 hours before the session</li>
                </ul>
            </div>

            <div class="preparation-tips">
                <h4 style="margin-top: 0; color: #333;">Preparation Tips:</h4>
                @if($isForStudent)
                    <div class="tip">Review the course materials or topics you want to discuss</div>
                    <div class="tip">Prepare specific questions or areas where you need help</div>
                    <div class="tip">Ensure your learning environment is quiet and distraction-free</div>
                    <div class="tip">Have paper and pen ready for note-taking</div>
                @else
                    <div class="tip">Review the student's profile and learning goals</div>
                    <div class="tip">Prepare relevant materials and examples</div>
                    <div class="tip">Have your teaching plan ready</div>
                    <div class="tip">Test your meeting link and audio/video equipment</div>
                @endif
            </div>

            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="cta-button">View in Dashboard</a>
            </div>

            <p><strong>Need to make changes?</strong> You can reschedule or cancel up to 24 hours before the session. Contact support for assistance.</p>

            <p>We're looking forward to a productive and engaging session!</p>

            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent regarding your booked session.
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