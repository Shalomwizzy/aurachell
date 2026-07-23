@extends('emails.layouts.base')
@section('subject', 'Payment Confirmed — #' . $order->order_number)
@section('preheader', 'Your bank transfer for order ' . $order->order_number . ' has been confirmed.')

@section('content')
<span class="eyebrow">Payment Confirmed</span>
<h1>Your payment is confirmed</h1>
<p>Your bank transfer has been verified and your order is now being prepared. Thank you for shopping with Aurachell.</p>

<div class="info-box">
    <div class="label">Order Number</div>
    <div class="value"><strong class="highlight">{{ $order->order_number }}</strong></div>

    <div class="label">Amount Paid</div>
    <div class="value highlight" style="font-size:20px;">₦{{ number_format($order->total, 0) }}</div>

    <div class="label">Status</div>
    <div class="value"><span class="tag-gold">Processing — Being Prepared</span></div>
</div>

@if($order->tracking_code)
<div style="text-align:center;margin:28px 0;">
    <span class="label">Your Tracking Code</span>
    <div style="font-size:22px;color:#371220;font-weight:700;letter-spacing:0.15em;font-family:'Courier New',monospace;">{{ $order->tracking_code }}</div>
</div>
@endif

@if($order->items->count())
<hr class="divider">
<h2>Items in Your Order</h2>
<table class="order-table">
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name ?? $item->product?->name }} &times; {{ $item->quantity }}</td>
            <td style="text-align:right;">₦{{ number_format($item->total_price ?? ($item->price * $item->quantity), 0) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<hr class="divider">

<div style="text-align:center;">
    <a href="{{ route('account.orders') }}" class="btn">View My Orders</a>
</div>
@endsection
