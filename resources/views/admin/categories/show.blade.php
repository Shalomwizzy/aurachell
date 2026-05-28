@extends('layouts.admin')
@section('title', $category->name)

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.categories.index') }}" class="text-text-muted hover:text-cream transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <h1 class="font-display text-2xl text-white">{{ $category->name }}</h1>
    <a href="{{ route('admin.categories.edit', $category) }}" class="ml-auto px-4 py-2 bg-sage text-cream text-xs tracking-widest uppercase hover:bg-sage-800 transition-colors">Edit</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6">
            <h2 class="text-[11px] font-medium text-white tracking-widest uppercase mb-4">Products in this Category</h2>
            <div class="space-y-3">
                @forelse($category->products as $product)
                <div class="flex items-center gap-3 p-3 bg-[rgba(55,18,32,0.10)]">
                    <div class="w-10 h-10 bg-[rgba(55,18,32,0.30)] overflow-hidden">
                        <img src="{{ $product->primary_image_url }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm">{{ $product->name }}</p>
                        <p class="text-text-muted text-xs">₦{{ number_format($product->price, 0) }}</p>
                    </div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-xs text-sage hover:underline">Edit</a>
                </div>
                @empty
                <p class="text-text-muted text-sm">No products in this category.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div>
        <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6 space-y-3 text-sm">
            <h3 class="text-[11px] font-medium text-white tracking-widest uppercase mb-4">Details</h3>
            <div class="flex justify-between"><span class="text-text-muted">Slug</span><span class="text-warmSand-300">/{{ $category->slug }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Status</span><span class="{{ $category->is_active ? 'text-mahogany' : 'text-mahogany' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Parent</span><span class="text-warmSand-300">{{ $category->parent?->name ?? 'None' }}</span></div>
        </div>
    </div>
</div>
@endsection
