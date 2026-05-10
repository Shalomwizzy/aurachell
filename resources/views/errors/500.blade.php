@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')

@section('icon')
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
</svg>
@endsection

@section('heading', 'Something Went Wrong')

@section('message')
    Our servers encountered an unexpected error. Our team has been notified and
    we're working to fix it. Please try again in a moment.
@endsection

@section('actions')
    <a href="/" class="btn btn-primary">Go Home</a>
    <button class="btn btn-ghost" onclick="window.history.back()">Go Back</button>
@endsection
