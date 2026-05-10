@extends('emails.layouts.base')
@section('subject', 'New Arrival: ' . $product->name)

@section('content')
<p class="label">New Arrival</p>
<h1>{{ $product->name }}</h1>

<p>{{ $user->name }}, a new fragrance has arrived in the Aurachell collection — and we wanted you to be among the first to discover it.</p>

@if($product->cover_image ?? $product->images->first())
<div style="margin:28px 0;text-align:center;">
    <img src="{{ asset('images/products/' . ($product->cover_image ?? $product->images->first()?->path)) }}"
         alt="{{ $product->name }}"
         style="max-width:100%;border-radius:2px;max-height:300px;object-fit:cover;">
</div>
@endif

@if($product->short_description)
<p>{{ $product->short_description }}</p>
@endif

<div style="background:rgba(107,32,22,0.08);border:1px solid rgba(107,32,22,0.15);padding:28px;margin:28px 0;">
    <p class="label">Price</p>
    <p class="value" style="color:#C4A48C;font-size:22px;">₦{{ number_format($product->price) }}</p>
    @if($product->compare_at_price && $product->compare_at_price > $product->price)
    <p style="font-size:13px;color:rgba(44,15,10,0.55);text-decoration:line-through;margin-top:-10px;">₦{{ number_format($product->compare_at_price) }}</p>
    @endif
    @if($product->stock_quantity > 0)
    <p style="font-size:12px;color:rgba(74,222,128,0.7);font-family:Arial,sans-serif;margin-top:8px;">✓ In stock</p>
    @endif
</div>

<div style="margin-top:36px;text-align:center;">
    <a href="{{ route('product.show', $product->slug) }}"
       style="display:inline-block;padding:14px 40px;background:#6B2016;color:#F5EDE4;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        Shop Now
    </a>
</div>
@endsection
