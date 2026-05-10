@extends('emails.layouts.base')
@section('subject', 'Your wishlist is waiting')

@section('content')
<h1>Your favourites are still here.</h1>

<p>{{ $user->name }}, you saved some beautiful pieces to your Aurachell wishlist. They're still available — and we'd hate for you to miss out.</p>

<hr class="divider">

@foreach($items->take(3) as $wish)
@php $product = $wish->product; @endphp
@if($product)
<div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.05);">
    <div style="flex:1;">
        <p style="margin:0 0 4px;font-size:15px;color:#2C0F0A;">{{ $product->name }}</p>
        <p style="margin:0;font-size:13px;color:#C4A48C;">₦{{ number_format($product->price) }}</p>
        @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
        <p style="margin:4px 0 0;font-size:11px;color:rgba(251,146,60,0.8);font-family:Arial,sans-serif;">Only {{ $product->stock_quantity }} left</p>
        @endif
    </div>
    <a href="{{ route('product.show', $product->slug) }}"
       style="display:inline-block;padding:9px 20px;background:#6B2016;color:#F5EDE4;text-decoration:none;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;font-family:Arial,sans-serif;white-space:nowrap;">
        View
    </a>
</div>
@endif
@endforeach

<div style="margin-top:32px;text-align:center;">
    <a href="{{ route('account.wishlist') }}"
       style="display:inline-block;padding:14px 40px;background:#6B2016;color:#F5EDE4;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        View Full Wishlist
    </a>
</div>
@endsection
