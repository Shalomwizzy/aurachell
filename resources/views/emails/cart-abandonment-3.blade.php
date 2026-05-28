{{-- Stage 3: Sent ~72h after abandonment. Tone: urgency + exclusivity. Final reminder. --}}
@extends('emails.layouts.base')
@section('subject', 'Your cart expires soon — ' . $items->count() . ' item' . ($items->count() === 1 ? '' : 's') . ' at risk')
@section('preheader', 'We cannot hold these items much longer. This is your final reminder.')

@section('content')

<p class="eyebrow">Final Notice</p>

<h1>Your cart is about to expire.</h1>

<p>
    {{ explode(' ', $user->name)[0] }}, we've been holding your selection — but we can only reserve stock for so long. After this, we can no longer guarantee availability.
</p>

{{-- Urgency box --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;padding:20px 28px;margin:24px 0;">
    <tr>
        <td>
            <p style="font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.22em;text-transform:uppercase;color:rgba(201,169,111,0.70);margin:0 0 6px;">
                Time-sensitive
            </p>
            <p style="font-family:Georgia,serif;font-size:15px;color:#FAF5ED;margin:0;line-height:1.5;">
                Stock is limited. Once these items sell, they may not be available again for weeks.
            </p>
        </td>
    </tr>
</table>

<hr class="divider">

<h3 style="margin-bottom:16px;">Expiring from your cart</h3>

@foreach($items->take(3) as $item)
@if($item->product)
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border:1px solid rgba(55,18,32,0.14);background:#FAF5ED;margin-bottom:10px;">
    <tr>
        @if($item->product->primary_image_url ?? false)
        <td width="80" style="vertical-align:top;padding:0;">
            <img src="{{ $item->product->primary_image_url }}"
                 alt="{{ $item->product->name }}"
                 width="80" height="96"
                 style="display:block;width:80px;height:96px;object-fit:cover;">
        </td>
        @endif
        <td style="padding:14px 16px;vertical-align:middle;">
            <p style="font-family:Georgia,serif;font-size:14px;color:#1E0C14;margin:0 0 4px;line-height:1.3;">
                {{ $item->product->name }}
            </p>
            <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(30,12,20,0.50);margin:0 0 6px;">Qty: {{ $item->quantity }}</p>
            <p style="font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#371220;margin:0;">
                ₦{{ number_format($item->price_at_add * $item->quantity) }}
            </p>
        </td>
        <td style="padding:14px 16px;vertical-align:middle;text-align:right;">
            @if(isset($item->product->stock_quantity) && $item->product->stock_quantity <= 5)
            <span style="display:inline-block;padding:3px 8px;background:rgba(55,18,32,0.10);color:#371220;font-family:Arial,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;">
                Only {{ $item->product->stock_quantity }} left
            </span>
            @else
            <span style="display:inline-block;padding:3px 8px;background:rgba(201,169,111,0.12);color:#8A6B40;font-family:Arial,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;">
                Limited stock
            </span>
            @endif
        </td>
    </tr>
</table>
@endif
@endforeach

@if($items->count() > 3)
<p style="font-size:13px;color:rgba(30,12,20,0.50);text-align:center;margin:8px 0 20px;">
    + {{ $items->count() - 3 }} more item{{ $items->count() - 3 === 1 ? '' : 's' }} expiring
</p>
@endif

{{-- Final CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:28px 0 20px;">
            <a href="{{ route('cart') }}" class="btn-gold">Claim My Order Now</a>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;color:rgba(30,12,20,0.38);margin:0;">
                Free delivery over ₦20,000 &nbsp;·&nbsp; Secure checkout &nbsp;·&nbsp; Easy returns
            </p>
        </td>
    </tr>
</table>

<hr class="divider">

<p style="text-align:center;font-size:13px;color:rgba(30,12,20,0.45);">
    If you've changed your mind, that's okay. You can always <a href="{{ route('shop') }}" style="color:#371220;">browse our full collection</a> whenever you're ready.<br>
    We'll always be here.
</p>

@endsection
