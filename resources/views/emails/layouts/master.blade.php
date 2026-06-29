<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Ed-Cademy')</title>
</head>

<body style="margin:0; padding:0; background-color:#eef2f4; -webkit-font-smoothing:antialiased; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    {{-- Hidden preheader text (shows as preview in inbox) --}}
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; mso-hide:all;">
        @yield('preheader', 'Ed-Cademy — Learn. Grow. Succeed.')
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#eef2f4; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="width:600px; max-width:600px; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 6px 18px rgba(0,74,87,0.12);">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#006b7d; padding:28px 32px; text-align:center;">
                            <h1 style="margin:0; font-size:26px; color:#ffffff; letter-spacing:0.3px;">Ed-Cademy</h1>
                            <p style="margin:6px 0 0; font-size:13px; color:#bfe7ee;">Learn. Grow. Succeed.</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:34px 32px; color:#33474d; font-size:15px; line-height:1.65;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#004a57; padding:22px 32px; text-align:center;">
                            <p style="margin:0; font-size:13px; color:#cfeef3;">
                                You're receiving this email because you have an account with Ed-Cademy.
                            </p>
                            <p style="margin:8px 0 0; font-size:12px; color:#9fd3dc;">
                                <a href="https://www.ed-cademy.com" style="color:#ffffff; text-decoration:none;">www.ed-cademy.com</a>
                            </p>
                            <p style="margin:8px 0 0; font-size:12px; color:#7fbac4;">
                                &copy; {{ date('Y') }} Ed-Cademy. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
