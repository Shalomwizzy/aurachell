<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#160c0b;font-family:'Inter',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#160c0b;">
<tr><td align="center" style="padding:40px 20px;">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

<tr><td style="padding:0 0 32px;text-align:center;">
    <p style="font-family:serif;font-size:22px;color:#C9A96F;letter-spacing:0.3em;text-transform:uppercase;margin:0;">Aurachell</p>
</td></tr>

<tr><td style="background:rgba(55,18,32,0.80);border:1px solid rgba(201,169,111,0.15);padding:40px;">
    <h1 style="font-family:serif;font-size:24px;color:#F7F2EB;margin:0 0 8px;">Payment Issue</h1>
    <p style="font-size:14px;color:rgba(247,242,235,0.55);margin:0 0 32px;">Unfortunately we were unable to confirm your bank transfer for order <strong style="color:#F7F2EB;">{{ $order->order_number }}</strong>.</p>

    @if($adminNote)
    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);padding:16px;margin-bottom:24px;">
        <p style="font-size:12px;color:rgba(247,242,235,0.45);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.1em;">Reason</p>
        <p style="font-size:13px;color:rgba(247,242,235,0.80);margin:0;">{{ $adminNote }}</p>
    </div>
    @endif

    <p style="font-size:13px;color:rgba(247,242,235,0.60);margin:0 0 24px;">Please contact our support team at <a href="mailto:{{ config('mail.from.address') }}" style="color:#C9A96F;">{{ config('mail.from.address') }}</a> if you believe this is an error, or to arrange an alternative payment.</p>
</td></tr>

<tr><td style="padding:24px 0;text-align:center;">
    <p style="font-size:11px;color:rgba(247,242,235,0.20);margin:0;">&copy; {{ date('Y') }} Aurachell. All rights reserved.</p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>
