<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Verify your Aurachell account</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; }
        body { margin:0 !important; padding:0 !important; width:100% !important; background:#EDE0D0; font-family:Georgia,'Times New Roman',serif; color:#1E0C14; }
        .wrapper { max-width:600px; margin:0 auto; background:#FAF5ED; }
        .header { padding:40px 48px 32px; background:#1E0C14; border-bottom:3px solid #371220; text-align:center; }
        .logo-text { font-family:Georgia,serif; font-size:22px; letter-spacing:0.30em; text-transform:uppercase; color:#C9A96F; }
        .logo-tag { font-size:10px; letter-spacing:0.25em; text-transform:uppercase; color:rgba(250,245,237,0.50); font-family:Arial,sans-serif; margin-top:6px; display:inline-block; }
        .body { padding:48px; background:#FAF5ED; }
        .footer { padding:32px 48px; background:#1E0C14; text-align:center; }
        .footer p { color:rgba(250,245,237,0.60) !important; font-size:11px !important; line-height:1.6; margin:0 0 6px; font-family:Arial,sans-serif; }
        .footer a { color:#C9A96F; text-decoration:none; }
        h1 { font-family:Georgia,serif; font-size:28px; color:#1E0C14; font-weight:normal; letter-spacing:-0.02em; margin:0 0 16px; line-height:1.25; }
        p { font-size:15px; color:rgba(30,12,20,0.75); line-height:1.7; margin:0 0 16px; font-family:Georgia,serif; }
        .divider { border:none; border-top:1px solid rgba(55,18,32,0.15); margin:32px 0; }
        .btn { display:inline-block; padding:16px 44px; background:#371220; color:#FAF5ED !important; text-decoration:none; font-size:11px; letter-spacing:0.22em; text-transform:uppercase; font-family:Arial,sans-serif; font-weight:600; border-radius:2px; }
        .label { font-size:10px; letter-spacing:0.2em; text-transform:uppercase; color:rgba(55,18,32,0.55); font-family:Arial,sans-serif; margin-bottom:4px; display:block; }
        .url-box { background:rgba(55,18,32,0.06); border:1px solid rgba(55,18,32,0.15); padding:14px 16px; margin-top:16px; word-break:break-all; font-size:12px; color:rgba(30,12,20,0.55); font-family:'Courier New',monospace; line-height:1.5; }
        .shield { width:56px; height:56px; background:rgba(55,18,32,0.08); border:1px solid rgba(55,18,32,0.15); border-radius:50%; display:inline-flex; align-items:center; justify-content:center; }
        @media only screen and (max-width:600px) {
            .body,.header,.footer { padding-left:24px !important; padding-right:24px !important; }
            h1 { font-size:22px !important; }
        }
    </style>
</head>
<body>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#EDE0D0;">
<tr>
<td align="center" style="padding:30px 12px;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="wrapper" style="max-width:600px;width:100%;background:#FAF5ED;">

    {{-- Header --}}
    <tr>
        <td class="header">
            <span class="logo-text">Aurachell</span><br>
            <span class="logo-tag">Crafted for Calm</span>
        </td>
    </tr>

    {{-- Body --}}
    <tr>
        <td class="body">

            {{-- Icon --}}
            <div style="text-align:center;margin-bottom:32px;">
                <div style="display:inline-block;width:60px;height:60px;background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.18);border-radius:50%;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" height="60">
                        <tr><td align="center" valign="middle">
                            <svg width="28" height="28" fill="none" stroke="#371220" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </td></tr>
                    </table>
                </div>
            </div>

            <h1 style="text-align:center;">Verify your email address</h1>

            <p>Welcome to Aurachell. Before you begin exploring our collection, please verify your email address by clicking the button below.</p>

            <p>This link expires in <strong style="color:#371220;">60 minutes</strong>. If you didn't create an account, you can safely ignore this email.</p>

            <hr class="divider">

            {{-- CTA --}}
            <div style="text-align:center;margin:32px 0;">
                <a href="{{ $url }}" class="btn">Verify My Email Address</a>
            </div>

            <hr class="divider">

            {{-- Fallback URL --}}
            <p style="font-size:13px;color:rgba(30,12,20,0.55);">If the button doesn't work, copy and paste this link into your browser:</p>
            <div class="url-box">{{ $url }}</div>

        </td>
    </tr>

    {{-- Footer --}}
    <tr>
        <td class="footer">
            <p>© {{ date('Y') }} Aurachell. All rights reserved.</p>
            <p>Questions? <a href="mailto:hello@aurachell.com">hello@aurachell.com</a></p>
            <p style="margin-top:14px;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;color:rgba(250,245,237,0.30) !important;">Lagos · Nigeria</p>
        </td>
    </tr>

</table>
</td>
</tr>
</table>
</body>
</html>
