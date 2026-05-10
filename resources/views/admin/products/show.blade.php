@extends('layouts.admin')
@section('title', $product->name)

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <h1 class="font-display text-2xl text-white">{{ $product->name }}</h1>
    <a href="{{ route('admin.products.edit', $product) }}" class="ml-auto px-4 py-2 bg-sage text-cream text-xs tracking-widest uppercase hover:bg-sage-800 transition-colors">Edit</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6">
            <div class="grid grid-cols-2 gap-8 mb-6">
                @foreach($product->images->take(4) as $image)
                <div class="aspect-square bg-[#2A2A2A] overflow-hidden">
                    <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            <h2 class="font-display text-xl text-white mb-2">{{ $product->name }}</h2>
            <p class="text-sage font-display text-2xl mb-4">₦{{ number_format($product->price, 0) }}</p>
            <div class="prose prose-sm prose-invert max-w-none text-gray-400">
                {!! $product->description !!}
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6 space-y-3 text-sm">
            <h3 class="text-[11px] font-medium text-white tracking-widest uppercase mb-4">Details</h3>
            <div class="flex justify-between"><span class="text-gray-500">SKU</span><span class="text-gray-300">{{ $product->sku }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Category</span><span class="text-gray-300">{{ $product->category?->name }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Stock</span><span class="{{ $product->stock_quantity <= 5 ? 'text-red-400' : 'text-gray-300' }}">{{ $product->stock_quantity }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Status</span><span class="{{ $product->is_active ? 'text-green-400' : 'text-red-400' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Featured</span><span class="text-gray-300">{{ $product->is_featured ? 'Yes' : 'No' }}</span></div>
            @if($product->capacity_ml)<div class="flex justify-between"><span class="text-gray-500">Capacity</span><span class="text-gray-300">{{ $product->capacity_ml }}ml</span></div>@endif
            @if($product->burn_time_hours)<div class="flex justify-between"><span class="text-gray-500">Burn Time</span><span class="text-gray-300">{{ $product->burn_time_hours }}hrs</span></div>@endif
            <div class="flex justify-between"><span class="text-gray-500">Orders</span><span class="text-gray-300">{{ $product->orderItems->count() }}</span></div>
        </div>

        @if($product->reviews->count())
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6">
            <h3 class="text-[11px] font-medium text-white tracking-widest uppercase mb-4">Reviews ({{ $product->allReviews->count() }})</h3>
            <div class="flex items-baseline gap-2">
                <p class="font-display text-3xl text-sage">{{ $product->average_rating }}</p>
                <p class="text-gray-400 text-sm">/ 5</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
