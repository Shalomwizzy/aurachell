@extends('emails.layouts.base')
@section('subject', 'Order Delivered #' . $order->order_number)
@section('preheader', 'Order ' . $order->order_number . ' has been marked delivered — ₦' . number_format($order->total, 0))

@section('content')
<span class="eyebrow">Admin Notification</span>
<h1>Order Delivered</h1>
<p>Order <strong class="highlight">{{ $order->order_number }}</strong> has been marked as <span class="tag-gold">Delivered</span>. This order is now complete.</p>

<div class="info-box">
    <div class="label">Order Number</div>
    <div class="value"><strong class="highlight">{{ $order->order_number }}</strong></div>

    <div class="label">Customer</div>
    <div class="value">{{ $order->customer_name }} &mdash; {{ $order->customer_email }}</div>

    <div class="label">Order Total</div>
    <div class="value highlight" style="font-size:20px;">₦{{ number_format($order->total, 0) }}</div>

    <div class="label">Delivered On</div>
    <div class="value">{{ now()->format('M j, Y · g:i A') }}</div>

    @if($order->shipping_address)
    <div class="label">Delivered To</div>
    <div class="value">
        {{ $order->shipping_address['address_line_1'] ?? '' }}
        @if(!empty($order->shipping_address['address_line_2'])), {{ $order->shipping_address['address_line_2'] }}@endif,
        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}
    </div>
    @endif
</div>

<hr class="divider">

<h2>Items Delivered</h2>
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
                @if($item->variant_name)<br><span style="font-size:12px;color:rgba(55,18,32,0.50);">{{ $item->variant_name }}</span>@endif
                @if($item->scent_note)<br><span style="font-size:12px;color:rgba(55,18,32,0.50);">Scent: {{ $item->scent_note }}</span>@endif
            </td>
            <td style="text-align:right;">{{ $item->quantity }}</td>
            <td style="text-align:right;">₦{{ number_format($item->total_price, 0) }}</td>
        </tr>
        @endforeach
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
