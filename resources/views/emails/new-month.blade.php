@extends('emails.layouts.base')
@section('subject', 'Happy New Month from Aurachell')

@section('content')
<p class="label">{{ $month }}</p>
<h1>Happy New Month,<br>{{ $user->name }}.</h1>

<p>A new month is the perfect opportunity to refresh your space, reset your rituals, and surround yourself with scents that inspire. Here's what we've curated for you this {{ now()->format('F') }}.</p>

<hr class="divider">

@if($featured->count())
<p class="label" style="margin-bottom:16px;">This Month's Highlights</p>
@foreach($featured as $product)
<div style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid rgba(255,255,255,0.05);">
    <div>
        <p style="margin:0 0 3px;font-size:15px;color:#1E0C14;">{{ $product->name }}</p>
        @if($product->short_description)
        <p style="margin:0;font-size:12px;color:rgba(30,12,20,0.60);">{{ Str::limit($product->short_description, 60) }}</p>
        @endif
    </div>
    <p style="margin:0;font-size:14px;color:#C9A96F;white-space:nowrap;margin-left:16px;">₦{{ number_format($product->price) }}</p>
</div>
@endforeach
@endif

<div style="margin-top:36px;text-align:center;">
    <a href="{{ route('shop') }}"
       style="display:inline-block;padding:14px 40px;background:#371220;color:#FAF5ED;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        Shop This Month's Picks
    </a>
</div>
@endsection
