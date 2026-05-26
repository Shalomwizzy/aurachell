@extends('emails.layouts.base')
@section('subject', 'Order Update: ' . $statusLabel . ' — ' . $order->order_number)

@section('content')
<h1>Order {{ $statusLabel }}</h1>
<p>Hi <span class="highlight">{{ $order->customer_name }}</span>, {{ $statusMessage }}</p>

<div style="background:rgba(212,185,154,0.18);border:1px solid rgba(201,169,111,0.40);padding:28px;margin:28px 0;text-align:center;">
    <p class="label">Order Number</p>
    <p style="font-family:monospace;font-size:20px;color:#C9A96F;letter-spacing:0.1em;margin:8px 0;">{{ $order->order_number }}</p>
    @if($order->tracking_code)
    <p class="label" style="margin-top:16px;">Tracking Code</p>
    <p style="font-family:monospace;font-size:16px;color:#C9A96F;letter-spacing:0.1em;margin:4px 0;">{{ $order->tracking_code }}</p>
    @endif
</div>

<hr class="divider">

<h2>Your Items</h2>
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
    <a href="{{ route('track-order') }}" class="btn">Track Order</a>
</div>
@endsection
