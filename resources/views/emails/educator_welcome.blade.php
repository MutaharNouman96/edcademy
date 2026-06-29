@extends('emails.layouts.master')

@section('title', 'Welcome to Ed-Cademy')

@section('preheader', 'Thanks for joining Ed-Cademy as an educator — your profile is under review.')

@section('content')
    <h2 style="margin:0 0 16px; color:#006b7d; font-size:22px;">Welcome aboard, {{ $educator_name }}! 🎉</h2>

    <p style="margin:0 0 16px;">
        Thank you for signing up as an <strong>Educator</strong> on
        <strong>Ed-Cademy</strong> — the global platform where educators share knowledge,
        host live sessions, and build engaging courses for learners around the world.
    </p>

    <div style="background-color:#e0f7fa; border-left:4px solid #006b7d; border-radius:8px; padding:16px 18px; margin:0 0 22px;">
        <p style="margin:0; color:#004a57; font-weight:600;">
            Your application is in review.
        </p>
        <p style="margin:8px 0 0; color:#33474d; font-size:14px;">
            Our team is verifying your submitted documents. As soon as your profile is
            approved, we'll email you and unlock course creation and payouts.
        </p>
    </div>

    <p style="margin:0 0 12px; font-weight:600; color:#33474d;">What happens next?</p>
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:0 0 24px;">
        <tr>
            <td style="padding:6px 0; font-size:14px; color:#33474d;">✅ &nbsp; Verify your email address to activate your account</td>
        </tr>
        <tr>
            <td style="padding:6px 0; font-size:14px; color:#33474d;">🔍 &nbsp; We review your CV and supporting documents</td>
        </tr>
        <tr>
            <td style="padding:6px 0; font-size:14px; color:#33474d;">💳 &nbsp; Set up your payout details to start earning</td>
        </tr>
        <tr>
            <td style="padding:6px 0; font-size:14px; color:#33474d;">🚀 &nbsp; Create your first course and go live</td>
        </tr>
    </table>

    <p style="text-align:center; margin:0 0 26px;">
        <a href="{{ $dashboard_link }}"
            style="display:inline-block; background-color:#006b7d; color:#ffffff; padding:13px 30px; text-decoration:none; border-radius:8px; font-weight:600; font-size:15px;">
            Go to Your Dashboard
        </a>
    </p>

    <p style="margin:0 0 4px;">We're thrilled to have you with us.</p>
    <p style="margin:0;">
        Warm regards,<br />
        <strong>The Ed-Cademy Team</strong>
    </p>
@endsection
