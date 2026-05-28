@extends('emails.layouts.base')
@section('subject', $subject)

@section('content')
@if($recipientName)
<p>Dear {{ $recipientName }},</p>
@endif

{!! nl2br(e($emailBody)) !!}

<div style="margin-top:36px;text-align:center;">
    <a href="{{ route('shop') }}" class="btn">Shop Now</a>
</div>

<p style="font-size:12px;color:rgba(55,18,32,0.40);margin-top:32px;text-align:center;">
    You received this email because you subscribed to Aurachell updates.<br>
    <a href="{{ route('home') }}" style="color:rgba(55,18,32,0.60);">Unsubscribe</a>
</p>
@endsection
