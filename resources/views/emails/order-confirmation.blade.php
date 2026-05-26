@extends('emails.layouts.base')
@section('subject', 'Order Confirmed — ' . $order->order_number)

@section('content')
<h1>Order Confirmed</h1>
<p>Thank you for your order, <span class="highlight">{{ $order->customer_name }}</span>. We're preparing your fragrance with care and will notify you when it ships.</p>

<div style="background:rgba(212,185,154,0.18);border:1px solid rgba(201,169,111,0.40);padding:24px;margin:28px 0;">
    <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <p class="label">Order Number</p>
            <p class="value highlight" style="font-family:monospace;letter-spacing:0.1em;">{{ $order->order_number }}</p>
        </div>
        <div>
            <p class="label">Tracking Code</p>
            <p class="value" style="font-family:monospace;">{{ $order->tracking_code ?? '—' }}</p>
        </div>
        <div>
            <p class="label">Order Date</p>
            <p class="value">{{ $order->placed_at?->format('M j, Y') }}</p>
        </div>
        <div>
            <p class="label">Status</p>
            <p><span class="tag">{{ $order->status }}</span></p>
        </div>
    </div>
</div>

<hr class="divider">

<h2>Order Summary</h2>
<table class="order-table">
    <thead>
        <tr>
            <th>Item</th>
            <th style="text-align:center;">Qty</th>
            <th style="text-align:right;">Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                {{ $item->product_name }}
                @if($item->variant_label)
                <br><span style="font-size:12px;color:rgba(30,12,20,0.55);">{{ $item->variant_label }}</span>
                @endif
                @if($item->scent_note)
                <br><span style="font-size:12px;color:rgba(30,12,20,0.55);">Scent: {{ $item->scent_note }}</span>
                @endif
            </td>
            <td style="text-align:center;">{{ $item->quantity }}</td>
            <td style="text-align:right;">₦{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
        @if($order->shipping_fee > 0)
        <tr>
            <td colspan="2" style="color:rgba(30,12,20,0.55);">Shipping</td>
            <td style="text-align:right;color:rgba(30,12,20,0.55);">₦{{ number_format($order->shipping_fee, 2) }}</td>
        </tr>
        @endif
        @if($order->discount > 0)
        <tr>
            <td colspan="2" style="color:rgba(30,12,20,0.55);">Discount ({{ $order->coupon_code }})</td>
            <td style="text-align:right;color:rgba(30,12,20,0.55);">−₦{{ number_format($order->discount, 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td colspan="2" style="font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;">Total</td>
            <td style="text-align:right;font-size:18px;">₦{{ number_format($order->total, 2) }}</td>
        </tr>
    </tbody>
</table>

<hr class="divider">

@if($order->shipping_address)
@php $addr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true); @endphp
<h2>Delivery Address</h2>
<p style="margin:0;line-height:2;">
    {{ $addr['name'] ?? $order->customer_name }}<br>
    {{ $addr['line1'] ?? '' }}{{ isset($addr['line2']) && $addr['line2'] ? ', ' . $addr['line2'] : '' }}<br>
    {{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }}<br>
    {{ $addr['phone'] ?? '' }}
</p>
@endif

<div style="margin-top:40px;text-align:center;">
    <a href="{{ route('order.success', $order->order_number) }}" class="btn">Track Your Order</a>
</div>
@endsection
