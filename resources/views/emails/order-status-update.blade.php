@extends('emails.layouts.inline')
@section('subject', 'Order Update: ' . $statusLabel . ' — ' . $order->order_number)
@section('preheader', 'Your order ' . $order->order_number . ' is now ' . $statusLabel . '.')

@section('content')
<h1 style="font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:normal;color:#371220;margin:0 0 14px;line-height:1.2;">Order {{ $statusLabel }}</h1>
<p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.75;margin:0 0 24px;">Hi <strong style="color:#371220;">{{ $order->customer_name }}</strong>, {{ $statusMessage }}</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border-left:3px solid #C9A96F;margin:0 0 28px;"><tr>
<td align="center" style="padding:24px;">
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;margin:0 0 6px;">Order Number</div>
    <div style="font-family:'Courier New',monospace;font-size:20px;color:#371220;letter-spacing:1px;">{{ $order->order_number }}</div>
    @if($order->tracking_code)
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;margin:16px 0 6px;">Tracking Code</div>
    <div style="font-family:'Courier New',monospace;font-size:16px;color:#371220;letter-spacing:1px;">{{ $order->tracking_code }}</div>
    @endif
</td>
</tr></table>

<div style="font-family:Georgia,'Times New Roman',serif;font-size:17px;color:#371220;margin:0 0 12px;">Your Items</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
    @foreach($order->items as $item)
    <tr>
        <td style="padding:12px 0;border-bottom:1px solid rgba(55,18,32,0.08);font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#3a2a25;">{{ $item->product_name }}@if($item->variant_name) · {{ $item->variant_name }}@endif</td>
        <td align="right" style="padding:12px 0;border-bottom:1px solid rgba(55,18,32,0.08);font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#8a7266;">× {{ $item->quantity }}</td>
    </tr>
    @endforeach
</table>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:34px 0 0;"><tr><td align="center">
    <a href="{{ route('track-order') }}" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">Track Order</a>
</td></tr></table>
@endsection
