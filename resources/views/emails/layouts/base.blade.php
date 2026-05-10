<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('subject', 'Aurachell')</title>
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* Theme — Aurachell mahogany on cream */
        body { background: #EDE0D0; font-family: Georgia, 'Times New Roman', serif; color: #2C0F0A; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #F5EDE4; }
        .header {
            padding: 40px 48px 32px;
            background: #2C0F0A;
            border-bottom: 3px solid #6B2016;
            text-align: center;
        }
        .logo-text { font-family: Georgia, serif; font-size: 22px; letter-spacing: 0.30em; text-transform: uppercase; color: #C4A48C; }
        .logo-tag { font-size: 10px; letter-spacing: 0.25em; text-transform: uppercase; color: rgba(245,237,228,0.50); font-family: Arial, sans-serif; margin-top: 6px; display: inline-block; }
        .body { padding: 48px; background: #F5EDE4; }
        .footer { padding: 32px 48px; background: #2C0F0A; text-align: center; }
        .footer p { color: rgba(245,237,228,0.60) !important; font-size: 11px !important; line-height: 1.6; margin: 0 0 6px; }
        .footer a { color: #C4A48C; text-decoration: none; }

        h1 { font-family: Georgia, serif; font-size: 28px; color: #2C0F0A; font-weight: normal; letter-spacing: -0.02em; margin: 0 0 16px; line-height: 1.25; }
        h2 { font-family: Georgia, serif; font-size: 18px; color: #6B2016; font-weight: normal; margin: 0 0 12px; }
        p { font-size: 15px; color: rgba(44,15,10,0.75); line-height: 1.7; margin: 0 0 16px; }
        a { color: #6B2016; }

        .divider { border: none; border-top: 1px solid rgba(107,32,22,0.15); margin: 32px 0; }

        .btn {
            display: inline-block;
            padding: 14px 36px;
            background: #6B2016;
            color: #F5EDE4 !important;
            text-decoration: none;
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-weight: 600;
            border-radius: 2px;
        }
        .btn:hover { background: #4a1510; }

        .highlight { color: #6B2016; font-weight: 600; }

        .label {
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(107,32,22,0.55);
            font-family: Arial, sans-serif;
            margin-bottom: 4px;
        }
        .value { font-size: 15px; color: #2C0F0A; margin-bottom: 16px; }

        .order-table { width: 100%; border-collapse: collapse; }
        .order-table th {
            text-align: left;
            font-size: 10px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(107,32,22,0.65);
            font-family: Arial, sans-serif;
            font-weight: 600;
            padding: 10px 0;
            border-bottom: 2px solid #6B2016;
        }
        .order-table td {
            padding: 14px 0;
            border-bottom: 1px solid rgba(107,32,22,0.10);
            font-size: 14px;
            color: rgba(44,15,10,0.80);
            vertical-align: top;
        }
        .order-table .total-row td {
            border-bottom: none;
            padding-top: 20px;
            color: #6B2016;
            font-size: 16px;
            font-weight: 600;
        }

        .tag {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(107,32,22,0.10);
            color: #6B2016;
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-weight: 600;
            border-radius: 2px;
        }

        .info-box {
            background: rgba(212,184,160,0.18);
            border-left: 3px solid #C4A48C;
            padding: 16px 20px;
            margin: 20px 0;
            font-size: 14px;
            color: rgba(44,15,10,0.80);
            line-height: 1.6;
        }

        @media only screen and (max-width: 600px) {
            .body, .header, .footer { padding-left: 24px !important; padding-right: 24px !important; }
            h1 { font-size: 24px !important; }
        }
    </style>
</head>
<body>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#EDE0D0;">
    <tr>
        <td align="center" style="padding: 30px 12px;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="wrapper" style="max-width:600px;width:100%;background:#F5EDE4;">

                <tr>
                    <td class="header">
                        <span class="logo-text">Aurachell</span>
                        <br>
                        <span class="logo-tag">Crafted for Calm</span>
                    </td>
                </tr>

                <tr>
                    <td class="body">
                        @yield('content')
                    </td>
                </tr>

                <tr>
                    <td class="footer">
                        <p>© {{ date('Y') }} Aurachell. All rights reserved.</p>
                        <p>Questions? Email us at <a href="mailto:hello@aurachell.com">hello@aurachell.com</a></p>
                        <p style="margin-top:14px;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;color:rgba(245,237,228,0.30) !important;">
                            Lagos · Nigeria
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
