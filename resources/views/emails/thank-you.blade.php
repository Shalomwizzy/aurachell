@extends('emails.layouts.base')
@section('subject', 'Thank you for your order')

@section('content')
<h1>Thank you, {{ $user->name }}.</h1>

<p>Your order has been received and is being prepared with care. Every Aurachell piece is handled with the attention it deserves.</p>

<hr class="divider">

<p class="label">Order Reference</p>
<p class="value">{{ $order->order_number }}</p>

<p class="label">Total</p>
<p class="value" style="color:#C4A48C;">₦{{ number_format($order->total) }}</p>

@if($order->items->count())
<table class="order-table" style="margin-top:24px;">
    <thead>
        <tr>
            <th>Item</th>
            <th style="text-align:right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }} <span style="color:rgba(44,15,10,0.55)">× {{ $item->quantity }}</span></td>
            <td style="text-align:right;">₦{{ number_format($item->subtotal) }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td><strong>Total</strong></td>
            <td style="text-align:right;"><strong>₦{{ number_format($order->total) }}</strong></td>
        </tr>
    </tbody>
</table>
@endif

<div style="margin-top:36px;text-align:center;">
    <a href="{{ route('account.order.detail', $order->order_number) }}"
       style="display:inline-block;padding:14px 40px;background:#6B2016;color:#F5EDE4;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        Track My Order
    </a>
</div>
@endsection
