@extends('emails.layouts.base')
@section('subject', 'Welcome to Aurachell')

@section('content')
<h1>Welcome, {{ $user->name }}.</h1>

<p>You've taken the first step into a world of refined scent. Every Aurachell fragrance is crafted to transform your space into a sanctuary.</p>

<hr class="divider">

<p class="label">Your Account</p>
<p class="value">{{ $user->email }}</p>

<p>With your account, you can track orders, save favourites, and discover new collections before anyone else.</p>

<div style="background:rgba(212,184,160,0.18);border:1px solid rgba(196,164,140,0.30);padding:28px;margin:28px 0;">
    @foreach(['Free delivery on orders over ₦20,000','Exclusive member-only collections','Early access to new launches','Personalised scent recommendations'] as $perk)
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
        <span style="color:#C4A48C;font-size:16px;">·</span>
        <span style="font-size:14px;color:rgba(44,15,10,0.70);">{{ $perk }}</span>
    </div>
    @endforeach
</div>

<div style="margin-top:40px;text-align:center;">
    <a href="{{ route('shop') }}" class="btn">Explore the Collection</a>
</div>
@endsection
