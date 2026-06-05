<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#160c0b;font-family:'Inter',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#160c0b;">
<tr><td align="center" style="padding:40px 20px;">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

<tr><td style="padding:0 0 32px;text-align:center;">
    @php $emailLogo = \App\Models\Setting::get('logo'); @endphp
    @if($emailLogo)
    <img src="{{ asset('images/' . $emailLogo) }}" alt="Aurachell" width="150" style="display:inline-block;height:auto;border:0;max-width:180px;">
    @else
    <p style="font-family:serif;font-size:22px;color:#C9A96F;letter-spacing:0.3em;text-transform:uppercase;margin:0;">Aurachell</p>
    @endif
</td></tr>

<tr><td style="background:rgba(55,18,32,0.80);border:1px solid rgba(201,169,111,0.15);padding:40px;">
    <h1 style="font-family:serif;font-size:24px;color:#F7F2EB;margin:0 0 8px;">Payment Confirmed</h1>
    <p style="font-size:14px;color:rgba(247,242,235,0.55);margin:0 0 32px;">Your bank transfer has been verified. Your order is now being processed.</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid rgba(201,169,111,0.15);margin-bottom:32px;">
        <tr style="border-bottom:1px solid rgba(201,169,111,0.10);">
            <td style="padding:12px 16px;font-size:12px;color:rgba(247,242,235,0.45);width:40%;">Order Number</td>
            <td style="padding:12px 16px;font-size:13px;color:#F7F2EB;font-weight:600;">{{ $order->order_number }}</td>
        </tr>
        <tr style="border-bottom:1px solid rgba(201,169,111,0.10);">
            <td style="padding:12px 16px;font-size:12px;color:rgba(247,242,235,0.45);">Amount Paid</td>
            <td style="padding:12px 16px;font-size:13px;color:#C9A96F;font-weight:600;">&#8358;{{ number_format($order->total, 2) }}</td>
        </tr>
        @if($order->tracking_code)
        <tr style="border-bottom:1px solid rgba(201,169,111,0.10);">
            <td style="padding:12px 16px;font-size:12px;color:rgba(247,242,235,0.45);">Tracking Code</td>
            <td style="padding:16px;font-size:20px;color:#C9A96F;font-weight:700;letter-spacing:0.15em;font-family:monospace;">{{ $order->tracking_code }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding:12px 16px;font-size:12px;color:rgba(247,242,235,0.45);">Status</td>
            <td style="padding:12px 16px;font-size:13px;color:#F7F2EB;">Processing — Being Prepared</td>
        </tr>
    </table>

    @if($order->items->count())
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid rgba(201,169,111,0.10);margin-bottom:32px;">
        <tr style="border-bottom:1px solid rgba(201,169,111,0.10);">
            <td colspan="2" style="padding:10px 16px;font-size:10px;color:rgba(247,242,235,0.35);letter-spacing:0.2em;text-transform:uppercase;">Items in Your Order</td>
        </tr>
        @foreach($order->items as $item)
        <tr style="{{ !$loop->last ? 'border-bottom:1px solid rgba(201,169,111,0.08);' : '' }}">
            <td style="padding:10px 16px;font-size:13px;color:rgba(247,242,235,0.80);">{{ $item->product_name ?? $item->product?->name }} &times; {{ $item->quantity }}</td>
            <td style="padding:10px 16px;font-size:13px;color:#C9A96F;text-align:right;">&#8358;{{ number_format($item->total_price ?? ($item->price * $item->quantity), 0) }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <p style="font-size:13px;color:rgba(247,242,235,0.60);margin:0 0 8px;">Your payment has been confirmed and your order is now being prepared.</p>
    @if($order->tracking_code)
    <p style="font-size:13px;color:rgba(247,242,235,0.60);margin:0 0 24px;">Use your tracking code <strong style="color:#C9A96F;">{{ $order->tracking_code }}</strong> to follow your delivery at any time.</p>
    @else
    <p style="font-size:13px;color:rgba(247,242,235,0.60);margin:0 0 24px;">You will receive another notification when your order ships.</p>
    @endif
    <a href="{{ route('account.orders') }}"
       style="display:inline-block;background:#C9A96F;color:#371220;text-decoration:none;padding:14px 32px;font-size:11px;letter-spacing:0.25em;text-transform:uppercase;font-weight:600;">
        View My Orders
    </a>
</td></tr>

<tr><td style="padding:24px 0;text-align:center;">
    <p style="font-size:11px;color:rgba(247,242,235,0.20);margin:0;">&copy; {{ date('Y') }} Aurachell. All rights reserved.</p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>
