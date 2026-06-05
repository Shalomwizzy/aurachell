<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#160c0b;font-family:'Inter',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#160c0b;">
<tr><td align="center" style="padding:40px 20px;">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

{{-- Header --}}
<tr><td style="padding:0 0 32px;text-align:center;">
    <p style="font-family:serif;font-size:22px;color:#C9A96F;letter-spacing:0.3em;text-transform:uppercase;margin:0;">Aurachell</p>
</td></tr>

{{-- Body --}}
<tr><td style="background:rgba(55,18,32,0.80);border:1px solid rgba(201,169,111,0.15);padding:40px;">
    <h1 style="font-family:serif;font-size:24px;color:#F7F2EB;margin:0 0 8px;">Bank Transfer Proof Received</h1>
    <p style="font-size:14px;color:rgba(247,242,235,0.55);margin:0 0 32px;">A customer has uploaded proof of payment.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid rgba(201,169,111,0.15);margin-bottom:32px;">
        <tr style="border-bottom:1px solid rgba(201,169,111,0.10);">
            <td style="padding:12px 16px;font-size:12px;color:rgba(247,242,235,0.45);width:40%;">Order Number</td>
            <td style="padding:12px 16px;font-size:13px;color:#F7F2EB;font-weight:600;">{{ $order->order_number }}</td>
        </tr>
        <tr style="border-bottom:1px solid rgba(201,169,111,0.10);">
            <td style="padding:12px 16px;font-size:12px;color:rgba(247,242,235,0.45);">Customer</td>
            <td style="padding:12px 16px;font-size:13px;color:#F7F2EB;">{{ $order->user?->name ?? $order->guest_name ?? 'Guest' }}</td>
        </tr>
        <tr>
            <td style="padding:12px 16px;font-size:12px;color:rgba(247,242,235,0.45);">Amount</td>
            <td style="padding:12px 16px;font-size:13px;color:#C9A96F;font-weight:600;">&#8358;{{ number_format($order->total, 2) }}</td>
        </tr>
    </table>

    <a href="{{ route('admin.bank-transfers.index', ['status'=>'pending']) }}"
       style="display:inline-block;background:#C9A96F;color:#371220;text-decoration:none;padding:14px 32px;font-size:11px;letter-spacing:0.25em;text-transform:uppercase;font-weight:600;">
        Review Transfer
    </a>
</td></tr>

{{-- Footer --}}
<tr><td style="padding:24px 0;text-align:center;">
    <p style="font-size:11px;color:rgba(247,242,235,0.20);margin:0;">This is an automated admin notification.</p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>
