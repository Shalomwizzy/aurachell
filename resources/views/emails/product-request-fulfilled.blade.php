@extends('emails.layouts.base')
@section('subject', 'Great news! Your requested product is now available')

@section('content')
<p class="label">Product Request Update</p>
<h1>Your request has been fulfilled!</h1>

<p>Hi {{ $productRequest->customer_name }},</p>

<p>We have great news — the product you requested is now available at Aurachell. We've worked on making it happen just for you.</p>

<div style="background:rgba(107,32,22,0.08);border:1px solid rgba(107,32,22,0.15);padding:28px;margin:28px 0;">
    <p class="label">Your Request</p>
    <p class="value" style="font-size:18px;margin-bottom:8px;">{{ $productRequest->product_name }}</p>

    @if($productRequest->description)
    <p style="font-size:13px;color:rgba(44,15,10,0.65);margin-top:4px;">{{ $productRequest->description }}</p>
    @endif

    @if($productRequest->admin_note)
    <div style="margin-top:20px;padding-top:20px;border-top:1px solid rgba(107,32,22,0.15);">
        <p class="label">Message from Aurachell</p>
        <p style="font-size:14px;color:rgba(44,15,10,0.75);line-height:1.6;">{{ $productRequest->admin_note }}</p>
    </div>
    @endif
</div>

<p>Head over to our shop to discover it and place your order before it sells out.</p>

<div style="margin-top:36px;text-align:center;">
    <a href="{{ route('shop') }}"
       style="display:inline-block;padding:14px 40px;background:#6B2016;color:#F5EDE4;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        Shop Now
    </a>
</div>

<p style="font-size:12px;color:rgba(44,15,10,0.45);margin-top:36px;text-align:center;">
    Thank you for requesting this — your feedback helps us grow the collection.
</p>
@endsection
