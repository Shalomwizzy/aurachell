@extends('errors.layout')

@section('title', 'Session Expired')
@section('code', '419')

@section('icon')
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
    <circle cx="12" cy="12" r="10"/>
    <polyline stroke-linecap="round" stroke-linejoin="round" points="12 6 12 12 16 14"/>
</svg>
@endsection

@section('heading', 'Session Expired')

@section('message')
    Your session has timed out for security reasons. Please go back and try again —
    your cart has been saved.
@endsection

@section('actions')
    <button class="btn btn-primary" onclick="window.history.back()">Go Back</button>
    <a href="/" class="btn btn-ghost">Go Home</a>
@endsection
