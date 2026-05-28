@extends('emails.layouts.base')
@section('subject', 'Reset Your Password — Aurachell')

@section('content')
<h1>Reset Your Password</h1>
<p>We received a request to reset the password for your Aurachell account. Click the button below to choose a new password.</p>

<div style="text-align:center;margin:36px 0;">
    <a href="{{ $url }}" class="btn">Reset Password</a>
</div>

<p style="font-size:13px;color:rgba(55,18,32,0.55);">This link will expire in {{ $expiry }} minutes. If you didn't request a password reset, you can safely ignore this email — your account password will remain unchanged.</p>

<hr class="divider">

<p style="font-size:13px;color:rgba(55,18,32,0.55);">If the button above doesn't work, copy and paste this link into your browser:</p>
<p style="font-size:12px;word-break:break-all;color:#371220;">{{ $url }}</p>
@endsection
