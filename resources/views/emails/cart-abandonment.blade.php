{{-- Stage 1: Sent ~24h after abandonment. Tone: gentle, no-pressure --}}
@extends('emails.layouts.base')
@section('subject', 'Your cart is saving your spot, ' . explode(' ', $user->name)[0])
@section('preheader', 'The pieces you chose are still here — just waiting.')

@section('content')

<p class="eyebrow">Your cart</p>

<h1>Still thinking it over?</h1>

<p>
    No rush at all, {{ explode(' ', $user->name)[0] }}. The pieces you added are still in your cart, patiently waiting. We just wanted to make sure you didn't lose them.
</p>

<hr class="divider">

{{-- Cart items --}}
<h3 style="margin-bottom:16px;">Items in your cart</h3>

@foreach($items->take(3) as $item)
@if($item->product)
@include('emails.partials.product-card', [
    'product' => $item->product,
    'qty'     => $item->quantity,
    'price'   => $item->price_at_add * $item->quantity,
])
@else
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border:1px solid rgba(55,18,32,0.10);padding:16px 18px;margin-bottom:12px;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:14px;color:#1E0C14;margin:0 0 4px;">Item no longer available</p>
            <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(30,12,20,0.50);margin:0;">Qty: {{ $item->quantity }}</p>
        </td>
        <td style="text-align:right;vertical-align:middle;">
            <p style="font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#371220;margin:0;">₦{{ number_format($item->price_at_add * $item->quantity) }}</p>
        </td>
    </tr>
</table>
@endif
@endforeach

@if($items->count() > 3)
<p style="font-size:13px;color:rgba(30,12,20,0.50);text-align:center;margin:8px 0 20px;">
    + {{ $items->count() - 3 }} more item{{ $items->count() - 3 === 1 ? '' : 's' }} in your cart
</p>
@endif

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:24px 0 28px;">
            <a href="{{ route('cart') }}" class="btn">Return to My Cart</a>
        </td>
    </tr>
</table>

<hr class="divider">

{{-- Trust signals --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        @foreach(['Free delivery over ₦20k', 'Easy 30-day returns', 'Secure Paystack checkout'] as $trust)
        <td style="text-align:center;padding:0 8px;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.18em;text-transform:uppercase;color:rgba(30,12,20,0.40);margin:0;">
                {{ $trust }}
            </p>
        </td>
        @endforeach
    </tr>
</table>

@endsection
