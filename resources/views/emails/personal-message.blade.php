@extends('emails.layouts.base')
@section('subject', $subject)

@section('content')
<h1 style="font-size:24px;color:#371220;margin:0 0 24px;">Hello, {{ $recipientName }}</h1>

<div style="font-size:15px;color:rgba(55,18,32,0.80);line-height:1.75;margin:0 0 24px;">
    {!! nl2br(e($body)) !!}
</div>

@if($couponCode)
<hr class="divider">
<h2 style="font-size:14px;letter-spacing:0.15em;text-transform:uppercase;color:#371220;margin:0 0 14px;">Your Exclusive Gift</h2>
<div style="text-align:center;padding:28px 20px;background:rgba(55,18,32,0.06);border:1px dashed rgba(55,18,32,0.30);margin:0 0 20px;">
    <p style="font-size:11px;letter-spacing:0.2em;text-transform:uppercase;color:rgba(55,18,32,0.60);margin:0 0 10px;font-family:Arial,sans-serif;">Discount Code</p>
    <p style="font-family:monospace;font-size:28px;font-weight:700;letter-spacing:0.15em;color:#371220;margin:0 0 10px;">{{ $couponCode }}</p>
    <p style="font-size:13px;color:rgba(55,18,32,0.55);margin:0;">Apply this code at checkout to redeem your discount.</p>
</div>
@endif

<hr class="divider">
<p style="font-size:14px;color:rgba(55,18,32,0.55);margin:0;">Warm regards,<br><strong style="color:#371220;">The Aurachell Team</strong></p>
@endsection
