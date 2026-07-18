@extends('emails.layouts.base')
@section('subject', 'Your Aurachell order is on its way — ' . $order->order_number)
@section('preheader', 'Your package has left our hands and is heading to yours.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.55);margin:0 0 14px;">
                Dispatched
            </p>
            <h1 style="font-family:Georgia,serif;font-size:30px;color:#FAF5ED;font-weight:normal;margin:0 0 10px;">
                It's on its way to you.
            </h1>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.48);margin:0;">
                Your scent experience is in transit.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>Wonderful news, <strong style="color:#371220;">{{ $order->customer_name }}</strong>. Your Aurachell order has been carefully packed and handed over for delivery.</p>

{{-- Tracking box --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.12);border:1px solid rgba(55,18,32,0.22);padding:28px;margin:24px 0;text-align:center;">
    <tr>
        <td>
            @if($order->tracking_code)
            <span class="label">Your Tracking Code</span>
            <p style="font-family:Arial,sans-serif;font-size:22px;font-weight:700;color:#371220;letter-spacing:0.12em;margin:10px 0 6px;">
                {{ $order->tracking_code }}
            </p>
            @else
            <span class="label">Order Reference</span>
            <p style="font-family:Arial,sans-serif;font-size:20px;font-weight:700;color:#371220;letter-spacing:0.08em;margin:10px 0 6px;">
                {{ $order->order_number }}
            </p>
            @endif
            <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(55,18,32,0.45);margin:0;letter-spacing:0.10em;">
                Estimated delivery: 2–5 business days within Nigeria
            </p>
        </td>
    </tr>
</table>

<hr class="divider">

{{-- What's inside --}}
<h3 style="margin-bottom:16px;">What's in your package</h3>
<table class="order-table">
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                <p style="font-family:Georgia,serif;font-size:14px;color:#371220;margin:0 0 2px;">{{ $item->product_name }}</p>
                @if($item->scent_note)
                <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(55,18,32,0.48);margin:0;">Scent: {{ $item->scent_note }}</p>
                @endif
            </td>
            <td style="text-align:right;color:rgba(55,18,32,0.55);">× {{ $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:28px 0 24px;">
            <a href="{{ route('order.success', $order->order_number) }}" class="btn">Track My Delivery</a>
        </td>
    </tr>
</table>

{{-- Tips while waiting --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.04);border-left:2px solid #371220;padding:20px 24px;margin:0 0 24px;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:#371220;margin:0 0 8px;line-height:1.55;">
                While you wait — consider where in your home you'd like to place your new diffuser.
            </p>
            <p style="font-family:Arial,sans-serif;font-size:12px;color:rgba(55,18,32,0.50);margin:0;">
                Near the entrance, in the bedroom, or by the living room — each creates a different kind of welcome.
            </p>
        </td>
    </tr>
</table>

<p style="font-size:13px;color:rgba(55,18,32,0.45);text-align:center;margin:0;">
    Need help? <a href="mailto:hello@aurachell.com" style="color:#371220;">hello@aurachell.com</a>
</p>

@endsection
