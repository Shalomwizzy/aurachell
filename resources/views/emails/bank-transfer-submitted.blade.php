@extends('emails.layouts.base')
@section('subject', 'Bank Transfer Proof — #' . $order->order_number)
@section('preheader', 'A customer uploaded proof of payment for order ' . $order->order_number)

@section('content')
<span class="eyebrow">Admin Notification</span>
<h1>Bank Transfer Proof Received</h1>
<p>A customer has uploaded proof of payment. Review it and confirm or reject the transfer.</p>

<div class="info-box">
    <div class="label">Order Number</div>
    <div class="value"><strong class="highlight">{{ $order->order_number }}</strong></div>

    <div class="label">Customer</div>
    <div class="value">{{ $order->user?->name ?? $order->guest_name ?? 'Guest' }}</div>

    <div class="label">Amount</div>
    <div class="value highlight" style="font-size:20px;">₦{{ number_format($order->total, 0) }}</div>
</div>

<hr class="divider">

<div style="text-align:center;">
    <a href="{{ route('admin.bank-transfers.index', ['status' => 'pending']) }}" class="btn">Review Transfer</a>
</div>
@endsection
