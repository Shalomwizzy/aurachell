@php
    $logo   = \App\Models\Setting::get('logo');
    $isPaid = $order->payment_status === 'paid';
    $ship   = $order->shipping_address ?? [];
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $isPaid ? 'Payment Confirmed' : 'New Order' }} — {{ $order->order_number }}</title>
</head>
<body style="margin:0;padding:0;background-color:#371220;">

<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">{{ $isPaid ? 'Payment confirmed' : 'New order placed' }} — {{ $order->order_number }} — ₦{{ number_format($order->total, 0) }}</div>

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
            <h1 style="font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:normal;color:#371220;margin:0 0 14px;line-height:1.2;">{{ $isPaid ? 'Payment Confirmed' : 'New Order Placed' }}</h1>
            <p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.7;margin:0 0 26px;">{{ $isPaid ? 'A payment has been confirmed. This order is ready to be processed and fulfilled.' : 'A new order has been placed and is awaiting payment. You will receive another notification once payment is confirmed.' }}</p>

            {{-- Details --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border-left:3px solid #C9A96F;margin:0 0 28px;">
            <tr><td style="padding:22px 24px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:Arial,Helvetica,sans-serif;">
                    <tr>
                        <td style="padding:0 0 4px;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Order Number</td>
                        <td align="right" style="padding:0 0 4px;font-size:15px;font-weight:bold;color:#371220;">{{ $order->order_number }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid rgba(55,18,32,0.10);font-size:0;line-height:0;padding:12px 0 0;">&nbsp;</td></tr>
                    <tr>
                        <td style="padding:0 0 4px;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Customer</td>
                        <td align="right" style="padding:0 0 4px;font-size:14px;color:#371220;">{{ $order->customer_name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" style="padding:0 0 12px;font-size:13px;color:#5c4a45;">{{ $order->customer_email }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Order Total</td>
                        <td align="right" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:19px;font-weight:bold;color:#371220;">₦{{ number_format($order->total, 0) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0 0;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Payment</td>
                        <td align="right" style="padding:12px 0 0;border-top:1px solid rgba(55,18,32,0.10);font-size:12px;">
                            <span style="display:inline-block;padding:3px 12px;background-color:{{ $isPaid ? '#C9A96F' : 'rgba(55,18,32,0.10)' }};color:{{ $isPaid ? '#371220' : '#5c4a45' }};font-weight:bold;letter-spacing:1px;text-transform:uppercase;font-size:10px;">{{ ucfirst($order->payment_status) }}</span>
                        </td>
                    </tr>
                    @if(!empty($ship['address_line_1']))
                    <tr>
                        <td colspan="2" style="padding:14px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Ship To</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size:13px;color:#5c4a45;line-height:1.5;">
                            {{ $ship['address_line_1'] }}@if(!empty($ship['address_line_2'])), {{ $ship['address_line_2'] }}@endif,
                            {{ $ship['city'] ?? '' }}@if(!empty($ship['state'])), {{ $ship['state'] }}@endif
                        </td>
                    </tr>
                    @endif
                </table>
            </td></tr>
            </table>

            {{-- Items --}}
            <div style="font-family:Georgia,'Times New Roman',serif;font-size:17px;color:#371220;margin:0 0 12px;">Items Ordered</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;">
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid rgba(55,18,32,0.20);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Product</td>
                    <td align="right" style="padding:10px 0;border-bottom:1px solid rgba(55,18,32,0.20);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Qty</td>
                    <td align="right" style="padding:10px 0;border-bottom:1px solid rgba(55,18,32,0.20);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Price</td>
                </tr>
                @foreach($order->items as $item)
                <tr>
                    <td style="padding:14px 0;border-bottom:1px solid rgba(55,18,32,0.08);font-size:14px;color:#3a2a25;vertical-align:top;">
                        {{ $item->product_name }}
                        @if($item->variant_name)<br><span style="font-size:12px;color:#8a7266;">{{ $item->variant_name }}</span>@endif
                        @if($item->scent_note)<br><span style="font-size:12px;color:#8a7266;">Scent: {{ $item->scent_note }}</span>@endif
                    </td>
                    <td align="right" style="padding:14px 0;border-bottom:1px solid rgba(55,18,32,0.08);font-size:14px;color:#3a2a25;vertical-align:top;">{{ $item->quantity }}</td>
                    <td align="right" style="padding:14px 0;border-bottom:1px solid rgba(55,18,32,0.08);font-size:14px;color:#3a2a25;vertical-align:top;">₦{{ number_format($item->total_price, 0) }}</td>
                </tr>
                @endforeach
                @if($order->discount > 0)
                <tr>
                    <td colspan="2" align="right" style="padding:12px 0 4px;font-size:13px;color:#8a7266;">Discount</td>
                    <td align="right" style="padding:12px 0 4px;font-size:13px;color:#A9885A;">−₦{{ number_format($order->discount, 0) }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="2" align="right" style="padding:4px 0;font-size:13px;color:#8a7266;">Shipping</td>
                    <td align="right" style="padding:4px 0;font-size:13px;color:#3a2a25;">₦{{ number_format($order->shipping_fee, 0) }}</td>
                </tr>
                <tr>
                    <td colspan="2" align="right" style="padding:16px 0 0;border-top:1px solid rgba(55,18,32,0.20);font-size:16px;font-weight:bold;color:#371220;">Total</td>
                    <td align="right" style="padding:16px 0 0;border-top:1px solid rgba(55,18,32,0.20);font-size:16px;font-weight:bold;color:#371220;">₦{{ number_format($order->total, 0) }}</td>
                </tr>
            </table>

            {{-- CTA --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:34px 0 0;"><tr><td align="center">
                <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">View Order in Admin</a>
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
