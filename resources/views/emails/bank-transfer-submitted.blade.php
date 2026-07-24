@php
    $logo = \App\Models\Setting::get('logo');
    $bt   = $order->bankTransfer;
    $amount = $bt->amount ?? $order->total;
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Bank Transfer Proof — {{ $order->order_number }}</title>
</head>
<body style="margin:0;padding:0;background-color:#371220;">

<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">A customer uploaded proof of payment for order {{ $order->order_number }} — ₦{{ number_format($amount, 0) }}</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#371220;">
<tr><td align="center" style="padding:32px 12px;">

    <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px;max-width:600px;background-color:#FAF5ED;">

        {{-- Header --}}
        <tr><td align="center" style="background-color:#371220;padding:36px 40px 22px;">
            @if($logo)
            <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" width="150" style="display:block;margin:0 auto;max-width:170px;height:auto;border:0;">
            @else
            <div style="font-family:Georgia,'Times New Roman',serif;font-size:23px;letter-spacing:6px;color:#C9A96F;text-transform:uppercase;">Aurachell</div>
            @endif
            <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:3px;color:#B79B78;text-transform:uppercase;margin-top:9px;">Crafted for Calm &nbsp;·&nbsp; Lagos</div>
        </td></tr>
        <tr><td style="background-color:#371220;padding:0 40px 4px;">
            <div style="height:2px;background-color:#C9A96F;font-size:0;line-height:0;">&nbsp;</div>
        </td></tr>

        {{-- Body --}}
        <tr><td style="background-color:#FAF5ED;padding:40px;">

            <div style="font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:3px;text-transform:uppercase;color:#A9885A;margin-bottom:14px;">Admin Notification</div>
            <h1 style="font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:normal;color:#371220;margin:0 0 14px;line-height:1.2;">Bank Transfer Proof Received</h1>
            <p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.7;margin:0 0 26px;">A customer has uploaded their proof of payment. The screenshot is <strong style="color:#371220;">attached to this email</strong>. Review it and confirm or reject the transfer in the admin panel.</p>

            {{-- Details --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border-left:3px solid #C9A96F;margin:0 0 28px;">
            <tr><td style="padding:22px 24px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:Arial,Helvetica,sans-serif;">
                    <tr>
                        <td style="padding:0 0 4px;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Order Number</td>
                        <td align="right" style="padding:0 0 4px;font-size:15px;font-weight:bold;color:#371220;">{{ $order->order_number }}</td>
                    </tr>
                    @if($bt && $bt->reference)
                    <tr>
                        <td style="padding:10px 0 4px;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Reference</td>
                        <td align="right" style="padding:10px 0 4px;font-size:13px;color:#371220;">{{ $bt->reference }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding:10px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Customer</td>
                        <td align="right" style="padding:10px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:14px;color:#371220;">{{ $order->customer_name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" style="padding:0 0 10px;font-size:13px;color:#5c4a45;">{{ $order->customer_email }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Amount</td>
                        <td align="right" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:19px;font-weight:bold;color:#371220;">₦{{ number_format($amount, 0) }}</td>
                    </tr>
                    @if($bt && $bt->proof_original_name)
                    <tr>
                        <td style="padding:12px 0 0;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Attached File</td>
                        <td align="right" style="padding:12px 0 0;border-top:1px solid rgba(55,18,32,0.10);font-size:13px;color:#5c4a45;">{{ $bt->proof_original_name }}</td>
                    </tr>
                    @endif
                </table>
            </td></tr>
            </table>

            @if($bt && $bt->customer_note)
            <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;margin:0 0 6px;">Customer Note</div>
            <p style="font-family:Georgia,'Times New Roman',serif;font-size:14px;color:#5c4a45;line-height:1.6;margin:0 0 28px;">{{ $bt->customer_note }}</p>
            @endif

            {{-- CTA --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:6px 0 0;"><tr><td align="center">
                <a href="{{ config('app.url') }}/admin/bank-transfers?status=pending" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">Review Transfer</a>
            </td></tr></table>

        </td></tr>

        {{-- Footer --}}
        <tr><td align="center" style="background-color:#371220;padding:28px 40px 34px;font-family:Arial,Helvetica,sans-serif;">
            <a href="{{ config('app.url') }}" style="color:#C9A96F;text-decoration:none;font-size:10px;letter-spacing:2px;text-transform:uppercase;">aurachell.com</a>
            <span style="color:rgba(250,245,237,0.30);"> &nbsp;·&nbsp; </span>
            <a href="mailto:hello@aurachell.com" style="color:#C9A96F;text-decoration:none;font-size:10px;letter-spacing:2px;text-transform:uppercase;">hello@aurachell.com</a>
            <p style="color:rgba(250,245,237,0.45);font-size:11px;line-height:1.6;margin:16px 0 0;">© {{ date('Y') }} Aurachell · Lagos, Nigeria</p>
        </td></tr>

    </table>

</td></tr>
</table>

</body>
</html>
