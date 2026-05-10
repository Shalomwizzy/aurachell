<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email — Aurachell</title>
    @php $logo = \App\Models\Setting::get('logo'); $favicon = \App\Models\Setting::get('favicon'); @endphp
    @if($favicon)<link rel="icon" href="{{ asset('images/' . $favicon) }}">@endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin:0; padding:0; background:#1a0a06; font-family: Georgia, 'Times New Roman', serif; min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .page-wrap { width:100%; max-width:480px; margin:0 auto; padding:32px 20px; }
        .card { background:#F5EDE4; padding:48px 40px; box-shadow:0 20px 60px rgba(0,0,0,0.4); }
        .icon-ring { width:72px; height:72px; border-radius:50%; background:rgba(107,32,22,0.12); border:1px solid rgba(107,32,22,0.20); display:flex; align-items:center; justify-content:center; margin:0 auto 28px; }
        .heading { font-size:26px; font-weight:normal; color:#2C0F0A; letter-spacing:-0.02em; margin:0 0 12px; text-align:center; }
        .subtext { font-size:14px; color:rgba(44,15,10,0.65); line-height:1.7; text-align:center; margin:0 0 24px; }
        .email-highlight { color:#6B2016; font-weight:600; font-style:normal; }
        .alert-sent { background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.35); color:#166534; font-size:13px; padding:12px 16px; text-align:center; margin-bottom:24px; }
        .btn-primary { display:block; width:100%; padding:15px; background:#6B2016; color:#F5EDE4; border:none; font-size:11px; letter-spacing:0.22em; text-transform:uppercase; font-family: Arial, sans-serif; font-weight:600; cursor:pointer; transition:background .2s; text-align:center; }
        .btn-primary:hover { background:#4a1510; }
        .divider { border:none; border-top:1px solid rgba(107,32,22,0.12); margin:24px 0; }
        .hint { font-size:12px; color:rgba(44,15,10,0.45); line-height:1.6; text-align:center; margin:0 0 20px; font-family:Arial,sans-serif; }
        .btn-logout { background:none; border:none; font-size:12px; color:rgba(44,15,10,0.45); text-decoration:underline; text-underline-offset:3px; cursor:pointer; font-family:Arial,sans-serif; display:block; margin:0 auto; }
        .btn-logout:hover { color:#6B2016; }
        .logo-block { text-align:center; margin-bottom:32px; }
        .logo-text { font-family:Georgia,serif; font-size:18px; letter-spacing:0.28em; text-transform:uppercase; color:#C4A48C; }
        .footer-note { text-align:center; margin-top:28px; font-size:11px; color:rgba(245,237,228,0.30); font-family:Arial,sans-serif; letter-spacing:0.12em; }
    </style>
</head>
<body>
<div class="page-wrap">

    {{-- Logo --}}
    <div class="logo-block">
        <a href="{{ route('home') }}" style="text-decoration:none;">
            @if($logo)
            <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" style="height:36px;display:inline-block;">
            @else
            <span class="logo-text">Aurachell</span>
            @endif
        </a>
    </div>

    <div class="card">

        {{-- Icon --}}
        <div class="icon-ring">
            <svg width="32" height="32" fill="none" stroke="#6B2016" stroke-width="1.4" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h1 class="heading">Check your inbox</h1>

        @if(session('status') == 'verification-link-sent')
        <div class="alert-sent">
            A fresh verification link has been sent to<br>
            <strong>{{ auth()->user()->email }}</strong>
        </div>
        @elseif(session('status'))
        <div class="alert-sent">{{ session('status') }}</div>
        @else
        <p class="subtext">
            We sent a verification link to<br>
            <em class="email-highlight">{{ auth()->user()->email }}</em><br><br>
            Click the link in that email to activate your account and access the full Aurachell experience.
        </p>
        @endif

        <p class="hint">Didn't receive it? Check your spam folder, or tap below to resend.</p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary">Resend Verification Email</button>
        </form>

        <hr class="divider">

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Sign out and use a different account</button>
        </form>

    </div>

    <p class="footer-note">© {{ date('Y') }} Aurachell. All rights reserved.</p>
</div>
</body>
</html>
