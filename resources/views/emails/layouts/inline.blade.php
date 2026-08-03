@php $emailLogo = \App\Models\Setting::get('logo'); @endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('subject', 'Aurachell')</title>
</head>
<body style="margin:0;padding:0;background-color:#371220;">

<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">@yield('preheader', 'Aurachell — Luxury Home Fragrance, Lagos.')</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#371220;">
<tr><td align="center" style="padding:32px 12px;">

    <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px;max-width:600px;background-color:#FAF5ED;">

        {{-- Header --}}
        <tr><td align="center" style="background-color:#371220;padding:36px 40px 22px;">
            @if($emailLogo)
            <img src="{{ asset('images/' . $emailLogo) }}" alt="Aurachell" width="150" style="display:block;margin:0 auto;max-width:170px;height:auto;border:0;">
            @else
            <div style="font-family:Georgia,'Times New Roman',serif;font-size:23px;letter-spacing:6px;color:#C9A96F;text-transform:uppercase;">Aurachell</div>
            @endif
            <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:3px;color:#B79B78;text-transform:uppercase;margin-top:9px;">Crafted for Calm &nbsp;·&nbsp; Lagos</div>
        </td></tr>
        <tr><td style="background-color:#371220;padding:0 40px 4px;">
            <div style="height:2px;background-color:#C9A96F;font-size:0;line-height:0;">&nbsp;</div>
        </td></tr>

        @hasSection('hero')
        <tr><td>@yield('hero')</td></tr>
        @endif

        {{-- Body --}}
        <tr><td style="background-color:#FAF5ED;padding:40px;">
            @yield('content')
        </td></tr>

        {{-- Footer --}}
        <tr><td align="center" style="background-color:#371220;padding:28px 40px 34px;font-family:Arial,Helvetica,sans-serif;">
            <a href="{{ config('app.url') }}" style="color:#C9A96F;text-decoration:none;font-size:10px;letter-spacing:2px;text-transform:uppercase;">aurachell.com</a>
            <span style="color:rgba(250,245,237,0.30);"> &nbsp;·&nbsp; </span>
            <a href="mailto:hello@aurachell.com" style="color:#C9A96F;text-decoration:none;font-size:10px;letter-spacing:2px;text-transform:uppercase;">hello@aurachell.com</a>
            <p style="color:rgba(250,245,237,0.45);font-size:11px;line-height:1.6;margin:16px 0 0;">© {{ date('Y') }} Aurachell · Lagos, Nigeria</p>
            @yield('footer-extra')
        </td></tr>

    </table>

</td></tr>
</table>

</body>
</html>
