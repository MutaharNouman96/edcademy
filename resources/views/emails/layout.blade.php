<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Invoice</title>
</head>

<body style="font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; background:#f6f8f9; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden;">
        <!-- Header -->
        <tr>
            <td style="background:#00838f; padding:20px 30px; color:#ffffff;">
                <table width="100%">
                    <tr>
                        <td align="left">
                            <h1 style="margin:0; font-size:22px;">Ed-Cademy</h1>
                            <p style="margin:4px 0 0; font-size:13px; color:#ccecee;"> Learn. Grow. Succeed. </p>
                        </td>
                        <td align="right">
                            <p style="margin:0; font-size:14px;"> <strong>Invoice #{{ $order->id }}</strong><br>
                                <span style="font-size:12px;"> {{ $order->created_at->format('d M Y') }} </span> </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr> <!-- Body -->
        <tr>
            <td style="padding:30px;">
                @yield('content')
            </td>
        </tr>
        <tr>
            <td style="background:#f0fafa; padding:20px 30px; text-align:center;">
                <p style="margin:0; font-size:13px; color:#555;"> Thank you for learning with <strong>Ed-Cademy</strong>
                </p>
                <p style="margin:6px 0 0; font-size:12px; color:#777;"> Website: <a href="https://www.ed-cademy.com"
                        style="color:#00838f; text-decoration:none;"> www.ed-cademy.com </a> </p>
                <p style="margin:6px 0 0; font-size:12px; color:#999;"> © {{ date('Y') }} Ed-Cademy. All rights
                    reserved. </p>
            </td>
        </tr>
    </table>

</body>

</html>
