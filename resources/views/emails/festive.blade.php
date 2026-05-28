@extends('emails.layouts.base')
@section('subject', $greeting . ' from Aurachell')

@section('content')
<div style="text-align:center;margin-bottom:36px;">
    <p style="font-size:13px;letter-spacing:0.3em;text-transform:uppercase;color:#371220;font-family:Arial,sans-serif;margin-bottom:12px;">Aurachell Greetings</p>
    <h1 style="font-size:34px;margin-bottom:16px;">{{ $greeting }},<br>{{ $user->name }}.</h1>
</div>

<p style="text-align:center;font-size:16px;line-height:1.8;color:rgba(55,18,32,0.80);">{{ $message }}</p>

<div style="background:rgba(55,18,32,0.12);border:1px solid rgba(55,18,32,0.2);padding:32px;margin:36px 0;text-align:center;">
    <p style="font-size:13px;letter-spacing:0.15em;text-transform:uppercase;color:#371220;font-family:Arial,sans-serif;margin-bottom:8px;">Festive Gift Sets</p>
    <p style="font-size:15px;color:rgba(55,18,32,0.75);margin-bottom:0;">Curated aromatherapy collections — perfect for gifting or treating yourself this season.</p>
</div>

<div style="margin-top:32px;text-align:center;">
    <a href="{{ route('shop') }}"
       style="display:inline-block;padding:14px 44px;background:#371220;color:#FAF5ED;text-decoration:none;font-size:11px;letter-spacing:0.25em;text-transform:uppercase;font-family:Arial,sans-serif;">
        {{ $ctaText }}
    </a>
</div>

<p style="margin-top:40px;text-align:center;font-size:13px;color:rgba(55,18,32,0.55);">
    From all of us at Aurachell — with love.
</p>
@endsection
