@php
    $logo  = \App\Models\Setting::get('logo');
    $first = explode(' ', $user->name)[0];
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Happy Birthday from Aurachell</title>
</head>
<body style="margin:0;padding:0;background-color:#371220;">

<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">Today is yours, {{ $first }}. We're celebrating you with something special.</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#371220;">
<tr><td align="center" style="padding:32px 12px;">

    <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px;max-width:600px;background-color:#FAF5ED;">

        {{-- Header --}}
        <tr><td align="center" style="background-color:#371220;padding:36px 40px 22px;">
            @if($logo)
            <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" width="150" style="display:block;margin:0 auto;max-width:170px;height:auto;border:0;">
            @else
            <div style="font-family:Georgia,'Times New Roman',serif;font-size:23px;letter-spacing:6px;color:#C9A96F;text-transform:uppercase;">Aurachell</div>
            @endif
            <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:3px;color:#B79B78;text-transform:uppercase;margin-top:9px;">Crafted for Calm &nbsp;·&nbsp; Lagos</div>
        </td></tr>
        <tr><td style="background-color:#371220;padding:0 40px 4px;">
            <div style="height:2px;background-color:#C9A96F;font-size:0;line-height:0;">&nbsp;</div>
        </td></tr>

        {{-- Hero --}}
        <tr><td align="center" style="background-color:#371220;padding:40px 40px 44px;">
            <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:5px;text-transform:uppercase;color:#B79B78;margin:0 0 18px;">Happy Birthday</div>
            <div style="font-family:Georgia,'Times New Roman',serif;font-size:32px;color:#FAF5ED;line-height:1.2;margin:0 0 14px;">Today belongs to you,<br>{{ $first }}.</div>
            <div style="font-family:Georgia,'Times New Roman',serif;font-size:15px;font-style:italic;color:#C9A96F;line-height:1.6;">From everyone at Aurachell — with warmth.</div>
        </td></tr>

        {{-- Body --}}
        <tr><td style="background-color:#FAF5ED;padding:40px;">

            <p style="font-family:Georgia,'Times New Roman',serif;font-size:16px;font-style:italic;color:#5c4a45;line-height:1.8;margin:0 0 20px;text-align:center;">Birthdays are one of life's rarest permission slips — to be fully, unapologetically celebrated.</p>
            <p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.75;margin:0 0 8px;">We think your space should feel that celebration too — a scent that wraps around the room and lingers long after the candles are blown out.</p>

            {{-- Gift --}}
            @if(isset($couponCode))
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#371220;margin:28px 0;"><tr>
            <td align="center" style="padding:32px 28px;">
                <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:4px;text-transform:uppercase;color:#B79B78;margin:0 0 14px;">Your Birthday Gift</div>
                <div style="font-family:Georgia,'Times New Roman',serif;font-size:14px;color:rgba(250,245,237,0.80);margin:0 0 16px;line-height:1.5;">{{ $discountDescription ?? 'A special discount, just for today.' }}</div>
                <div style="font-family:Arial,Helvetica,sans-serif;font-size:26px;font-weight:bold;color:#C9A96F;letter-spacing:4px;margin:0 0 14px;">{{ $couponCode }}</div>
                <div style="font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#B79B78;letter-spacing:1px;">Valid for {{ $couponDays ?? 7 }} days &nbsp;·&nbsp; Applied at checkout</div>
            </td>
            </tr></table>
            @else
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border:1px solid rgba(201,169,111,0.35);margin:28px 0;"><tr>
            <td align="center" style="padding:28px;">
                <div style="font-family:Georgia,'Times New Roman',serif;font-size:16px;color:#371220;margin:0 0 10px;line-height:1.5;">Our gift to you: treat yourself to something beautiful today.</div>
                <div style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#8a7266;">Browse the full collection and find your next favourite scent.</div>
            </td>
            </tr></table>
            @endif

            {{-- CTA --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:6px 0 0;"><tr><td align="center" style="padding:12px 0 30px;">
                <a href="{{ route('shop') }}" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">Treat Yourself Today</a>
            </td></tr></table>

            <div style="border-top:1px solid rgba(201,169,111,0.45);margin:0 0 28px;"></div>

            {{-- Message --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F4E9DA;border-left:2px solid #371220;margin:0 0 26px;"><tr>
            <td style="padding:22px 26px;">
                <p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;font-style:italic;color:#371220;margin:0 0 10px;line-height:1.7;">May your home always smell like joy, your spaces feel like sanctuary, and your year ahead be everything you deserve.</p>
                <p style="font-family:Arial,Helvetica,sans-serif;font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;margin:0;">With love, The Aurachell Team</p>
            </td>
            </tr></table>

            <p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;font-style:italic;color:#a08a7f;text-align:center;margin:0;">"Every fragrance is a memory you haven't made yet."</p>

        </td></tr>

        {{-- Footer --}}
        <tr><td align="center" style="background-color:#371220;padding:28px 40px 34px;font-family:Arial,Helvetica,sans-serif;">
            <a href="{{ config('app.url') }}" style="color:#C9A96F;text-decoration:none;font-size:10px;letter-spacing:2px;text-transform:uppercase;">aurachell.com</a>
            <span style="color:rgba(250,245,237,0.30);"> &nbsp;·&nbsp; </span>
            <a href="{{ config('app.url') }}/shop" style="color:#C9A96F;text-decoration:none;font-size:10px;letter-spacing:2px;text-transform:uppercase;">Shop</a>
            <p style="color:rgba(250,245,237,0.45);font-size:11px;line-height:1.6;margin:16px 0 0;">© {{ date('Y') }} Aurachell · Lagos, Nigeria</p>
        </td></tr>

    </table>

</td></tr>
</table>

</body>
</html>
