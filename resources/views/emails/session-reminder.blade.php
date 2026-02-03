<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Reminder - Tomorrow</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .reminder-message { font-size: 18px; margin-bottom: 20px; color: #ffc107; font-weight: 600; }
        .countdown { background: linear-gradient(135deg, #fff8e1 0%, #fff3c4 100%); padding: 25px; border-radius: 8px; margin: 20px 0; text-align: center; border: 2px solid #ffc107; }
        .countdown-number { font-size: 36px; font-weight: bold; color: #f57c00; display: block; }
        .countdown-text { font-size: 16px; color: #e65100; margin-top: 5px; }
        .session-details { background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107; }
        .session-details h3 { margin-top: 0; color: #f57c00; }
        .detail-row { display: flex; margin-bottom: 10px; }
        .detail-label { font-weight: 600; width: 120px; color: #666; }
        .detail-value { color: #333; }
        .checklist { background-color: #f8f9fa; padding: 25px; margin: 30px 0; border-radius: 8px; }
        .checklist h4 { margin-top: 0; color: #333; }
        .check-item { margin-bottom: 10px; display: flex; align-items: flex-start; }
        .check-item::before { content: "☐"; color: #28a745; font-weight: bold; margin-right: 10px; }
        .meeting-link { background-color: #e8f5e8; border: 1px solid #c8e6c9; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .meeting-link strong { color: #2e7d32; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #f57c00; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Session Reminder</h1>
        </div>

        <div class="content">
            <div class="reminder-message">
                @if($isForStudent)
                    Hi {{ $student->full_name }}!
                @else
                    Hi {{ $educator->full_name }}!
                @endif
            </div>

            <p>This is a friendly reminder that you have a session scheduled for <strong>tomorrow</strong>.</p>

            <div class="countdown">
                <span class="countdown-number">24</span>
                <span class="countdown-text">HOURS TO GO</span>
            </div>

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
                    <span class="detail-value">{{ $session->scheduled_at->format('l, F j, Y \a\t g:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value">{{ $session->duration ?? 60 }} minutes</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Platform:</span>
                    <span class="detail-value">{{ $session->platform ?? 'Zoom' }}</span>
                </div>
            </div>

            @if($session->meeting_link)
            <div class="meeting-link">
                <strong>Meeting Link:</strong><br>
                <a href="{{ $session->meeting_link }}" style="color: #2e7d32; word-break: break-all;">{{ $session->meeting_link }}</a>
                <br><small style="color: #666;">Click to join the session directly</small>
            </div>
            @endif

            <div class="checklist">
                <h4>Pre-Session Checklist:</h4>
                <div class="check-item">Test your internet connection and speed</div>
                <div class="check-item">Check your camera and microphone</div>
                <div class="check-item">Ensure your device is charged or plugged in</div>
                <div class="check-item">Find a quiet, well-lit space for the session</div>
                <div class="check-item">Have paper, pen, and any materials ready</div>
                <div class="check-item">Close unnecessary applications and browser tabs</div>
                @if($isForStudent)
                    <div class="check-item">Prepare questions or topics you want to discuss</div>
                @else
                    <div class="check-item">Review the student's profile and learning goals</div>
                @endif
            </div>

            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="cta-button">View Session Details</a>
            </div>

            <p><strong>Need to reschedule?</strong> Contact us at least 12 hours before the session. For immediate assistance, reach out to our support team.</p>

            <p>We're excited for your session tomorrow! Let's make it productive and engaging.</p>

            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This is an automated reminder for your upcoming session.
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