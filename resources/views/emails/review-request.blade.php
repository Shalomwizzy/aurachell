@extends('emails.layouts.base')
@section('subject', 'How is your Aurachell, ' . explode(' ', $user->name)[0] . '?')
@section('preheader', 'Your experience matters. One minute of your time shapes someone else\'s.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#1E0C14;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.55);margin:0 0 14px;">
                Your Review
            </p>
            <h1 style="font-family:Georgia,serif;font-size:30px;color:#FAF5ED;font-weight:normal;margin:0 0 10px;">
                We'd love to hear<br>how it's going.
            </h1>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.48);margin:0;">
                Your honest words help others find their perfect scent.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>
    {{ explode(' ', $user->name)[0] }}, it's been a week since your Aurachell order arrived. By now, your space has had a chance to breathe in something new — and we'd love to know what you think.
</p>

{{-- Order reference --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(212,185,154,0.10);border:1px solid rgba(201,169,111,0.20);padding:20px 28px;margin:24px 0;">
    <tr>
        <td width="50%" style="vertical-align:top;padding-right:12px;">
            <span class="label">Order Reference</span>
            <p style="font-family:Arial,sans-serif;font-size:15px;font-weight:700;color:#371220;margin:0;letter-spacing:0.06em;">{{ $order->order_number }}</p>
        </td>
        <td width="50%" style="vertical-align:top;padding-left:12px;text-align:right;">
            <span class="label">Delivered</span>
            <p style="font-family:Georgia,serif;font-size:13px;color:#1E0C14;margin:0;">{{ $order->delivered_at?->format('d F Y') ?? $order->updated_at->format('d F Y') }}</p>
        </td>
    </tr>
</table>

{{-- Items to review --}}
@if($order->items->count())
<h3 style="margin-bottom:16px;">What you ordered</h3>
@foreach($order->items->take(3) as $item)
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border-bottom:1px solid rgba(55,18,32,0.08);padding:14px 0;margin-bottom:0;">
    <tr>
        <td style="vertical-align:middle;padding:12px 0;">
            <p style="font-family:Georgia,serif;font-size:14px;color:#1E0C14;margin:0 0 3px;">{{ $item->product_name }}</p>
            @if($item->scent_note)
            <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(30,12,20,0.48);margin:0;">Scent: {{ $item->scent_note }}</p>
            @endif
        </td>
    </tr>
</table>
@endforeach
@endif

<hr class="gold-rule">

{{-- Review CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.04);border-left:2px solid #C9A96F;padding:24px 26px;margin:0 0 28px;">
    <tr>
        <td>
            <h2 style="font-family:Georgia,serif;font-size:18px;color:#371220;font-weight:normal;margin:0 0 10px;">
                Your voice helps someone else find their scent.
            </h2>
            <p style="font-family:Arial,sans-serif;font-size:13px;color:rgba(30,12,20,0.55);margin:0 0 20px;line-height:1.6;">
                A few honest words from you can guide the next customer toward exactly the right piece. It takes under a minute — and it means everything to us.
            </p>
            <a href="{{ route('account.reviews') }}" class="btn" style="display:inline-block;">Leave a Review</a>
        </td>
    </tr>
</table>

{{-- Star rating prompt --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="text-align:center;margin:0 0 28px;">
    <tr>
        <td>
            <p style="font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:rgba(30,12,20,0.38);margin:0 0 12px;">
                How would you rate your experience?
            </p>
            @for($i = 1; $i <= 5; $i++)
            <a href="{{ route('account.reviews') }}?order={{ $order->order_number }}&rating={{ $i }}"
               style="display:inline-block;font-size:22px;color:#C9A96F;text-decoration:none;margin:0 3px;">★</a>
            @endfor
        </td>
    </tr>
</table>

<p style="font-family:Arial,sans-serif;font-size:12px;color:rgba(30,12,20,0.40);text-align:center;margin:0;">
    No pressure — if you'd like more time, we understand completely.<br>
    <a href="mailto:hello@aurachell.com" style="color:#371220;">hello@aurachell.com</a> is always open if you have questions.
</p>

@endsection
