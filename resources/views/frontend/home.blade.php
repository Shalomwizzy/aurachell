@extends('layouts.app')

@section('title', 'Aurachell — Crafted for Calm. Designed for Home.')
@section('meta_description', 'Premium luxury home diffusers crafted for calm, elegant living. Shop ceramic, glass, and travel diffusers with exotic scent blends. Free shipping on orders over ₦50,000.')

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative min-h-[92vh] flex items-center justify-center overflow-hidden"
         style="background:var(--color-primary)">
    <div class="absolute inset-0 bg-grain opacity-20"></div>
    <div class="absolute inset-0" style="background:radial-gradient(ellipse at 30% 50%,rgba(212,184,160,0.12) 0%,transparent 60%),radial-gradient(ellipse at 70% 20%,rgba(196,164,140,0.08) 0%,transparent 50%)"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center animate-fade-in">
        <p class="font-sans text-xs tracking-[0.35em] uppercase mb-6" style="color:rgba(212,184,160,0.90)">Luxury Home Fragrance · Nigeria</p>
        <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[1.1] tracking-tight text-balance mb-6" style="color:#F5EDE4">
            Crafted for Calm.<br>
            <em style="color:#C4A48C">Designed for Home.</em>
        </h1>
        <p class="font-sans text-lg max-w-lg mx-auto leading-relaxed mb-10" style="color:rgba(245,237,228,0.82)">
            Each diffuser is a ritual. Each scent, a sanctuary. Discover our collection of handcrafted home diffusers that transform your space into a haven of calm.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop') }}" class="btn-primary px-10 py-4" style="background:#C4A48C;color:#2C0F0A;">
                Shop the Collection
            </a>
            <a href="{{ route('about') }}" class="inline-flex items-center gap-2 font-sans text-sm tracking-widest uppercase transition-colors" style="color:rgba(245,237,228,0.60)">
                Our Story
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce" style="color:rgba(245,237,228,0.3)">
        <span class="font-sans text-[10px] tracking-widest uppercase">Scroll</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
    </div>
</section>

{{-- ===== BRAND STRIP ===== --}}
<div class="py-4 overflow-hidden" style="background:var(--color-primary)">
    <div class="flex items-center gap-12 text-xs tracking-[0.3em] uppercase font-sans text-center" style="color:rgba(245,237,228,0.85)">
        @foreach(['Free Delivery on Orders ₦20k+', 'Handcrafted in Nigeria', '100% Natural Ingredients', '30-Day Returns', 'Secure Paystack Checkout'] as $item)
        <span class="whitespace-nowrap">{{ $item }}</span>
        <span class="w-1 h-1 rounded-full flex-shrink-0" style="background:rgba(245,237,228,0.40)"></span>
        @endforeach
        @foreach(['Free Delivery on Orders ₦20k+', 'Handcrafted in Nigeria', '100% Natural Ingredients'] as $item)
        <span class="whitespace-nowrap">{{ $item }}</span>
        <span class="w-1 h-1 rounded-full flex-shrink-0" style="background:rgba(245,237,228,0.40)"></span>
        @endforeach
    </div>
</div>

{{-- ===== FEATURED CATEGORIES ===== --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-12">
        <p class="font-sans text-xs tracking-[0.3em] uppercase mb-3" style="color:var(--color-accent)">Collections</p>
        <h2 class="section-title">Shop by Category</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($featuredCategories as $cat)
        <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="group relative overflow-hidden aspect-[4/5] block"
           style="background:var(--color-base)">
            @if($cat->image)
            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            @else
            <div class="absolute inset-0 flex items-center justify-center" style="background:linear-gradient(135deg,var(--color-base),var(--color-ghost))">
                <svg class="w-20 h-20 opacity-20" style="color:var(--color-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            @endif
            <div class="absolute inset-0 transition-opacity duration-300" style="background:linear-gradient(to top,rgba(44,15,10,0.82) 0%,rgba(44,15,10,0.2) 60%,transparent 100%)"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <h3 class="font-display text-2xl mb-1" style="color:#F5EDE4">{{ $cat->name }}</h3>
                <p class="text-sm" style="color:rgba(245,237,228,0.65)">{{ $cat->description }}</p>
                <span class="inline-flex items-center gap-1 text-xs tracking-widest uppercase mt-3 font-medium group-hover:gap-2 transition-all" style="color:#C4A48C">
                    Shop Now
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- ===== BESTSELLERS ===== --}}
<section class="py-20" style="background:var(--color-base);background:linear-gradient(180deg,var(--color-surface) 0%,rgba(212,184,160,0.15) 100%)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="font-sans text-xs tracking-[0.3em] uppercase mb-3" style="color:var(--color-accent)">Most Loved</p>
                <h2 class="section-title">Bestsellers</h2>
            </div>
            <a href="{{ route('shop') }}" class="hidden sm:flex btn-ghost text-sm">View All →</a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach($bestsellers->take(8) as $product)
            @php $inWishlist = auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
            <div class="card-product" x-data="{ wishlist: false }">
                <a href="{{ route('product.show', $product->slug) }}" class="block">
                    <div class="relative overflow-hidden aspect-square" style="background:var(--color-base)">
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            loading="lazy"
                            onerror="this.src='https://placehold.co/400x400/D4B8A0/6B2016?text=Aurachell'">
                        @if($product->compare_at_price)
                        <span class="absolute top-3 left-3 badge-primary text-[10px]">Sale</span>
                        @endif
                        @auth
                        <form action="{{ route('account.wishlist.toggle', $product) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            <button type="submit" class="w-7 h-7 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center shadow hover:scale-110 transition-transform">
                                <svg class="w-3.5 h-3.5 {{ $inWishlist ? 'fill-mahogany stroke-mahogany' : 'fill-none stroke-current text-text-muted' }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </button>
                        </form>
                        @endauth
                    </div>
                    <div class="p-4">
                        <p class="font-sans text-[10px] tracking-widest uppercase mb-1" style="color:var(--color-text-muted)">{{ $product->category?->name }}</p>
                        <h3 class="font-display text-base leading-snug mb-2" style="color:var(--color-text-dark)">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-sans font-semibold text-sm" style="color:var(--color-accent)">₦{{ number_format($product->price) }}</span>
                                @if($product->compare_at_price)
                                <span class="text-xs ml-2 line-through" style="color:var(--color-text-muted)">₦{{ number_format($product->compare_at_price) }}</span>
                                @endif
                            </div>
                            @if($product->stock_quantity <= 0)
                            <span class="text-[10px] tracking-wider uppercase" style="color:var(--color-text-muted)">Sold Out</span>
                            @endif
                        </div>
                    </div>
                </a>
                @if($product->stock_quantity > 0)
                <div class="px-4 pb-4"
                     x-data="{ adding: false, added: false }"
                     @cart-reset.window="added = false">
                    <button
                        :disabled="adding"
                        @click="
                            adding = true;
                            window.addToCartAjax({{ $product->id }}, null, 1)
                                .then(() => {
                                    added = true;
                                    window.showToast('Added to cart!');
                                    window.dispatchEvent(new CustomEvent('open-cart'));
                                    setTimeout(() => added = false, 2500);
                                })
                                .catch(e => window.showToast(e.message || 'Could not add to cart', 'error'))
                                .finally(() => adding = false)
                        "
                        class="w-full py-2 text-xs font-sans font-medium tracking-widest uppercase transition-all"
                        :style="added
                            ? 'background:var(--color-primary);color:#F5EDE4;border:1px solid var(--color-primary);'
                            : 'border:1px solid var(--color-accent);color:var(--color-accent);background:transparent;'"
                        x-text="adding ? 'Adding…' : added ? '✓ Added' : 'Add to Cart'">
                        Add to Cart
                    </button>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('shop') }}" class="btn-secondary">View All Products</a>
        </div>
    </div>
</section>

{{-- ===== BRAND STORY STRIP ===== --}}
<section class="py-20" style="background:var(--color-primary)">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <p class="font-sans text-xs tracking-[0.3em] uppercase mb-6" style="color:rgba(212,184,160,0.90)">Our Philosophy</p>
        <h2 class="font-display text-3xl md:text-5xl leading-tight mb-8" style="color:#F5EDE4">
            "Every scent is a story.<br>
            <em style="color:#C4A48C">Every space, a sanctuary."</em>
        </h2>
        <p class="text-base leading-relaxed max-w-2xl mx-auto mb-10" style="color:rgba(245,237,228,0.82)">
            Aurachell was born from a simple belief: your home should feel like a retreat. Our diffusers are handcrafted with premium natural ingredients, designed to transform everyday spaces into moments of calm and luxury.
        </p>
        <a href="{{ route('about') }}" class="btn-primary" style="background:#C4A48C;color:#2C0F0A;">
            Read Our Story
        </a>
    </div>
</section>

{{-- ===== TESTIMONIALS ===== --}}
@if($reviews->count())
<section class="py-20" style="background:var(--color-surface)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="font-sans text-xs tracking-[0.3em] uppercase mb-3" style="color:var(--color-accent)">Customer Love</p>
            <h2 class="section-title">What They're Saying</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews->take(3) as $review)
            <div class="p-6 rounded-sm" style="background:white;box-shadow:var(--shadow-luxury)">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4" style="color:{{ $i < $review->rating ? '#6B2016' : 'rgba(107,32,22,0.20)' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-sm leading-relaxed mb-4" style="color:var(--color-text-muted)">"{{ Str::limit($review->body, 120) }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold" style="background:var(--color-base);color:var(--color-accent)">{{ strtoupper(substr($review->user?->name ?? 'A', 0, 1)) }}</div>
                    <div>
                        <p class="text-sm font-medium" style="color:var(--color-text-dark)">{{ $review->user?->name ?? 'Anonymous' }}</p>
                        <p class="text-xs" style="color:var(--color-text-muted)">Verified Purchase</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== NEWSLETTER ===== --}}
<section class="py-20" style="background:var(--color-surface)">
    <div class="max-w-xl mx-auto px-6 text-center">
        <p class="font-sans text-xs tracking-[0.3em] uppercase mb-4" style="color:var(--color-accent)">Stay in the Know</p>
        <h2 class="font-display text-3xl mb-4" style="color:var(--color-text-dark)">Join the Aurachell Circle</h2>
        <p class="text-sm leading-relaxed mb-8" style="color:var(--color-text-muted)">
            Be the first to know about new collections, exclusive offers, and the stories behind our scents.
        </p>
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex gap-0">
            @csrf
            <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
            </div>
            <input type="email" name="email" placeholder="Your email address" required
                   class="flex-1 px-4 py-3.5 text-sm focus:outline-none"
                   style="border:1px solid var(--color-ghost);border-right:none;background:white;color:var(--color-text-dark)">
            <button type="submit" class="btn-primary px-6 py-3.5 text-xs" style="background:var(--color-primary);flex-shrink:0">
                Subscribe
            </button>
        </form>
        @if(session('newsletter_success'))
        <p class="mt-3 text-sm" style="color:var(--color-accent)">{{ session('newsletter_success') }}</p>
        @endif
    </div>
</section>

{{-- ===== BLOG PREVIEW ===== --}}
@php $latestPosts = \App\Models\BlogPost::where('is_published', true)->latest('published_at')->limit(3)->get(); @endphp
@if($latestPosts->count())
<section class="py-20" style="background:var(--color-surface)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="font-sans text-xs tracking-[0.3em] uppercase mb-3" style="color:var(--color-accent)">The Journal</p>
                <h2 class="section-title">Stories & Rituals</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="hidden sm:flex btn-ghost text-sm">All Posts →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block rounded-sm overflow-hidden" style="background:white;box-shadow:var(--shadow-luxury)">
                @if($post->cover_image)
                <div class="overflow-hidden aspect-video">
                    <img src="{{ asset('images/blog/' . $post->cover_image) }}" alt="{{ $post->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                @else
                <div class="aspect-video flex items-center justify-center" style="background:linear-gradient(135deg,var(--color-base),var(--color-ghost))">
                    <svg class="w-10 h-10 opacity-30" style="color:var(--color-accent)" fill="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h2"/></svg>
                </div>
                @endif
                <div class="p-5">
                    <p class="font-display text-base leading-snug mb-2 transition-colors" style="color:var(--color-text-dark)">{{ $post->title }}</p>
                    <p class="text-xs" style="color:var(--color-text-muted)">{{ $post->reading_time }} min read · {{ $post->published_at?->format('d M Y') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
