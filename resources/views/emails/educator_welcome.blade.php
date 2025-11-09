<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to Ed-Cademy</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #004a57;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .header {
            background-color: #006b7d;
            padding: 20px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
        }

        .body {
            padding: 30px;
            color: #333333;
        }

        .body h2 {
            color: #006b7d;
            margin-top: 0;
        }

        .body p {
            line-height: 1.6;
            font-size: 15px;
        }

        .highlight {
            background-color: #e0f7fa;
            color: #004a57;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }

        .footer {
            background-color: #004a57;
            color: #e0f7fa;
            text-align: center;
            padding: 15px;
            font-size: 13px;
        }

        a.button {
            display: inline-block;
            background-color: #006b7d;
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 15px;
        }

        a.button:hover {
            background-color: #004a57;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Ed-Cademy!</h1>
        </div>
        <div class="body">
            <h2>Hello {{ $educator_name }},</h2>
            <p>
                Thank you for signing up as an <strong>Educator</strong> on
                <strong>Ed-Cademy</strong> — the global platform for educators to share
                knowledge, host live sessions, and create interactive courses.
            </p>

            <div class="highlight">
                We're currently reviewing your profile and verifying your submitted
                documents. Once verification is complete, you’ll receive a
                confirmation email and gain access to create and publish courses.
            </div>

            <p>
                While you wait, you can explore your educator dashboard, learn about
                our content policies, or prepare your first course outline.
            </p>

            <p style="text-align: center;">
                <a href="{{ $dashboard_link }}" class="button">Go to Dashboard</a>
            </p>

            <p>We’re excited to have you on board!</p>

            <p style="margin-top: 30px;">
                Best regards,<br />
                <strong>The Ed-Cademy Team</strong><br />
                <a href="https://edcademy.com" style="color:#006b7d;">www.edcademy.com</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Ed-Cademy. All rights reserved.
        </div>
    </div>
</body>

</html>
