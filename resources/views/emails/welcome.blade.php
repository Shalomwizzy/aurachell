@extends('emails.layouts.base')
@section('subject', 'Welcome to Aurachell, ' . $user->name)
@section('preheader', 'Some rooms ask you to stay. Now yours can too.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;">
    <tr>
        <td style="padding:52px 48px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.60);margin:0 0 20px;">
                You're in
            </p>
            <h1 style="font-family:Georgia,serif;font-size:34px;color:#FAF5ED;font-weight:normal;letter-spacing:-0.02em;line-height:1.15;margin:0 0 18px;">
                Welcome to<br>Aurachell.
            </h1>
            <p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(201,169,111,0.70);margin:0;line-height:1.6;">
                Some rooms ask you to stay. Now yours can too.
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:0 48px 0;background:#371220;">
            <div style="height:1px;background:linear-gradient(to right,transparent,rgba(201,169,111,0.35),transparent);"></div>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p style="font-family:Georgia,serif;font-size:15px;color:rgba(30,12,20,0.70);line-height:1.75;margin:0 0 20px;">
    Hello, <strong style="color:#1E0C14;">{{ explode(' ', $user->name)[0] }}</strong>.
</p>

<p>
    Every Aurachell diffuser is crafted by hand in Lagos — using natural ingredients chosen for depth, longevity, and the way they settle into a room and make it feel like home.
</p>

<p>
    We built this brand around a single belief: the scents in your space shape how you feel, think, and rest. You deserve a home that smells as considered as the rest of your life.
</p>

<hr class="gold-rule">

{{-- Member benefits --}}
<h3>Your membership includes</h3>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:28px;">
    @foreach([
        ['Free delivery on orders over ₦20,000', 'On every qualifying order, always.'],
        ['Early access to new collections', 'You hear about launches before anyone else.'],
        ['Exclusive member-only offers', 'Rewards, seasonal surprises, and private sales.'],
        ['Dedicated customer care', 'Real humans who love fragrance, ready to help.'],
    ] as [$perk, $desc])
    <tr>
        <td style="padding:12px 0;border-bottom:1px solid rgba(55,18,32,0.07);vertical-align:top;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="20" style="vertical-align:top;padding-top:3px;">
                        <span style="font-family:Arial,sans-serif;font-size:10px;color:#C9A96F;">✦</span>
                    </td>
                    <td style="vertical-align:top;">
                        <p style="font-family:Georgia,serif;font-size:14px;color:#1E0C14;margin:0 0 3px;font-weight:600;">
                            {{ $perk }}
                        </p>
                        <p style="font-family:Arial,sans-serif;font-size:12px;color:rgba(30,12,20,0.50);margin:0;">
                            {{ $desc }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endforeach
</table>

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:8px 0 36px;">
            <a href="{{ route('shop') }}" class="btn">Explore the Collection</a>
        </td>
    </tr>
</table>

<hr class="gold-rule">

{{-- Philosophy quote --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(212,185,154,0.12);padding:28px 32px;margin:0 0 8px;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:18px;font-style:italic;color:#371220;margin:0 0 14px;line-height:1.5;text-align:center;">
                "Every fragrance is a memory<br>you haven't made yet."
            </p>
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.28em;text-transform:uppercase;color:rgba(55,18,32,0.40);text-align:center;margin:0;">
                Aurachell &nbsp;·&nbsp; Lagos
            </p>
        </td>
    </tr>
</table>

<p style="font-size:13px;color:rgba(30,12,20,0.45);text-align:center;margin:28px 0 0;">
    Questions? Reach us at <a href="mailto:hello@aurachell.com" style="color:#371220;">hello@aurachell.com</a> — we'd love to hear from you.
</p>

@endsection
