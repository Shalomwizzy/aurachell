@extends('emails.layouts.inline')
@section('subject', 'Your Aurachell order is on its way — ' . $order->order_number)
@section('preheader', 'Your package has left our hands and is heading to yours.')

@section('hero')
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#371220;">
<tr><td align="center" style="padding:40px 40px 44px;">
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:4px;text-transform:uppercase;color:#B79B78;margin:0 0 14px;">Dispatched</div>
    <div style="font-family:Georgia,'Times New Roman',serif;font-size:30px;color:#FAF5ED;line-height:1.2;margin:0 0 10px;">It's on its way to you.</div>
    <div style="font-family:Georgia,'Times New Roman',serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.55);">Your scent experience is in transit.</div>
</td></tr>
</table>
@endsection

@section('content')
<p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.75;margin:0 0 22px;">Wonderful news, <strong style="color:#371220;">{{ $order->customer_name }}</strong>. Your Aurachell order has been carefully packed and handed over for delivery.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border-left:3px solid #C9A96F;margin:0 0 28px;"><tr>
<td align="center" style="padding:26px 24px;">
    @if($order->tracking_code)
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;margin:0 0 8px;">Your Tracking Code</div>
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:bold;color:#371220;letter-spacing:2px;margin:0 0 6px;">{{ $order->tracking_code }}</div>
    @else
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;margin:0 0 8px;">Order Reference</div>
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:20px;font-weight:bold;color:#371220;letter-spacing:1px;margin:0 0 6px;">{{ $order->order_number }}</div>
    @endif
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#8a7266;letter-spacing:1px;">Estimated delivery: 2–5 business days within Nigeria</div>
</td>
</tr></table>

<div style="font-family:Georgia,'Times New Roman',serif;font-size:17px;color:#371220;margin:0 0 12px;">What's in your package</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
    @foreach($order->items as $item)
    <tr>
        <td style="padding:12px 0;border-bottom:1px solid rgba(55,18,32,0.08);vertical-align:top;">
            <div style="font-family:Georgia,'Times New Roman',serif;font-size:14px;color:#371220;margin:0 0 2px;">{{ $item->product_name }}</div>
            @if($item->scent_note)<div style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#8a7266;">Scent: {{ $item->scent_note }}</div>@endif
        </td>
        <td align="right" style="padding:12px 0;border-bottom:1px solid rgba(55,18,32,0.08);font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#8a7266;vertical-align:top;">× {{ $item->quantity }}</td>
    </tr>
    @endforeach
</table>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:32px 0 8px;"><tr><td align="center">
    <a href="{{ route('order.success', $order->order_number) }}" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">Track My Delivery</a>
</td></tr></table>

<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#8a7266;text-align:center;margin:22px 0 0;">Need help? <a href="mailto:hello@aurachell.com" style="color:#371220;">hello@aurachell.com</a></p>
@endsection
