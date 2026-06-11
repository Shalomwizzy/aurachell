@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')

@section('icon')
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
    <circle cx="11" cy="11" r="8"/>
    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
    <line x1="8" y1="11" x2="14" y2="11" stroke-linecap="round"/>
</svg>
@endsection

@section('heading', 'Page Not Found')

@section('message')
    The page you're looking for has moved, been removed, or never existed.
    Let us guide you back to something beautiful.
@endsection

@section('actions')
    <a href="/" class="btn btn-primary">Go Home</a>
    <a href="/shop" class="btn btn-ghost">Browse Shop</a>
@endsection
