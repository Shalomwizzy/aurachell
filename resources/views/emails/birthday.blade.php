@extends('emails.layouts.base')
@section('subject', 'Happy Birthday, ' . explode(' ', $user->name)[0] . ' — a gift from Aurachell')
@section('preheader', 'Today is yours. We\'re celebrating you with something special.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;">
    <tr>
        <td style="padding:52px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.42em;text-transform:uppercase;color:rgba(201,169,111,0.65);margin:0 0 18px;">
                Happy Birthday
            </p>
            <h1 style="font-family:Georgia,serif;font-size:34px;color:#FAF5ED;font-weight:normal;margin:0 0 14px;line-height:1.2;">
                Today belongs to you,<br>{{ explode(' ', $user->name)[0] }}.
            </h1>
            <p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(201,169,111,0.75);margin:0;line-height:1.6;">
                From everyone at Aurachell — with warmth.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p style="font-family:Georgia,serif;font-size:16px;color:rgba(30,12,20,0.75);line-height:1.8;margin:0 0 20px;font-style:italic;text-align:center;">
    Birthdays are one of life's rarest permission slips — to be fully, unapologetically celebrated.
</p>

<p>
    We think your space should feel that celebration too. A scent that wraps around the room, that lingers after every candle has been blown out, that holds the warmth of a day worth remembering.
</p>

{{-- Gift coupon block --}}
@if(isset($couponCode))
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;padding:32px 28px;margin:28px 0;text-align:center;">
    <tr>
        <td>
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.60);margin:0 0 14px;">
                Your Birthday Gift
            </p>
            <p style="font-family:Georgia,serif;font-size:14px;color:rgba(250,245,237,0.75);margin:0 0 16px;line-height:1.5;">
                {{ $discountDescription ?? 'A special discount, just for today.' }}
            </p>
            <p style="font-family:Arial,sans-serif;font-size:26px;font-weight:700;color:#C9A96F;letter-spacing:0.20em;margin:0 0 14px;">
                {{ $couponCode }}
            </p>
            <p style="font-family:Arial,sans-serif;font-size:10px;color:rgba(201,169,111,0.50);margin:0;letter-spacing:0.10em;">
                Valid for {{ $couponDays ?? 7 }} days &nbsp;·&nbsp; Applied at checkout
            </p>
        </td>
    </tr>
</table>
@else
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(212,185,154,0.10);border:1px solid rgba(201,169,111,0.22);padding:28px;margin:28px 0;text-align:center;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:16px;color:#371220;margin:0 0 10px;line-height:1.5;">
                Our gift to you: treat yourself to something beautiful today.
            </p>
            <p style="font-family:Arial,sans-serif;font-size:12px;color:rgba(30,12,20,0.50);margin:0;">
                Browse the full collection and find your next favourite scent.
            </p>
        </td>
    </tr>
</table>
@endif

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:12px 0 32px;">
            <a href="{{ route('shop') }}" class="btn">Treat Yourself Today</a>
        </td>
    </tr>
</table>

<hr class="gold-rule">

{{-- Birthday message --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.04);border-left:2px solid #C9A96F;padding:22px 26px;margin:0 0 28px;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:#371220;margin:0 0 10px;line-height:1.7;">
                May your home always smell like joy, your spaces feel like sanctuary, and your year ahead be everything you deserve.
            </p>
            <p style="font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.18em;text-transform:uppercase;color:rgba(30,12,20,0.40);margin:0;">
                With love, The Aurachell Team
            </p>
        </td>
    </tr>
</table>

<p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(55,18,32,0.48);text-align:center;margin:0;">
    "Every fragrance is a memory you haven't made yet."
</p>

@endsection
