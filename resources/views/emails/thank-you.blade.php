@extends('emails.layouts.base')
@section('subject', 'Thank you for choosing Aurachell — ' . $order->order_number)
@section('preheader', 'Your order is confirmed. Every piece is being prepared with intention.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <h1 style="font-family:Georgia,serif;font-size:32px;color:#FAF5ED;font-weight:normal;margin:0 0 12px;letter-spacing:-0.01em;">
                Thank you, {{ explode(' ', $user->name)[0] }}.
            </h1>
            <p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(201,169,111,0.65);margin:0;line-height:1.5;">
                Every order is a small act of choosing beauty. We don't take that lightly.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>Your order has been received and is being prepared with care. You've trusted us with your space, and we'll honour that.</p>

{{-- Order summary box --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.10);border:1px solid rgba(55,18,32,0.20);padding:24px 28px;margin:24px 0;">
    <tr>
        <td width="50%" style="vertical-align:top;padding-right:12px;">
            <span class="label">Order Number</span>
            <p style="font-family:Arial,sans-serif;font-size:16px;font-weight:700;color:#371220;margin:0;letter-spacing:0.06em;">{{ $order->order_number }}</p>
        </td>
        <td width="50%" style="vertical-align:top;padding-left:12px;text-align:right;">
            <span class="label">Order Total</span>
            <p style="font-family:Georgia,serif;font-size:20px;color:#371220;margin:0;font-weight:600;">₦{{ number_format($order->total) }}</p>
        </td>
    </tr>
</table>

<hr class="divider">

@if($order->items->count())
<h3 style="margin-bottom:16px;">What you ordered</h3>
<table class="order-table">
    <thead>
        <tr>
            <th>Item</th>
            <th style="text-align:right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                <p style="font-family:Georgia,serif;font-size:14px;color:#371220;margin:0 0 2px;">{{ $item->product_name }}</p>
                @if($item->scent_note)
                <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(55,18,32,0.48);margin:0;">Scent: {{ $item->scent_note }}</p>
                @endif
                <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(55,18,32,0.45);margin:2px 0 0;">Qty: {{ $item->quantity }}</p>
            </td>
            <td style="text-align:right;">₦{{ number_format($item->subtotal ?? ($item->price * $item->quantity)) }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td><strong>Total</strong></td>
            <td style="text-align:right;"><strong>₦{{ number_format($order->total) }}</strong></td>
        </tr>
    </tbody>
</table>
@endif

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:32px 0 24px;">
            <a href="{{ route('account.order.detail', $order->order_number) }}" class="btn">Track My Order</a>
        </td>
    </tr>
</table>

{{-- Closing note --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border-top:1px solid rgba(55,18,32,0.08);padding:24px 0 0;">
    <tr>
        <td style="text-align:center;">
            <p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(55,18,32,0.50);margin:0 0 10px;line-height:1.6;">
                "Every fragrance is a memory you haven't made yet."
            </p>
            <p style="font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.22em;text-transform:uppercase;color:rgba(55,18,32,0.35);margin:0;">
                Aurachell &nbsp;·&nbsp; Lagos
            </p>
        </td>
    </tr>
</table>

@endsection
