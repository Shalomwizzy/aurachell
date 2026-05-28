@extends('emails.layouts.base')
@section('subject', 'Order Confirmed — ' . $order->order_number)
@section('preheader', 'Your order is confirmed. We\'re preparing your fragrance with care.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(55,18,32,0.55);margin:0 0 14px;">
                Order Confirmed
            </p>
            <h1 style="font-family:Georgia,serif;font-size:30px;color:#FAF5ED;font-weight:normal;margin:0 0 10px;">
                Your order is confirmed.
            </h1>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.48);margin:0;">
                We're preparing your fragrance with care.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>Thank you, <strong style="color:#371220;">{{ $order->customer_name }}</strong>. Your order has been received and is being carefully prepared by our team in Lagos.</p>

{{-- Order meta --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.12);border:1px solid rgba(55,18,32,0.22);padding:24px 28px;margin:24px 0;">
    <tr>
        <td width="50%" style="vertical-align:top;padding-right:12px;">
            <span class="label">Order Number</span>
            <p style="font-family:Arial,sans-serif;font-size:16px;font-weight:700;color:#371220;margin:0;letter-spacing:0.05em;">{{ $order->order_number }}</p>
        </td>
        <td width="50%" style="vertical-align:top;padding-left:12px;">
            <span class="label">Order Date</span>
            <p style="font-family:Georgia,serif;font-size:14px;color:#371220;margin:0;">{{ $order->placed_at?->format('d F Y') ?? now()->format('d F Y') }}</p>
        </td>
    </tr>
    <tr><td colspan="2" style="padding:14px 0 0;"></td></tr>
    <tr>
        <td width="50%" style="vertical-align:top;padding-right:12px;">
            <span class="label">Tracking Code</span>
            <p style="font-family:Arial,sans-serif;font-size:13px;color:#371220;margin:0;">{{ $order->tracking_code ?? 'Assigned on dispatch' }}</p>
        </td>
        <td width="50%" style="vertical-align:top;padding-left:12px;">
            <span class="label">Status</span>
            <span class="tag">{{ ucfirst($order->status) }}</span>
        </td>
    </tr>
</table>

<hr class="divider">

{{-- Order items --}}
<h3 style="margin-bottom:16px;">Your Order</h3>
<table class="order-table">
    <thead>
        <tr>
            <th>Item</th>
            <th style="text-align:center;width:48px;">Qty</th>
            <th style="text-align:right;">Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td style="vertical-align:top;">
                <p style="font-family:Georgia,serif;font-size:14px;color:#371220;margin:0 0 3px;">{{ $item->product_name }}</p>
                @if($item->scent_note)
                <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(55,18,32,0.48);margin:0;">Scent: {{ $item->scent_note }}</p>
                @endif
            </td>
            <td style="text-align:center;color:rgba(55,18,32,0.60);">{{ $item->quantity }}</td>
            <td style="text-align:right;">₦{{ number_format($item->price * $item->quantity) }}</td>
        </tr>
        @endforeach
        @if($order->shipping_amount > 0)
        <tr>
            <td colspan="2" style="color:rgba(55,18,32,0.55);">Shipping</td>
            <td style="text-align:right;color:rgba(55,18,32,0.55);">₦{{ number_format($order->shipping_amount) }}</td>
        </tr>
        @endif
        @if($order->discount_amount > 0)
        <tr>
            <td colspan="2" style="color:#371220;">Discount</td>
            <td style="text-align:right;color:#371220;">-₦{{ number_format($order->discount_amount) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td colspan="2"><strong>Total</strong></td>
            <td style="text-align:right;"><strong>₦{{ number_format($order->total) }}</strong></td>
        </tr>
    </tbody>
</table>

<hr class="divider">

{{-- What happens next --}}
<h3 style="margin-bottom:16px;">What happens next</h3>
@foreach([
    ['We carefully pack your order', 'Every piece is wrapped with intention for its journey to you.'],
    ['You receive your shipping confirmation', 'We\'ll email you a tracking code as soon as your order dispatches.'],
    ['Your order arrives', 'Sit back and anticipate. Your scent experience is on its way.'],
] as [$title, $desc])
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:14px;">
    <tr>
        <td width="28" style="vertical-align:top;padding-top:2px;">
            <span style="font-family:Arial,sans-serif;font-size:11px;color:#371220;">✦</span>
        </td>
        <td>
            <p style="font-family:Georgia,serif;font-size:14px;color:#371220;margin:0 0 3px;font-weight:600;">{{ $title }}</p>
            <p style="font-family:Arial,sans-serif;font-size:12px;color:rgba(55,18,32,0.50);margin:0;">{{ $desc }}</p>
        </td>
    </tr>
</table>
@endforeach

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:28px 0 20px;">
            <a href="{{ route('order.success', $order->order_number) }}" class="btn">Track My Order</a>
        </td>
    </tr>
</table>

<p style="font-size:13px;color:rgba(55,18,32,0.45);text-align:center;margin:0;">
    Questions? Reply to this email or reach us at <a href="mailto:hello@aurachell.com" style="color:#371220;">hello@aurachell.com</a>
</p>

@endsection
