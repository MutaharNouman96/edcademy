<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Message from Ed-Cademy</title>
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
            font-size: 24px;
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

        .note-content {
            background-color: #f0f4f8;
            color: #1a202c;
            padding: 20px;
            border-left: 4px solid #006b7d;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 15px;
            line-height: 1.7;
            white-space: pre-line;
        }

        .footer {
            background-color: #004a57;
            color: #e0f7fa;
            text-align: center;
            padding: 15px;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Message from Ed-Cademy</h1>
        </div>
        <div class="body">
            <h2>Hello {{ $educatorName }},</h2>
            <p>You have received the following message from the Ed-Cademy team:</p>

            <div class="note-content">{!! nl2br(e($noteBody)) !!}</div>

            <p>If you have any questions, feel free to reply to this email or contact our support team.</p>

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
