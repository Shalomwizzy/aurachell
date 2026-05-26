@extends('emails.layouts.base')
@section('subject', 'Your Order is On Its Way — ' . $order->order_number)

@section('content')
<h1>It's on its way.</h1>
<p>Great news, <span class="highlight">{{ $order->customer_name }}</span> — your Aurachell order has been shipped and is heading to you.</p>

<div style="background:rgba(212,185,154,0.18);border:1px solid rgba(201,169,111,0.40);padding:28px;margin:28px 0;text-align:center;">
    @if($order->tracking_number)
    <p class="label">Tracking Number</p>
    <p style="font-family:monospace;font-size:20px;color:#C9A96F;letter-spacing:0.15em;margin:8px 0;">{{ $order->tracking_number }}</p>
    @if($order->courier)
    <p style="font-size:13px;color:rgba(30,12,20,0.55);font-family:Arial,sans-serif;">via {{ $order->courier }}</p>
    @endif
    @else
    <p class="label">Order Number</p>
    <p style="font-family:monospace;font-size:20px;color:#C9A96F;letter-spacing:0.1em;margin:8px 0;">{{ $order->order_number }}</p>
    @endif
</div>

<p>We'll send another update when your order is delivered. Typical delivery is 2–5 business days within Nigeria.</p>

<hr class="divider">

<h2>What's in your package</h2>
<table class="order-table">
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}{{ $item->variant_label ? ' · ' . $item->variant_label : '' }}</td>
            <td style="text-align:right;color:rgba(30,12,20,0.60);">× {{ $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top:40px;text-align:center;">
    <a href="{{ route('order.success', $order->order_number) }}" class="btn">Track Shipment</a>
</div>
@endsection
