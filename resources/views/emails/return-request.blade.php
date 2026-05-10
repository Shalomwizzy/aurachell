@extends('emails.layouts.base')
@section('subject', $statusLabel)

@section('content')
<h1>{{ $statusLabel }}</h1>

<p>{{ $statusMessage }}</p>

<hr class="divider">

<p class="label">Order</p>
<p class="value">{{ $returnRequest->order->order_number }}</p>
<p class="label">Return Reason</p>
<p class="value">{{ $returnRequest->reason }}</p>
@if($returnRequest->details)
<p class="label">Additional Details</p>
<p class="value">{{ $returnRequest->details }}</p>
@endif
<p class="label">Request Status</p>
<p class="value">{{ $returnRequest->statusLabel() }}</p>

@if($returnRequest->admin_notes)
<hr class="divider">
<p class="label">Message from Us</p>
<p>{{ $returnRequest->admin_notes }}</p>
@endif

@if($event === 'approved')
<hr class="divider">
<p><strong>Next steps:</strong> Please contact us to arrange the return shipment. Once we receive and inspect the item, we will process your refund within 3–5 business days.</p>
@endif

@if($event === 'submitted')
<p style="margin-top:16px;">You can track the status of your request from your account page.</p>
@endif

<div style="margin-top:40px;text-align:center;">
    <a href="{{ route('account.order.detail', $returnRequest->order->order_number) }}" class="btn">View Order</a>
</div>
@endsection
