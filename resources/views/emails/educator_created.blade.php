<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Educator Account</title>
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

        .credentials {
            background-color: #e0f7fa;
            color: #004a57;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .credentials p {
            margin: 6px 0;
            font-size: 15px;
        }

        .credentials strong {
            display: inline-block;
            min-width: 100px;
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
            <h2>Hello {{ $educatorName }},</h2>
            <p>
                An educator account has been created for you on <strong>Ed-Cademy</strong> by our administration team.
                Below are your login credentials:
            </p>

            <div class="credentials">
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Password:</strong> {{ $plainPassword }}</p>
            </div>

            <p>
                We strongly recommend that you change your password after your first login for security purposes.
            </p>

            <p style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
            </p>

            <p>If you have any questions, feel free to reach out to our support team.</p>

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
