@extends('layouts.app')

@section('title', 'Shop — Luxury Home Diffusers')
@section('meta_description', 'Browse our full collection of luxury ceramic, glass, and travel diffusers with exotic scent blends.')

@section('content')

{{-- Page Header --}}
<div class="bg-sage/5 border-b border-sand/30 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-[10px] text-text-muted mb-4 font-sans tracking-[0.2em] uppercase">
            <a href="{{ route('home') }}" class="hover:text-sage transition-colors">Home</a>
            <span class="text-sand">—</span>
            <span class="text-text-dark">Shop</span>
        </nav>
        <h1 class="font-display text-4xl lg:text-5xl text-text-dark tracking-tight">
            @if(request('q'))
                Results for <em>"{{ request('q') }}"</em>
            @elseif(request('category'))
                {{ $categories->firstWhere('slug', request('category'))?->name ?? 'Shop' }}
            @else
                All Collections
            @endif
        </h1>
        <p class="text-text-muted text-sm mt-2 font-sans">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} available</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-12">

        {{-- Sidebar Filters --}}
        <aside class="lg:w-60 flex-shrink-0" x-data="{ filtersOpen: false }">
            {{-- Mobile toggle --}}
            <button @click="filtersOpen = !filtersOpen"
                    class="lg:hidden w-full flex items-center justify-between py-3.5 px-5 border border-sand/50 mb-5 text-left">
                <span class="font-sans text-xs font-medium uppercase tracking-[0.2em] text-text-dark flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filters
                </span>
                <svg class="w-4 h-4 text-text-muted transition-transform duration-200" :class="filtersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div :class="filtersOpen ? 'block' : 'hidden lg:block'" class="lg:sticky lg:top-24">
                <form method="GET" action="{{ route('shop') }}" id="filter-form">
                    @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif

                    {{-- Category --}}
                    <div class="mb-9">
                        <h3 class="font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-4 pb-3 border-b border-sand/30">Category</h3>
                        <div class="space-y-3">
                            <label class="flex items-center justify-between cursor-pointer group py-1">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} class="accent-sage" onchange="this.form.submit()">
                                    <span class="text-sm text-text-dark group-hover:text-sage transition-colors font-sans">All Products</span>
                                </div>
                            </label>
                            @foreach($categories as $cat)
                            <label class="flex items-center justify-between cursor-pointer group py-1">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'checked' : '' }} class="accent-sage" onchange="this.form.submit()">
                                    <span class="text-sm {{ request('category') === $cat->slug ? 'text-sage font-medium' : 'text-text-dark' }} group-hover:text-sage transition-colors font-sans">{{ $cat->name }}</span>
                                </div>
                                <span class="text-[10px] text-text-muted bg-sand/30 px-2 py-0.5">{{ $cat->products()->active()->count() }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Range --}}
                    <div class="mb-9">
                        <h3 class="font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-4 pb-3 border-b border-sand/30">Price (₦)</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] text-text-muted mb-1.5">Min</label>
                                <input type="number" name="min" value="{{ request('min') }}" placeholder="0"
                                    class="w-full border-b border-sand py-2 text-sm bg-transparent focus:outline-none focus:border-sage transition-colors text-text-dark">
                            </div>
                            <div>
                                <label class="block text-[10px] text-text-muted mb-1.5">Max</label>
                                <input type="number" name="max" value="{{ request('max') }}" placeholder="Any"
                                    class="w-full border-b border-sand py-2 text-sm bg-transparent focus:outline-none focus:border-sage transition-colors text-text-dark">
                            </div>
                        </div>
                    </div>

                    {{-- In Stock --}}
                    <div class="mb-9">
                        <h3 class="font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-4 pb-3 border-b border-sand/30">Availability</h3>
                        <label class="flex items-center gap-3 cursor-pointer group py-1">
                            <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} class="accent-sage w-4 h-4" onchange="this.form.submit()">
                            <span class="text-sm text-text-dark group-hover:text-sage transition-colors font-sans">In Stock Only</span>
                        </label>
                    </div>

                    <div class="space-y-2.5">
                        <button type="submit" class="btn-primary w-full justify-center py-3 text-xs">Apply Filters</button>
                        @if(request()->hasAny(['category','min','max','in_stock']))
                        <a href="{{ route('shop') }}" class="block text-center text-xs text-text-muted py-2 hover:text-sage transition-colors">
                            Clear all filters ×
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </aside>

        {{-- Products Grid --}}
        <div class="flex-1 min-w-0">
            {{-- Toolbar --}}
            <div class="flex items-center justify-between mb-8 gap-4 pb-5 border-b border-sand/30">
                {{-- Active filters display --}}
                <div class="flex flex-wrap gap-2">
                    @if(request('category'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-sage/10 text-sage text-xs font-sans">
                        {{ $categories->firstWhere('slug', request('category'))?->name }}
                        <a href="{{ route('shop', array_filter(['q' => request('q'), 'sort' => request('sort')])) }}" class="hover:text-sage-800">×</a>
                    </span>
                    @endif
                    @if(request('in_stock'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-sage/10 text-sage text-xs font-sans">
                        In Stock
                        <a href="{{ route('shop', array_filter(['q' => request('q'), 'category' => request('category'), 'sort' => request('sort')])) }}" class="hover:text-sage-800">×</a>
                    </span>
                    @endif
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <label class="text-[10px] text-text-muted tracking-widest uppercase hidden sm:block">Sort</label>
                    <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                        class="border-0 border-b border-sand py-1.5 pr-8 text-sm text-text-dark bg-transparent focus:outline-none focus:border-sage transition-colors cursor-pointer appearance-none">
                        <option value="newest" {{ request('sort','newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </div>

            @if($products->isEmpty())
            <div class="text-center py-24">
                <div class="w-16 h-16 rounded-full bg-sand/30 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <p class="font-display text-2xl text-text-dark mb-3">No products found</p>
                <p class="text-text-muted text-sm font-sans mb-8 max-w-xs mx-auto">Try adjusting your filters or browse our full collection.</p>
                <a href="{{ route('shop') }}" class="btn-secondary">Browse All Products</a>
            </div>
            @else

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-7">
                @foreach($products as $product)
                @php $inWishlist = auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                <article class="card-product group relative" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
                    <a href="{{ route('product.show', $product->slug) }}" class="block relative">
                        {{-- Image --}}
                        <div class="relative overflow-hidden aspect-[4/5] bg-sand/20">
                            <img src="{{ $product->primary_image_url }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/400x500/F7F2EB/371220?text=Aurachell'">

                            {{-- Overlay on hover --}}
                            <div class="absolute inset-0 bg-sage/0 group-hover:bg-sage/10 transition-colors duration-500"></div>

                            {{-- Badges --}}
                            <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                                @if($product->compare_at_price && $product->discount_percent)
                                <span class="badge-sage text-[9px] tracking-widest">−{{ $product->discount_percent }}%</span>
                                @endif
                                @if($product->is_featured && !$product->compare_at_price)
                                <span class="badge bg-mahogany text-cream text-[9px] tracking-widest">Featured</span>
                                @endif
                            </div>

                            @if(!$product->isInStock())
                            <div class="absolute inset-0 bg-white/70 backdrop-blur-[1px] flex items-center justify-center">
                                <span class="px-4 py-1.5 bg-white text-text-muted text-[10px] font-sans tracking-widest uppercase border border-sand/50">Sold Out</span>
                            </div>
                            @endif

                            {{-- Quick view on hover --}}
                            <div class="absolute inset-x-0 bottom-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <div class="bg-white/95 backdrop-blur-sm text-center py-2.5 text-xs font-sans tracking-[0.15em] uppercase text-text-dark">
                                    Quick View
                                </div>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="p-4">
                            <p class="font-sans text-[10px] tracking-[0.2em] uppercase mb-1.5" style="color:var(--color-ghost)">{{ $product->category?->name }}</p>
                            <h3 class="font-display text-base lg:text-lg text-text-dark leading-tight mb-1.5 line-clamp-2 group-hover:text-sage transition-colors duration-300">{{ $product->name }}</h3>

                            @if($product->scent_notes)
                            <p class="text-xs text-text-muted mb-3 line-clamp-1 font-sans italic">{{ $product->scent_notes }}</p>
                            @endif

                            <div class="flex items-baseline gap-2.5">
                                <span class="font-display text-lg" style="color:var(--color-primary)">₦{{ number_format($product->price, 0) }}</span>
                                @if($product->compare_at_price)
                                <span class="font-sans text-xs text-text-muted line-through">₦{{ number_format($product->compare_at_price, 0) }}</span>
                                @endif
                            </div>

                            @if($product->reviews->count() > 0)
                            <div class="flex items-center gap-1 mt-2">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= round($product->average_rating) ? 'text-sage' : 'text-sand/30' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                                <span class="text-[10px] text-text-muted ml-0.5">({{ $product->reviews->count() }})</span>
                            </div>
                            @endif
                        </div>
                    </a>

                    @auth
                    <form action="{{ route('account.wishlist.toggle', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
                        @csrf
                        <button type="submit" class="w-7 h-7 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center shadow hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5 {{ $inWishlist ? 'fill-mahogany stroke-mahogany' : 'fill-none stroke-current text-text-muted' }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </form>
                    @endauth

                    {{-- CTA --}}
                    @if($product->isInStock())
                    <div class="px-4 pb-5">
                        <a href="{{ route('product.show', $product->slug) }}" class="btn-secondary w-full text-center text-xs py-2.5">
                            Shop Now
                        </a>
                    </div>
                    @endif
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
            <div class="mt-14 pt-8 border-t border-sand/30 flex justify-center">
                {{ $products->links() }}
            </div>
            @endif
            @endif

        </div>
    </div>
</div>
@endsection
