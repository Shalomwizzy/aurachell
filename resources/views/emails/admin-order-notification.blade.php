@extends('emails.layouts.base')
@section('subject', 'New Order #' . $order->order_number)

@section('content')
@if($order->payment_status === 'paid')
<h1>Payment Confirmed</h1>
<p>A payment has been confirmed. This order is ready to be processed and fulfilled.</p>
@else
<h1>New Order Placed</h1>
<p>A new order has been placed and is awaiting payment. You will receive another notification once payment is confirmed.</p>
@endif

<div class="info-box">
    <div class="label">Order Number</div>
    <div class="value"><strong class="highlight">{{ $order->order_number }}</strong></div>

    <div class="label">Customer</div>
    <div class="value">{{ $order->customer_name }} &mdash; {{ $order->customer_email }}</div>

    <div class="label">Order Total</div>
    <div class="value highlight" style="font-size:20px;">₦{{ number_format($order->total, 0) }}</div>

    <div class="label">Payment Status</div>
    <div class="value"><span class="tag">{{ ucfirst($order->payment_status) }}</span></div>

    @if($order->shipping_address)
    <div class="label">Ship To</div>
    <div class="value">
        {{ $order->shipping_address['address_line_1'] ?? '' }}
        @if(!empty($order->shipping_address['address_line_2'])), {{ $order->shipping_address['address_line_2'] }}@endif,
        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}
    </div>
    @endif
</div>

<hr class="divider">

<h2>Items Ordered</h2>
<table class="order-table">
    <thead>
        <tr>
            <th>Product</th>
            <th style="text-align:right;">Qty</th>
            <th style="text-align:right;">Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                {{ $item->product_name }}
                @if($item->variant_name)<br><span style="font-size:12px;color:rgba(44,15,10,0.50);">{{ $item->variant_name }}</span>@endif
                @if($item->scent_note)<br><span style="font-size:12px;color:rgba(44,15,10,0.50);">Scent: {{ $item->scent_note }}</span>@endif
            </td>
            <td style="text-align:right;">{{ $item->quantity }}</td>
            <td style="text-align:right;">₦{{ number_format($item->total_price, 0) }}</td>
        </tr>
        @endforeach
        @if($order->discount > 0)
        <tr>
            <td colspan="2" style="text-align:right;color:rgba(44,15,10,0.55);">Discount</td>
            <td style="text-align:right;color:#22c55e;">−₦{{ number_format($order->discount, 0) }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="2" style="text-align:right;color:rgba(44,15,10,0.55);">Shipping</td>
            <td style="text-align:right;">{{ $order->shipping_fee > 0 ? '₦'.number_format($order->shipping_fee,0) : 'Free' }}</td>
        </tr>
        <tr class="total-row">
            <td colspan="2" style="text-align:right;">Total</td>
            <td style="text-align:right;">₦{{ number_format($order->total, 0) }}</td>
        </tr>
    </tbody>
</table>

<hr class="divider">

<div style="text-align:center;">
    <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}" class="btn">View Order in Admin</a>
</div>
@endsection
