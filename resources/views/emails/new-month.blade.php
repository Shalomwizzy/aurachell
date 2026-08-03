@php
    $logo = \App\Models\Setting::get('logo');
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Happy New Month from Aurachell</title>
</head>
<body style="margin:0;padding:0;background-color:#371220;">

<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">A fresh month, a fresh scent — this month's picks from Aurachell.</div>

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

        {{-- Body --}}
        <tr><td style="background-color:#FAF5ED;padding:40px;">

            <div style="font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:3px;text-transform:uppercase;color:#A9885A;margin-bottom:14px;">{{ $month }}</div>
            <h1 style="font-family:Georgia,'Times New Roman',serif;font-size:27px;font-weight:normal;color:#371220;margin:0 0 16px;line-height:1.25;">Happy New Month, {{ $user->name }}.</h1>
            <p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.75;margin:0 0 8px;">A new month is the perfect moment to refresh your space, reset your rituals, and surround yourself with scents that inspire. Here's what we've curated for you this {{ $month }}.</p>

            @if($featured->count())
            <div style="border-top:1px solid rgba(55,18,32,0.12);margin:28px 0 4px;"></div>
            <div style="font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#A9885A;margin:0 0 6px;">This Month's Highlights</div>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                @foreach($featured as $product)
                <tr>
                    <td style="padding:14px 0;border-bottom:1px solid rgba(55,18,32,0.08);vertical-align:top;">
                        <div style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#371220;margin:0 0 3px;">{{ $product->name }}</div>
                        @if($product->short_description)
                        <div style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#8a7266;line-height:1.5;">{{ Str::limit($product->short_description, 60) }}</div>
                        @endif
                    </td>
                    <td align="right" style="padding:14px 0;border-bottom:1px solid rgba(55,18,32,0.08);vertical-align:top;white-space:nowrap;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#371220;">₦{{ number_format($product->price, 0) }}</td>
                </tr>
                @endforeach
            </table>
            @endif

            {{-- CTA --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:34px 0 0;"><tr><td align="center">
                <a href="{{ route('shop') }}" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">Shop This Month's Picks</a>
            </td></tr></table>

        </td></tr>

        {{-- Footer --}}
        <tr><td align="center" style="background-color:#371220;padding:28px 40px 34px;font-family:Arial,Helvetica,sans-serif;">
            <a href="{{ config('app.url') }}" style="color:#C9A96F;text-decoration:none;font-size:10px;letter-spacing:2px;text-transform:uppercase;">aurachell.com</a>
            <span style="color:rgba(250,245,237,0.30);"> &nbsp;·&nbsp; </span>
            <a href="{{ config('app.url') }}/shop" style="color:#C9A96F;text-decoration:none;font-size:10px;letter-spacing:2px;text-transform:uppercase;">Shop</a>
            <p style="color:rgba(250,245,237,0.45);font-size:11px;line-height:1.6;margin:16px 0 0;">© {{ date('Y') }} Aurachell · Lagos, Nigeria</p>
            <p style="margin:8px 0 0;"><a href="{{ config('app.url') }}/newsletter/unsubscribe" style="color:rgba(250,245,237,0.40);text-decoration:underline;font-size:10px;">Unsubscribe</a></p>
        </td></tr>

    </table>

</td></tr>
</table>

</body>
</html>
