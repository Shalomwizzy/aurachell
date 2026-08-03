@extends('emails.layouts.inline')
@section('subject', 'Order Delivered #' . $order->order_number)
@section('preheader', 'Order ' . $order->order_number . ' has been marked delivered — ₦' . number_format($order->total, 0))

@section('content')
<div style="font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:3px;text-transform:uppercase;color:#A9885A;margin-bottom:14px;">Admin Notification</div>
<h1 style="font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:normal;color:#371220;margin:0 0 14px;line-height:1.2;">Order Delivered</h1>
<p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.7;margin:0 0 26px;">Order <strong style="color:#371220;">{{ $order->order_number }}</strong> has been marked as delivered. This order is now complete.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border-left:3px solid #C9A96F;margin:0 0 28px;"><tr>
<td style="padding:22px 24px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:Arial,Helvetica,sans-serif;">
        <tr>
            <td style="padding:0 0 4px;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Order Number</td>
            <td align="right" style="padding:0 0 4px;font-size:15px;font-weight:bold;color:#371220;">{{ $order->order_number }}</td>
        </tr>
        <tr>
            <td style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Customer</td>
            <td align="right" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:14px;color:#371220;">{{ $order->customer_name }}</td>
        </tr>
        <tr><td colspan="2" align="right" style="padding:0 0 2px;font-size:13px;color:#5c4a45;">{{ $order->customer_email }}</td></tr>
        @if($order->customer_phone)
        <tr><td colspan="2" align="right" style="padding:0 0 10px;font-size:13px;color:#5c4a45;">☎ {{ $order->customer_phone }}</td></tr>
        @endif
        <tr>
            <td style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Order Total</td>
            <td align="right" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:19px;font-weight:bold;color:#371220;">₦{{ number_format($order->total, 0) }}</td>
        </tr>
        <tr>
            <td style="padding:12px 0 0;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Delivered On</td>
            <td align="right" style="padding:12px 0 0;border-top:1px solid rgba(55,18,32,0.10);font-size:13px;color:#371220;">{{ now()->format('M j, Y · g:i A') }}</td>
        </tr>
    </table>
</td>
</tr></table>

<div style="font-family:Georgia,'Times New Roman',serif;font-size:17px;color:#371220;margin:0 0 12px;">Items Delivered</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
    @foreach($order->items as $item)
    <tr>
        <td style="padding:12px 0;border-bottom:1px solid rgba(55,18,32,0.08);font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#3a2a25;">{{ $item->product_name }}@if($item->variant_name) · {{ $item->variant_name }}@endif</td>
        <td align="right" style="padding:12px 0;border-bottom:1px solid rgba(55,18,32,0.08);font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#8a7266;">× {{ $item->quantity }}</td>
    </tr>
    @endforeach
</table>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:34px 0 0;"><tr><td align="center">
    <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">View Order in Admin</a>
</td></tr></table>
@endsection
