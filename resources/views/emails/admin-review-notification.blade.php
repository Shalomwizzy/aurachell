@extends('emails.layouts.base')
@section('subject', ($isUpdate ? 'Updated' : 'New') . ' Review Submitted')
@section('preheader', $review->rating . '-star review on ' . ($review->product->name ?? 'a product') . ' — awaiting approval')

@section('content')
<span class="eyebrow">Admin Notification</span>
<h1>{{ $isUpdate ? 'Review Updated' : 'New Review Submitted' }}</h1>
<p>A customer {{ $isUpdate ? 'updated their' : 'left a' }} review. It is <span class="tag-gold">Awaiting Approval</span> and won't show on the site until you approve it.</p>

<div class="info-box">
    <div class="label">Product</div>
    <div class="value"><strong class="highlight">{{ $review->product->name ?? '—' }}</strong></div>

    <div class="label">Reviewer</div>
    <div class="value">{{ $review->user->name ?? 'Customer' }}@if($review->user?->email) &mdash; {{ $review->user->email }}@endif</div>

    <div class="label">Rating</div>
    <div class="value" style="font-size:18px;letter-spacing:2px;">
        <span class="gold">{{ str_repeat('★', (int) $review->rating) }}</span><span style="color:rgba(55,18,32,0.25);">{{ str_repeat('★', 5 - (int) $review->rating) }}</span>
        <span style="font-size:13px;color:rgba(55,18,32,0.55);">&nbsp;{{ $review->rating }} / 5</span>
    </div>

    @if($review->title)
    <div class="label">Title</div>
    <div class="value">{{ $review->title }}</div>
    @endif

    <div class="label">Comment</div>
    <div class="value">{{ $review->comment }}</div>
</div>

<hr class="divider">

<div style="text-align:center;">
    <a href="{{ config('app.url') }}/admin/reviews" class="btn">Moderate Reviews</a>
</div>
@endsection
