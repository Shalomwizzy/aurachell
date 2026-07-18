@extends('emails.layouts.base')
@section('subject', 'Your pre-order has been received')
@section('preheader', 'We have reserved your spot — you will be the first to know when it returns.')

@section('content')
<p class="label">Pre-order Confirmation</p>
<h1>Your pre-order is in.</h1>

<p>Hi {{ $preorder->customer_name }},</p>

<p>Thank you for pre-ordering from Aurachell. <span class="highlight">{{ $preorder->product->name }}</span> is currently out of stock, but your request has been recorded — you are now at the front of the line for the next batch.</p>

<div style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.15);padding:28px;margin:28px 0;">
    <p class="label">Your Pre-order</p>
    <p class="value" style="font-size:18px;margin-bottom:8px;">{{ $preorder->product->name }}</p>
    <p style="font-size:13px;color:rgba(55,18,32,0.65);margin:0 0 4px;">Quantity: {{ $preorder->quantity }}</p>
    <p style="font-size:13px;color:rgba(55,18,32,0.65);margin:0;">Current price: ₦{{ number_format($preorder->product->price, 0) }}</p>

    @if($preorder->note)
    <div style="margin-top:20px;padding-top:20px;border-top:1px solid rgba(55,18,32,0.15);">
        <p class="label">Your Note</p>
        <p style="font-size:14px;color:rgba(55,18,32,0.75);line-height:1.6;margin:0;">{{ $preorder->note }}</p>
    </div>
    @endif
</div>

<p>As soon as it is back in stock, our team will reach out to you at <span class="highlight">{{ $preorder->customer_email }}</span>@if($preorder->customer_phone) or {{ $preorder->customer_phone }}@endif to complete your order. No payment has been taken yet.</p>

<div style="margin-top:36px;text-align:center;">
    <a href="{{ route('shop') }}"
       style="display:inline-block;padding:14px 40px;background:#371220;color:#FAF5ED;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        Continue Shopping
    </a>
</div>

<p style="font-size:12px;color:rgba(55,18,32,0.45);margin-top:36px;text-align:center;">
    Questions? Reply to this email or write to hello@aurachell.com — we're happy to help.
</p>
@endsection
