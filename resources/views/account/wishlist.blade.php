@extends('layouts.account')
@section('title', 'My Wishlist')
@section('account-content')
<h1 class="font-display text-2xl text-text-dark mb-6">My Wishlist</h1>

@if(session('success'))
<div class="mb-5 px-4 py-3 text-sm font-sans bg-green-50 border border-green-200 text-green-700">{{ session('success') }}</div>
@endif

@forelse($items as $item)
@if($item->product)
<div class="bg-white shadow-luxury p-4 mb-4 flex items-center gap-4">
    <a href="{{ route('product.show', $item->product->slug) }}" class="shrink-0">
        <img src="{{ $item->product->primaryImageUrl }}"
             alt="{{ $item->product->name }}"
             class="w-20 h-20 object-cover">
    </a>
    <div class="flex-1 min-w-0">
        <a href="{{ route('product.show', $item->product->slug) }}" class="font-display text-base text-text-dark hover:text-sage transition-colors line-clamp-2">
            {{ $item->product->name }}
        </a>
        <div class="flex items-center gap-3 mt-1">
            @if($item->product->compare_price && $item->product->compare_price > $item->product->price)
            <span class="font-sans text-xs text-text-muted line-through">₦{{ number_format($item->product->compare_price, 0) }}</span>
            @endif
            <span class="font-sans text-sm font-semibold text-sage">₦{{ number_format($item->product->price, 0) }}</span>
        </div>
        @if(!$item->product->isInStock())
        <p class="text-xs font-sans text-red-500 mt-1">Out of stock</p>
        @endif
    </div>
    <div class="flex flex-col items-end gap-2 shrink-0">
        @if($item->product->isInStock())
        <button onclick="addToCartAjax({{ $item->product->id }}, null, 1, this)"
                class="btn-primary text-xs py-2 px-4">Add to Cart</button>
        @endif
        <form method="POST" action="{{ route('account.wishlist.toggle', $item->product) }}">
            @csrf
            <button type="submit" class="text-xs font-sans text-red-400 hover:text-red-600 transition-colors">Remove</button>
        </form>
    </div>
</div>
@endif
@empty
<div class="bg-white shadow-luxury p-10 text-center">
    <p class="font-display text-xl text-text-dark mb-3">Your wishlist is empty</p>
    <p class="text-text-muted text-sm mb-6 font-sans">Save items you love to find them easily later.</p>
    <a href="{{ route('shop') }}" class="btn-primary">Browse Collection</a>
</div>
@endforelse

{{ $items->links() }}
@endsection
