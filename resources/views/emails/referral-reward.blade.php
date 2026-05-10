@extends('emails.layouts.base')
@section('subject', 'You earned a reward!')

@section('content')
<h1>You've earned a reward, {{ $referrer->name }}.</h1>

<p>Someone you referred just made their first purchase at Aurachell. As a thank-you, here's an exclusive {{ $rewardPercent }}% discount code — just for you.</p>

<hr class="divider">

<p class="label">Your Reward Code</p>
<div style="background:rgba(107,32,22,0.08);border:2px dashed rgba(107,32,22,0.35);padding:24px;text-align:center;margin:24px 0;border-radius:4px;">
    <span style="font-size:28px;font-weight:700;letter-spacing:0.15em;color:#6B2016;">{{ $couponCode }}</span>
</div>

<p class="label">What you get</p>
<p class="value">{{ $rewardPercent }}% off your next order</p>
<p class="label">Valid for</p>
<p class="value">{{ $validityDays }} days · Single use</p>

<p style="margin-top:24px;">Simply enter the code at checkout. Keep referring friends to keep earning rewards.</p>

<div style="margin-top:40px;text-align:center;">
    <a href="{{ route('shop') }}" class="btn">Shop Now</a>
</div>
@endsection
