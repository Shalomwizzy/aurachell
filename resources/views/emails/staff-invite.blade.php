@extends('emails.layouts.base')
@section('subject', 'You\'ve been invited to Aurachell Admin')

@section('content')
<h1>You're invited.</h1>
<p>You've been added as a team member on the Aurachell administration dashboard.</p>

<div style="background:rgba(255,255,255,0.03);border:1px solid rgba(55,18,32,0.12);padding:28px;margin:28px 0;">
    <p class="label">Your Email</p>
    <p class="value">{{ $user->email }}</p>

    <p class="label">Temporary Password</p>
    <p class="value highlight" style="font-family:monospace;letter-spacing:0.1em;font-size:20px;">{{ $tempPassword }}</p>

    <p style="font-size:12px;color:rgba(55,18,32,0.55);font-family:Arial,sans-serif;margin:0;">Please change your password after first login.</p>
</div>

<div style="text-align:center;margin-top:40px;">
    <a href="{{ route('admin.login') }}" class="btn">Access Admin Dashboard</a>
</div>
@endsection
