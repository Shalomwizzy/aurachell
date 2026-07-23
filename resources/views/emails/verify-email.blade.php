@extends('emails.layouts.base')
@section('subject', 'Verify your Aurachell account')
@section('preheader', 'Confirm your email to activate your Aurachell account — this link expires in 60 minutes.')

@section('content')
<h1 style="text-align:center;">Verify your email address</h1>
<p>Welcome to Aurachell. Before you begin exploring our collection, please confirm your email address by tapping the button below.</p>
<p>This link expires in <strong class="highlight">60 minutes</strong>. If you didn't create an account, you can safely ignore this email.</p>

<div style="text-align:center;margin:36px 0;">
    <a href="{{ $url }}" class="btn">Verify My Email</a>
</div>

<hr class="divider">

<p class="muted">If the button doesn't work, copy and paste this link into your browser:</p>
<div style="background:rgba(55,18,32,0.06);border:1px solid rgba(55,18,32,0.15);padding:14px 16px;word-break:break-all;font-size:12px;color:rgba(55,18,32,0.60);font-family:'Courier New',monospace;line-height:1.5;">{{ $url }}</div>
@endsection
