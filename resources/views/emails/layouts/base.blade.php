<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('subject', 'Aurachell')</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        /* ── Reset ── */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; display: block; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; min-width: 100%; }

        /* ── Aurachell Brand Tokens ── */
        /* Maroon: #371220 / Deep: #1E0C14 / Gold: #C9A96F / Sand: #D4B99A / Cream: #FAF5ED */

        body { background-color: #E8D9C8; font-family: Georgia, 'Times New Roman', serif; color: #1E0C14; }

        .outer  { background-color: #E8D9C8; padding: 36px 16px; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #FAF5ED; }

        /* Header */
        .hdr {
            background: #1E0C14;
            padding: 40px 48px 0;
            text-align: center;
        }
        .hdr-logo {
            font-family: Georgia, serif;
            font-size: 24px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: #C9A96F;
            text-decoration: none;
            display: inline-block;
        }
        .hdr-tag {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            letter-spacing: 0.30em;
            text-transform: uppercase;
            color: rgba(250,245,237,0.38);
            display: block;
            margin-top: 7px;
            margin-bottom: 32px;
        }
        .hdr-rule {
            height: 2px;
            background: linear-gradient(to right, transparent, #C9A96F, transparent);
            border: none;
            margin: 0;
        }

        /* Body */
        .body { padding: 52px 48px; background: #FAF5ED; }

        /* Footer */
        .ftr { padding: 36px 48px 40px; background: #1E0C14; text-align: center; }
        .ftr p { color: rgba(250,245,237,0.45) !important; font-size: 11px !important; line-height: 1.7; margin: 0 0 5px; font-family: Arial, Helvetica, sans-serif !important; }
        .ftr a { color: #C9A96F !important; text-decoration: none; }
        .ftr-divider { border: none; border-top: 1px solid rgba(201,169,111,0.15); margin: 20px 0; }
        .social-links { margin: 18px 0 20px; }
        .social-links a { display: inline-block; margin: 0 8px; color: rgba(250,245,237,0.40) !important; font-family: Arial, Helvetica, sans-serif; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; }
        .social-links a:hover { color: #C9A96F !important; }

        /* Typography */
        h1 {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 30px;
            color: #1E0C14;
            font-weight: normal;
            letter-spacing: -0.02em;
            margin: 0 0 20px;
            line-height: 1.20;
        }
        h2 {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 18px;
            color: #371220;
            font-weight: normal;
            margin: 0 0 14px;
            letter-spacing: 0.01em;
        }
        h3 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: rgba(55,18,32,0.60);
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin: 0 0 8px;
        }
        p {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 15px;
            color: rgba(30,12,20,0.72);
            line-height: 1.75;
            margin: 0 0 18px;
        }
        a { color: #371220; }

        .eyebrow {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: #C9A96F;
            display: block;
            margin-bottom: 14px;
        }

        /* Divider */
        .divider { border: none; border-top: 1px solid rgba(55,18,32,0.10); margin: 36px 0; }
        .gold-rule { border: none; border-top: 1px solid rgba(201,169,111,0.40); margin: 36px 0; }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: #371220;
            color: #FAF5ED !important;
            text-decoration: none;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
        }
        .btn:hover { background: #220B14; }
        .btn-ghost {
            display: inline-block;
            padding: 13px 38px;
            background: transparent;
            color: #371220 !important;
            text-decoration: none;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            border: 1px solid rgba(55,18,32,0.35);
        }
        .btn-gold {
            display: inline-block;
            padding: 15px 40px;
            background: #C9A96F;
            color: #1E0C14 !important;
            text-decoration: none;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
        }

        /* Utility */
        .highlight { color: #371220; font-weight: 600; }
        .gold { color: #C9A96F; }
        .muted { color: rgba(30,12,20,0.45); font-size: 13px; }
        .label {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: rgba(55,18,32,0.50);
            margin-bottom: 4px;
            display: block;
        }

        /* Info box */
        .info-box {
            background: rgba(212,185,154,0.15);
            border-left: 2px solid #C9A96F;
            padding: 18px 22px;
            margin: 24px 0;
        }
        .info-box p { font-size: 14px; margin: 0; color: rgba(30,12,20,0.75); }

        /* Pull quote */
        .pull-quote {
            border-left: 2px solid #C9A96F;
            padding: 4px 0 4px 20px;
            margin: 32px 0;
        }
        .pull-quote p {
            font-size: 17px;
            font-style: italic;
            color: #371220;
            margin: 0;
            line-height: 1.55;
        }

        /* Stats band */
        .stats-band {
            background: #371220;
            padding: 24px 32px;
            margin: 32px 0;
            text-align: center;
        }
        .stat-item { display: inline-block; padding: 0 20px; }
        .stat-number { font-size: 22px; color: #C9A96F; font-family: Georgia, serif; display: block; }
        .stat-label { font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(250,245,237,0.45); font-family: Arial, sans-serif; }

        /* Order table */
        .order-table { width: 100%; border-collapse: collapse; }
        .order-table th {
            text-align: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            letter-spacing: 0.20em;
            text-transform: uppercase;
            color: rgba(55,18,32,0.55);
            font-weight: 600;
            padding: 12px 0;
            border-bottom: 1px solid rgba(55,18,32,0.20);
        }
        .order-table td {
            padding: 16px 0;
            border-bottom: 1px solid rgba(55,18,32,0.07);
            font-size: 14px;
            color: rgba(30,12,20,0.78);
            vertical-align: top;
        }
        .order-table .total-row td {
            border-bottom: none;
            border-top: 1px solid rgba(55,18,32,0.20);
            padding-top: 18px;
            color: #1E0C14;
            font-size: 16px;
            font-weight: 600;
        }

        /* Tag / badge */
        .tag {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(55,18,32,0.08);
            color: #371220;
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-weight: 600;
        }
        .tag-gold {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(201,169,111,0.15);
            color: #8A6B40;
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-weight: 600;
        }

        /* Product card */
        .product-card {
            border: 1px solid rgba(55,18,32,0.10);
            background: #FAF5ED;
            overflow: hidden;
        }
        .product-card img { width: 100%; }
        .product-card-body { padding: 16px 18px; }
        .product-card-name { font-size: 15px; color: #1E0C14; margin: 0 0 4px; }
        .product-card-cat { font-size: 10px; color: rgba(30,12,20,0.45); font-family: Arial, sans-serif; letter-spacing: 0.18em; text-transform: uppercase; }
        .product-card-price { font-size: 15px; color: #371220; font-weight: 600; margin-top: 8px; }

        /* Hero band */
        .hero-band {
            background: #371220;
            padding: 44px 48px;
            text-align: center;
        }
        .hero-band h1 { color: #FAF5ED; margin: 0 0 16px; }
        .hero-band p { color: rgba(250,245,237,0.60); margin: 0 0 28px; font-size: 15px; }

        /* Brand footer strip */
        .brand-strip {
            background: #371220;
            padding: 20px 48px;
            text-align: center;
        }
        .brand-strip p {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            letter-spacing: 0.30em;
            text-transform: uppercase;
            color: rgba(201,169,111,0.45);
            margin: 0;
        }

        /* Trust row */
        .trust-row { text-align: center; margin: 28px 0; }
        .trust-item {
            display: inline-block;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(30,12,20,0.45);
            padding: 0 12px;
        }

        /* Mobile */
        @media only screen and (max-width: 620px) {
            .outer { padding: 0 !important; }
            .body, .hdr, .ftr, .hero-band, .brand-strip { padding-left: 24px !important; padding-right: 24px !important; }
            h1 { font-size: 24px !important; }
            .stat-item { display: block !important; margin: 8px 0 !important; }
            .col-half { width: 100% !important; display: block !important; }
        }
    </style>
</head>
<body>

{{-- Preheader (inbox preview text) --}}
<div style="display:none;font-size:1px;max-height:0;overflow:hidden;mso-hide:all;opacity:0;color:transparent;width:0;height:0;">
    @yield('preheader', 'Aurachell — Luxury Home Fragrance from Lagos, Nigeria.')&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
</div>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" class="outer">
<tr><td align="center">

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px;" class="wrapper">

        {{-- Header --}}
        <tr>
            <td class="hdr">
                <a href="{{ config('app.url') }}" class="hdr-logo">Aurachell</a>
                <span class="hdr-tag">Crafted for Calm &nbsp;·&nbsp; Lagos</span>
            </td>
        </tr>
        <tr>
            <td style="background:#1E0C14;padding:0;">
                <div class="hdr-rule"></div>
            </td>
        </tr>

        @hasSection('hero')
        <tr><td>@yield('hero')</td></tr>
        @endif

        {{-- Body --}}
        <tr>
            <td class="body">
                @yield('content')
            </td>
        </tr>

        @hasSection('brand-strip')
        <tr><td>@yield('brand-strip')</td></tr>
        @endif

        {{-- Footer --}}
        <tr>
            <td class="ftr">
                <div class="social-links">
                    <a href="{{ config('app.url') }}">aurachell.com</a>
                    &nbsp;&nbsp;·&nbsp;&nbsp;
                    <a href="mailto:hello@aurachell.com">hello@aurachell.com</a>
                </div>
                <div class="ftr-divider"></div>
                <p>© {{ date('Y') }} Aurachell. All rights reserved.</p>
                <p>Lagos, Nigeria</p>
                @yield('footer-extra')
                <p style="margin-top:16px;">
                    <a href="{{ config('app.url') }}/account">My Account</a>
                    &nbsp;&nbsp;·&nbsp;&nbsp;
                    <a href="{{ config('app.url') }}/shop">Shop</a>
                    &nbsp;&nbsp;·&nbsp;&nbsp;
                    <a href="{{ config('app.url') }}/contact">Contact</a>
                    &nbsp;&nbsp;·&nbsp;&nbsp;
                    <a href="{{ config('app.url') }}/newsletter/unsubscribe">Unsubscribe</a>
                </p>
                <p style="margin-top:12px;font-size:10px;letter-spacing:0.08em;color:rgba(250,245,237,0.22) !important;">
                    You are receiving this email because you have an account with Aurachell.
                </p>
            </td>
        </tr>

    </table>

</td></tr>
</table>

</body>
</html>
