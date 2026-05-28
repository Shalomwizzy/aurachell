{{-- Stage 2: Sent ~48h after abandonment. Tone: emotional, aspirational, lifestyle-forward --}}
@extends('emails.layouts.base')
@section('subject', 'Some rooms ask you to stay.')
@section('preheader', 'Your cart is still here. And so is the feeling.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Georgia,serif;font-size:19px;font-style:italic;color:rgba(55,18,32,0.75);margin:0 0 14px;line-height:1.5;">
                "A home that smells beautiful<br>is a home that feels loved."
            </p>
            <div style="width:40px;height:1px;background:rgba(55,18,32,0.40);margin:0 auto;"></div>
        </td>
    </tr>
</table>
@endsection

@section('content')

<h1>Your space is waiting.</h1>

<p>
    {{ explode(' ', $user->name)[0] }}, the scents you chose weren't random — they were chosen for a reason. The way a room smells shapes how you feel the moment you walk in. How you sleep. How you think. How you rest.
</p>

<p>
    Your cart is still there. So is everything you were drawn to.
</p>

<hr class="gold-rule">

<h3 style="margin-bottom:16px;">What you left behind</h3>

@foreach($items->take(3) as $item)
@if($item->product)
@include('emails.partials.product-card', [
    'product'  => $item->product,
    'qty'      => $item->quantity,
    'price'    => $item->price_at_add * $item->quantity,
    'showBtn'  => false,
])
@endif
@endforeach

@if($items->count() > 3)
<p style="font-size:13px;color:rgba(55,18,32,0.50);text-align:center;margin:4px 0 20px;">
    + {{ $items->count() - 3 }} more item{{ $items->count() - 3 === 1 ? '' : 's' }}
</p>
@endif

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:28px 0;">
            <a href="{{ route('cart') }}" class="btn">Bring It Home</a>
        </td>
    </tr>
</table>

<hr class="divider">

{{-- Social proof --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.12);padding:28px 32px;margin:0 0 28px;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(55,18,32,0.70);margin:0 0 16px;line-height:1.65;text-align:center;">
                "My home smells incredible. Every guest asks what candle I'm burning.<br>It's not a candle — it's Aurachell."
            </p>
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.22em;text-transform:uppercase;color:rgba(55,18,32,0.40);text-align:center;margin:0;">
                Chisom A. &nbsp;·&nbsp; Verified Customer
            </p>
        </td>
    </tr>
</table>

<p style="text-align:center;font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;color:rgba(55,18,32,0.40);">
    3,000+ customers have transformed their spaces with Aurachell.
</p>

@endsection
