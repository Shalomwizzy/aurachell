@extends('emails.layouts.base')
@section('subject', 'You left something behind')

@section('content')
<h1>Still thinking it over?</h1>

<p>{{ $user->name }}, you left some beautiful pieces in your cart. They're still reserved for you — but not for long.</p>

<hr class="divider">

@foreach($items->take(3) as $item)
<div style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid rgba(255,255,255,0.05);">
    <div>
        <p style="margin:0 0 2px;font-size:14px;color:#1E0C14;">{{ $item->product?->name ?? 'Product' }}</p>
        <p style="margin:0;font-size:12px;color:rgba(30,12,20,0.60);font-family:Arial,sans-serif;">Qty: {{ $item->quantity }}</p>
    </div>
    <p style="margin:0;font-size:14px;color:#C9A96F;">₦{{ number_format(($item->price ?? 0) * $item->quantity) }}</p>
</div>
@endforeach

<div style="margin-top:32px;text-align:center;">
    <a href="{{ route('cart') }}"
       style="display:inline-block;padding:14px 40px;background:#371220;color:#FAF5ED;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        Complete My Order
    </a>
</div>

<p style="margin-top:24px;font-size:13px;text-align:center;color:rgba(30,12,20,0.55);">
    Free delivery on orders over ₦20,000
</p>
@endsection
