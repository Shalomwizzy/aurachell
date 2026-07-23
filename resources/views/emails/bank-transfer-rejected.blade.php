@extends('emails.layouts.base')
@section('subject', 'Payment Issue — #' . $order->order_number)
@section('preheader', 'We could not confirm your bank transfer for order ' . $order->order_number . '.')

@section('content')
<h1>We couldn't confirm your payment</h1>
<p>Unfortunately we were unable to confirm your bank transfer for order <strong class="highlight">{{ $order->order_number }}</strong>.</p>

@if($adminNote)
<div class="info-box">
    <div class="label">Reason</div>
    <div class="value">{{ $adminNote }}</div>
</div>
@endif

<p>If you believe this is an error, or you'd like to arrange an alternative payment, please contact us at <a href="mailto:{{ config('mail.from.address') }}" style="color:#8A6B3F;">{{ config('mail.from.address') }}</a> and we'll be glad to help.</p>
@endsection
