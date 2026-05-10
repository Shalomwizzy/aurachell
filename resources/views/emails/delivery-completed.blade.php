@extends('emails.layouts.base')
@section('subject', 'Your order has been delivered')

@section('content')
<h1>Your order has arrived, {{ $user->name }}.</h1>

<p>We hope your Aurachell piece brings as much joy unwrapped as it did when you chose it. Your space is about to smell incredible.</p>

<hr class="divider">

<p class="label">Order Reference</p>
<p class="value">{{ $order->order_number }}</p>

<div style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.12);padding:24px;margin:28px 0;text-align:center;">
    <p style="font-size:13px;color:rgba(74,222,128,0.8);font-family:Arial,sans-serif;letter-spacing:0.1em;text-transform:uppercase;margin:0;">✓ Delivered</p>
</div>

<p>We'd love to know how you're enjoying your purchase. If you have a moment, leave a review — it helps others discover the Aurachell experience.</p>

<div style="margin-top:32px;text-align:center;">
    <a href="{{ route('account.reviews') }}"
       style="display:inline-block;padding:14px 40px;background:#6B2016;color:#F5EDE4;text-decoration:none;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-family:Arial,sans-serif;">
        Leave a Review
    </a>
</div>

<p style="margin-top:28px;text-align:center;font-size:13px;color:rgba(44,15,10,0.55);">
    Need help? Reply to this email or contact <a href="mailto:hello@aurachell.com" style="color:#C4A48C;text-decoration:none;">hello@aurachell.com</a>
</p>
@endsection
