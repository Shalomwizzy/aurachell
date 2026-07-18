@extends('emails.layouts.base')
@section('subject', 'New pre-order received')
@section('preheader', 'A customer has pre-ordered an out-of-stock product.')

@section('content')
<p class="label">Admin Notification</p>
<h1>New pre-order received</h1>

<p>A customer just pre-ordered a product that is currently <span class="highlight">out of stock</span>. Restock it and reach out to them to complete the sale.</p>

<div style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.15);padding:28px;margin:28px 0;">
    <p class="label">Product</p>
    <p class="value" style="font-size:18px;margin-bottom:4px;">{{ $preorder->product->name }}</p>
    <p style="font-size:13px;color:rgba(55,18,32,0.65);margin:0 0 16px;">SKU: {{ $preorder->product->sku ?? '—' }} · Price: ₦{{ number_format($preorder->product->price, 0) }} · Quantity requested: {{ $preorder->quantity }}</p>

    <p class="label">Customer</p>
    <p style="font-size:14px;color:rgba(55,18,32,0.78);margin:0 0 2px;">{{ $preorder->customer_name }}</p>
    <p style="font-size:13px;color:rgba(55,18,32,0.65);margin:0 0 2px;">{{ $preorder->customer_email }}</p>
    @if($preorder->customer_phone)
    <p style="font-size:13px;color:rgba(55,18,32,0.65);margin:0;">{{ $preorder->customer_phone }}</p>
    @endif

    @if($preorder->note)
    <div style="margin-top:20px;padding-top:20px;border-top:1px solid rgba(55,18,32,0.15);">
        <p class="label">Customer Note</p>
        <p style="font-size:14px;color:rgba(55,18,32,0.75);line-height:1.6;margin:0;">{{ $preorder->note }}</p>
    </div>
    @endif
</div>

<div style="margin-top:36px;text-align:center;">
    <a href="{{ route('admin.preorders.index') }}"
       style="display:inline-block;padding:14px 40px;background:#371220;color:#FAF5ED;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        View Pre-orders
    </a>
</div>
@endsection
