<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Educator Signup</title>
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
        font-size: 22px;
      }
      .body {
        padding: 25px;
        color: #333333;
        line-height: 1.6;
      }
      .body p {
        margin-bottom: 12px;
      }
      .details {
        background-color: #e0f7fa;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        color: #004a57;
      }
      .footer {
        background-color: #004a57;
        color: #e0f7fa;
        text-align: center;
        padding: 12px;
        font-size: 13px;
      }
      a.button {
        display: inline-block;
        background-color: #006b7d;
        color: #fff !important;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
      }
      a.button:hover {
        background-color: #004a57;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>New Educator Signup — Verification Needed</h1>
      </div>
      <div class="body">
        <p>Hello Admin,</p>
        <p>
          A new educator has just registered on <strong>Ed-Cademy</strong> and
          requires document verification.
        </p>

        <div class="details">
          <p><strong>Name:</strong> {{ $educator->name }}</p>
          <p><strong>Email:</strong> {{ $educator->email }}</p>
          <p><strong>Joined:</strong> {{ $educator->created_at->format('M d, Y h:i A') }}</p>
        </div>

        <p>
          Please review the submitted documents and verify the account from the
          admin panel.
        </p>

        <p style="text-align: center;">
          <a href="{{ route('admin.educators.pending') }}" class="button">Review Educators</a>
        </p>

        <p>Thanks,<br />The Ed-Cademy System</p>
      </div>
      <div class="footer">
        &copy; {{ date('Y') }} Ed-Cademy. Internal notification only.
      </div>
    </div>
  </body>
</html>
