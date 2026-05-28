@extends('layouts.account')
@section('title', 'My Reviews')
@section('account-content')
<h1 class="font-display text-2xl text-text-dark mb-6">My Reviews</h1>

@if(session('success'))
<div class="mb-5 px-4 py-3 text-sm font-sans bg-caramel/10 border border-caramel/30 text-bronze">{{ session('success') }}</div>
@endif

@forelse($reviews as $review)
<div class="bg-white shadow-luxury p-5 mb-4">
    <div class="flex items-start justify-between gap-4 flex-wrap mb-3">
        <div class="flex items-center gap-3">
            @if($review->product)
            <a href="{{ route('product.show', $review->product->slug) }}" class="shrink-0">
                <img src="{{ $review->product->primaryImageUrl }}" alt="{{ $review->product->name }}" class="w-12 h-12 object-cover">
            </a>
            <div>
                <a href="{{ route('product.show', $review->product->slug) }}" class="font-sans text-sm font-medium text-text-dark hover:text-sage transition-colors">{{ $review->product->name }}</a>
                <p class="text-xs text-text-muted font-sans">{{ $review->created_at->format('d M Y') }}</p>
            </div>
            @endif
        </div>
        <span class="text-[10px] tracking-widest uppercase px-2.5 py-1 font-sans {{ $review->is_approved ? 'bg-caramel/15 text-bronze' : 'bg-sand/40 text-text-muted' }}">
            {{ $review->is_approved ? 'Published' : 'Pending' }}
        </span>
    </div>

    <div class="flex items-center gap-0.5 mb-2">
        @for($i = 1; $i <= 5; $i++)
        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-bronze' : 'text-sand/50' }}" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        @endfor
    </div>

    @if($review->title)
    <p class="font-sans font-semibold text-sm text-text-dark mb-1">{{ $review->title }}</p>
    @endif
    <p class="font-sans text-sm text-text-muted leading-relaxed">{{ $review->comment }}</p>
</div>
@empty
<div class="bg-white shadow-luxury p-10 text-center">
    <p class="font-display text-xl text-text-dark mb-3">No reviews yet</p>
    <p class="text-text-muted text-sm mb-6 font-sans">Reviews you submit will appear here once approved.</p>
    <a href="{{ route('shop') }}" class="btn-primary">Shop Now</a>
</div>
@endforelse

{{ $reviews->links() }}
@endsection
