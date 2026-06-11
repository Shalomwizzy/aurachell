@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('code', '429')

@section('icon')
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>
@endsection

@section('heading', 'Slow Down a Little')

@section('message')
    You've made too many requests in a short time. Please wait a moment and try again.
@endsection

@section('actions')
    <button class="btn btn-primary" onclick="setTimeout(() => window.location.reload(), 5000); this.textContent='Retrying in 5s…'; this.disabled=true;">
        Retry
    </button>
    <a href="/" class="btn btn-ghost">Go Home</a>
@endsection
