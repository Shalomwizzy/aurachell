@extends('emails.layouts.base')
@section('subject', 'Your Aurachell has arrived — ' . $order->order_number)
@section('preheader', 'Your order is delivered. Now the ritual begins.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.55);margin:0 0 14px;">
                Delivered
            </p>
            <h1 style="font-family:Georgia,serif;font-size:30px;color:#FAF5ED;font-weight:normal;margin:0 0 10px;">
                Your Aurachell has arrived.
            </h1>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.48);margin:0;">
                Now the ritual begins.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>
    {{ explode(' ', $user->name)[0] }}, your order has been delivered. We hope the unboxing felt as special as the scent inside. Every piece from Aurachell was chosen, crafted, and packed with care — and it's finally yours.
</p>

{{-- Delivery badge --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(212,185,154,0.10);border:1px solid rgba(201,169,111,0.22);padding:20px 28px;margin:24px 0;text-align:center;">
    <tr>
        <td>
            <span class="label">Order Reference</span>
            <p style="font-family:Arial,sans-serif;font-size:16px;font-weight:700;color:#371220;margin:6px 0 0;letter-spacing:0.08em;">{{ $order->order_number }}</p>
        </td>
    </tr>
</table>

<hr class="gold-rule">

{{-- How to use --}}
<h3 style="margin-bottom:16px;">Getting the most from your Aurachell</h3>

@foreach([
    ['Place with intention', 'Position your diffuser where air flows naturally — near a doorway, beside a window, or at nose level on a shelf.'],
    ['Start gently', 'Begin with fewer reeds inserted. You can always add more as you dial in your preferred scent intensity.'],
    ['Flip weekly', 'Turn your reeds once a week to refresh the fragrance throw without overwhelming the room.'],
    ['Keep away from heat', 'Direct sunlight and heat sources will accelerate evaporation. A cool, shaded spot extends your diffuser\'s life significantly.'],
] as [$tip, $desc])
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:16px;">
    <tr>
        <td width="24" style="vertical-align:top;padding-top:3px;">
            <span style="font-family:Arial,sans-serif;font-size:11px;color:#C9A96F;">✦</span>
        </td>
        <td>
            <p style="font-family:Georgia,serif;font-size:14px;color:#1E0C14;margin:0 0 4px;font-weight:600;">{{ $tip }}</p>
            <p style="font-family:Arial,sans-serif;font-size:12px;color:rgba(30,12,20,0.55);margin:0;line-height:1.6;">{{ $desc }}</p>
        </td>
    </tr>
</table>
@endforeach

<hr class="gold-rule">

{{-- Review request --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.04);border-left:2px solid #C9A96F;padding:22px 26px;margin:0 0 28px;">
    <tr>
        <td>
            <h2 style="font-family:Georgia,serif;font-size:17px;color:#371220;font-weight:normal;margin:0 0 10px;">
                Your voice helps other customers find their scent.
            </h2>
            <p style="font-family:Arial,sans-serif;font-size:13px;color:rgba(30,12,20,0.55);margin:0 0 18px;line-height:1.6;">
                If you're enjoying your Aurachell experience, a short review means the world to us — and to the customers who are still searching for the perfect fragrance.
            </p>
            <a href="{{ route('account.reviews') }}" class="btn-ghost" style="font-size:10px;letter-spacing:0.22em;">Leave a Review</a>
        </td>
    </tr>
</table>

{{-- Brand philosophy --}}
<p style="font-family:Georgia,serif;font-size:16px;font-style:italic;color:rgba(55,18,32,0.50);text-align:center;line-height:1.6;margin:0;">
    "Every fragrance is a memory you haven't made yet."
</p>

@endsection
