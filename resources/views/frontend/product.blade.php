@extends('layouts.app')

@section('title', $product->name . ' — Aurachell')
@section('meta_description', $product->short_description ?? Str::limit(strip_tags($product->description), 155))
@section('og_title', $product->name . ' — Aurachell')
@section('og_image', $product->primary_image_url)

@push('styles')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ addslashes($product->name) }}",
    "description": "{{ addslashes($product->short_description ?? Str::limit(strip_tags($product->description ?? ''), 200)) }}",
    "image": "{{ $product->primary_image_url }}",
    "sku": "{{ $product->sku }}",
    "brand": { "@type": "Brand", "name": "Aurachell" },
    "offers": {
        "@type": "Offer",
        "url": "{{ route('product.show', $product->slug) }}",
        "priceCurrency": "NGN",
        "price": "{{ $product->price }}",
        "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
    }@if($product->reviews_count > 0),
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $product->average_rating }}",
        "reviewCount": "{{ $product->reviews_count }}"
    }@endif
}
</script>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="border-b border-sand/30 bg-white/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex items-center gap-2 text-[10px] text-text-muted font-sans tracking-[0.2em] uppercase">
            <a href="{{ route('home') }}" class="hover:text-sage transition-colors">Home</a>
            <span class="text-sand">—</span>
            <a href="{{ route('shop') }}" class="hover:text-sage transition-colors">Shop</a>
            @if($product->category)
            <span class="text-sand">—</span>
            <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="hover:text-sage transition-colors">{{ $product->category->name }}</a>
            @endif
            <span class="text-sand">—</span>
            <span class="text-text-dark truncate max-w-[180px]">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16" id="product-page">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20">

        {{-- ===== IMAGE GALLERY ===== --}}
        <div class="space-y-4">
            {{-- Main image --}}
            <div class="relative aspect-[4/5] bg-sand/10 overflow-hidden">
                <img id="main-product-image"
                     src="{{ $product->primary_image_url }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover transition-opacity duration-300"
                     onerror="this.src='https://placehold.co/600x750/F7F2EB/371220?text=Aurachell'">

                {{-- Badges --}}
                <div class="absolute top-4 left-4 flex flex-col gap-2">
                    @if($product->compare_at_price && $product->discount_percent)
                    <span class="badge-sage">{{ $product->discount_percent }}% Off</span>
                    @endif
                    @if($product->is_featured)
                    <span class="badge-sage">Bestseller</span>
                    @endif
                </div>

                @if(!$product->isInStock())
                <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center">
                    <span class="px-6 py-2 bg-white text-text-dark text-xs font-sans tracking-widest uppercase border border-sand">Sold Out</span>
                </div>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
            <div class="grid grid-cols-4 gap-2.5" id="product-thumbnails">
                @foreach($product->images as $image)
                <button onclick="setProductImage('{{ $image->url }}', this)"
                    class="product-thumb aspect-square bg-sand/10 overflow-hidden border-2 transition-all duration-200 {{ $image->is_primary ? 'border-sage opacity-100' : 'border-transparent opacity-70 hover:opacity-100' }}">
                    <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ===== PRODUCT INFO ===== --}}
        <div class="flex flex-col lg:py-4">

            {{-- Category label --}}
            <p class="font-sans text-[10px] tracking-[0.3em] uppercase text-mahogany mb-3">{{ $product->category?->name }}</p>

            {{-- Name --}}
            <h1 class="font-display text-3xl lg:text-4xl xl:text-5xl text-text-dark leading-tight tracking-tight mb-4">
                {{ $product->name }}
            </h1>

            {{-- Rating --}}
            @if($product->reviews->count() > 0)
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'text-mahogany' : 'text-warmSand-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-xs text-text-muted font-sans">{{ $product->average_rating }} / 5</span>
                <a href="#reviews" class="text-xs text-sage hover:underline underline-offset-4 transition-colors font-sans">
                    {{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}
                </a>
            </div>
            @endif

            {{-- Divider --}}
            <div class="h-px bg-sand/30 mb-5"></div>

            {{-- Price --}}
            <div class="flex items-baseline gap-4 mb-5">
                <span class="font-display text-4xl tracking-tight" style="color:var(--color-primary)">₦{{ number_format($product->price, 0) }}</span>
                @if($product->compare_at_price)
                <span class="font-sans text-lg text-text-muted line-through">₦{{ number_format($product->compare_at_price, 0) }}</span>
                <span class="font-sans text-sm text-mahogany font-medium">Save ₦{{ number_format($product->compare_at_price - $product->price, 0) }}</span>
                @endif
            </div>

            {{-- Short description --}}
            @if($product->short_description)
            <p class="font-sans text-sm text-text-muted leading-relaxed mb-5">{{ $product->short_description }}</p>
            @endif

            {{-- Scent Notes (descriptive — what this product contains) --}}
            @if($product->scent_notes)
            <div class="mb-5">
                <p class="font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-2.5">Scent Notes</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $product->scent_notes) as $note)
                    @php $n = trim($note) @endphp
                    @if($n !== '')
                    <span class="px-3 py-1.5 border border-sand/50 bg-sand/30 text-text-dark text-xs font-sans">{{ $n }}</span>
                    @endif
                    @endforeach
                </div>
                <p class="text-[11px] text-text-muted font-sans mt-2 italic">These are the fragrance notes contained in this product.</p>
            </div>
            @endif

            {{-- Product Details --}}
            <div class="flex flex-wrap gap-x-8 gap-y-2 text-xs font-sans mb-6 py-4 border-y border-sand/30">
                @if($product->capacity_ml)
                <div>
                    <span class="text-text-muted uppercase tracking-widest text-[10px]">Volume</span>
                    <p class="text-text-dark font-medium mt-0.5">{{ $product->capacity_ml }}ml</p>
                </div>
                @endif
                @if($product->burn_time_hours)
                <div>
                    <span class="text-text-muted uppercase tracking-widest text-[10px]">Duration</span>
                    <p class="text-text-dark font-medium mt-0.5">~{{ $product->burn_time_hours }} hours</p>
                </div>
                @endif
                <div>
                    <span class="text-text-muted uppercase tracking-widest text-[10px]">Availability</span>
                    <p class="font-medium mt-0.5 {{ $product->isInStock() ? 'text-mahogany' : 'text-mahogany' }}">
                        {{ $product->isInStock() ? 'In Stock' : 'Out of Stock' }}
                        @if($product->isInStock() && $product->isLowStock())
                        <span class="text-mahogany"> · Only {{ $product->stock_quantity }} left</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Variants --}}
            @if($product->variants->count() > 0)
            <div class="mb-6">
                <h4 class="font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-3">Choose Option</h4>
                <div class="flex flex-wrap gap-2" id="variant-options">
                    @foreach($product->variants as $variant)
                    <button
                        onclick="selectVariant({{ $variant->id }}, this)"
                        class="variant-btn px-5 py-2.5 border text-sm font-sans transition-all duration-200 border-sand text-text-dark hover:border-sage/50"
                        data-variant="{{ $variant->id }}"
                    >{{ $variant->name }}</button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quantity + Add to Cart --}}
            @if($product->isInStock())
            <div class="flex items-stretch gap-3 mb-4">
                {{-- Qty stepper --}}
                <div class="flex items-center border border-sand/70 bg-white">
                    <button type="button" onclick="changeQty(-1)"
                            class="w-11 h-12 flex items-center justify-center text-text-muted hover:text-text-dark hover:bg-sand/10 transition-colors text-lg font-light">
                        −
                    </button>
                    <span id="product-qty" class="w-10 text-center text-sm font-sans text-text-dark border-x border-sand/50">1</span>
                    <button type="button" onclick="changeQty(1)"
                            class="w-11 h-12 flex items-center justify-center text-text-muted hover:text-text-dark hover:bg-sand/10 transition-colors text-lg font-light">
                        +
                    </button>
                </div>

                {{-- Add to cart --}}
                <button id="add-to-cart-btn"
                        type="button"
                        onclick="productAddToCart({{ $product->id }})"
                        class="btn-primary flex-1 h-12 text-sm tracking-widest relative">
                    <span id="atc-label">Add to Cart</span>
                    <span id="atc-spinner" class="absolute inset-0 flex items-center justify-center gap-2" style="display:none;">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Adding...
                    </span>
                </button>
            </div>
            @else
            <button disabled class="btn-primary w-full opacity-50 cursor-not-allowed mb-4">Out of Stock</button>
            @endif

            {{-- Wishlist --}}
            @auth
            @php $inWishlist = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
            <form action="{{ route('account.wishlist.toggle', $product) }}" method="POST" class="mb-6">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-sm transition-colors font-sans group {{ $inWishlist ? 'text-mahogany' : 'text-text-muted hover:text-sage' }}">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform {{ $inWishlist ? 'fill-mahogany stroke-mahogany' : 'fill-none stroke-current' }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    {{ $inWishlist ? 'Remove from Wishlist' : 'Save to Wishlist' }}
                </button>
            </form>
            @else
            <div class="mb-6">
                <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm text-text-muted hover:text-sage transition-colors font-sans group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Sign in to save to Wishlist
                </a>
            </div>
            @endauth

            {{-- Trust badges --}}
            <div class="grid grid-cols-3 gap-4 py-6 border-t border-sand/30 mb-6">
                @foreach([
                    ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'label' => 'Free Shipping', 'sub' => 'On orders over ₦50K'],
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'label' => '100% Authentic', 'sub' => 'Certified natural'],
                    ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'label' => 'Easy Returns', 'sub' => '14-day policy'],
                ] as $badge)
                <div class="text-center">
                    <div class="w-8 h-8 mx-auto mb-2" style="color:var(--color-ghost)">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $badge['icon'] }}"/></svg>
                    </div>
                    <p class="text-[10px] font-medium text-text-dark tracking-wide">{{ $badge['label'] }}</p>
                    <p class="text-[9px] text-text-muted mt-0.5">{{ $badge['sub'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Accordion --}}
            <div class="divide-y divide-sand/30">
                @foreach([
                    ['key' => 'description', 'label' => 'Description'],
                    ['key' => 'usage', 'label' => 'How to Use'],
                    ['key' => 'shipping', 'label' => 'Shipping & Returns'],
                ] as $section)
                <div>
                    <button type="button"
                        onclick="toggleAccordion('acc-{{ $section['key'] }}', this)"
                        class="w-full flex items-center justify-between py-4 text-left group"
                    >
                        <span class="font-sans text-sm font-medium text-text-dark group-hover:text-sage transition-colors tracking-wide">{{ $section['label'] }}</span>
                        <svg class="acc-chevron w-4 h-4 text-text-muted flex-shrink-0 transition-transform duration-300{{ $section['key'] === 'description' ? ' rotate-180' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="acc-{{ $section['key'] }}"
                         class="pb-5 text-sm text-text-muted leading-relaxed prose prose-sm max-w-none"
                         style="{{ $section['key'] === 'description' ? '' : 'display:none;' }}">
                        @if($section['key'] === 'description')
                            {!! $product->description !!}
                        @elseif($section['key'] === 'usage')
                            <p>Fill your diffuser with water to the max line. Add 5–10 drops of essential oil or use a pre-scented cartridge. Turn on and enjoy up to {{ $product->burn_time_hours ?? 40 }} hours of continuous fragrance. Clean monthly with a damp cloth.</p>
                        @else
                            <p>Free shipping on orders over ₦50,000. Standard delivery: 3–5 business days (Lagos), 5–7 days (other states). Returns accepted within 14 days for unused items in original packaging. Contact us at hello@aurachell.com to initiate a return.</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- ===== REVIEWS ===== --}}
    <div id="reviews" class="mt-24 pt-12 border-t border-sand/30">

        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="font-sans text-[10px] tracking-[0.3em] uppercase text-mahogany mb-2">Customer Experiences</p>
                <h2 class="font-display text-3xl text-text-dark">Reviews</h2>
            </div>
            @if($product->reviews->count() > 0)
            <div class="text-right hidden sm:block">
                <p class="font-display text-4xl" style="color:var(--color-primary)">{{ $product->average_rating }}</p>
                <div class="flex items-center gap-0.5 justify-end mt-1">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-3.5 h-3.5 {{ $i <= round($product->average_rating) ? 'text-mahogany' : 'text-warmSand-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-xs text-text-muted mt-1">{{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}</p>
            </div>
            @endif
        </div>

        @if($product->reviews->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-14">
            @foreach($product->reviews as $review)
            <div class="bg-white p-7 shadow-luxury hover:shadow-luxury-lg transition-shadow duration-300">
                <div class="flex items-center gap-0.5 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-mahogany' : 'text-warmSand-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                @if($review->title)
                <h4 class="font-display text-base text-text-dark mb-2">{{ $review->title }}</h4>
                @endif
                <p class="text-sm text-text-muted leading-relaxed mb-5 font-sans">"{{ $review->comment }}"</p>
                <div class="flex items-center gap-3 pt-4 border-t border-sand/20">
                    <div class="w-8 h-8 rounded-full bg-sage/15 flex items-center justify-center text-sage text-sm font-display font-semibold flex-shrink-0">
                        {{ strtoupper(substr($review->user?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-sans text-sm font-medium text-text-dark leading-none">{{ $review->user?->name ?? 'Verified Buyer' }}</p>
                        <p class="font-sans text-[10px] text-text-muted mt-0.5">{{ $review->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-sand/10 p-10 text-center mb-12">
            <p class="font-display text-xl text-text-dark mb-2">No reviews yet</p>
            <p class="text-text-muted text-sm font-sans">Be the first to share your experience with this product.</p>
        </div>
        @endif

        {{-- Write review --}}
        @auth
        <div class="bg-white shadow-luxury p-8 lg:p-10 max-w-2xl">
            <h3 class="font-display text-2xl text-text-dark mb-6">Write a Review</h3>
            <form action="{{ route('account.reviews.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div>
                    <label class="block text-[10px] tracking-[0.2em] uppercase text-text-muted mb-3">Your Rating</label>
                    <input type="hidden" name="rating" id="review-rating-input" value="5">
                    <div class="flex items-center gap-1" id="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                onclick="setReviewRating({{ $i }})"
                                onmouseenter="highlightStars({{ $i }})"
                                onmouseleave="highlightStars(document.getElementById('review-rating-input').value)"
                                class="transition-transform hover:scale-110"
                                data-star="{{ $i }}">
                            <svg class="w-7 h-7 transition-colors {{ $i <= 5 ? 'text-mahogany' : 'text-warmSand-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                        @endfor
                        <span id="review-rating-label" class="ml-3 text-sm text-text-muted font-sans">Excellent</span>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] tracking-[0.2em] uppercase text-text-muted mb-3">Review Title</label>
                    <input type="text" name="title" placeholder="Sum it up in a few words" class="input-luxury">
                </div>

                <div>
                    <label class="block text-[10px] tracking-[0.2em] uppercase text-text-muted mb-3">Your Review</label>
                    <textarea name="comment" rows="5" placeholder="Describe your experience with this product..." required
                        class="w-full border-b border-sand bg-transparent py-3 text-text-dark placeholder-text-muted focus:outline-none focus:border-sage transition-colors resize-none text-sm leading-relaxed"></textarea>
                </div>

                <button type="submit" class="btn-primary">Publish Review</button>
            </form>
        </div>
        @else
        <div class="border border-sand/50 p-6 text-center max-w-md">
            <p class="text-sm text-text-muted font-sans mb-4">Sign in to leave a review</p>
            <a href="{{ route('login') }}" class="btn-secondary text-xs">Sign In</a>
        </div>
        @endauth
    </div>

    {{-- ===== RELATED PRODUCTS ===== --}}
    @if($related->count() > 0)
    <div class="mt-24 pt-12 border-t border-sand/30">
        <div class="text-center mb-10">
            <p class="font-sans text-[10px] tracking-[0.3em] uppercase text-mahogany mb-2">You Might Also Love</p>
            <h2 class="font-display text-3xl text-text-dark">Complete Your Sanctuary</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach($related as $p)
            <a href="{{ route('product.show', $p->slug) }}" class="card-product group block">
                <div class="aspect-[4/5] bg-sand/10 overflow-hidden">
                    <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]"
                         loading="lazy"
                         onerror="this.src='https://placehold.co/400x500/F7F2EB/371220?text=Aurachell'">
                </div>
                <div class="p-4">
                    <p class="text-[10px] text-mahogany uppercase tracking-widest font-sans mb-1">{{ $p->category?->name }}</p>
                    <h3 class="font-display text-base text-text-dark line-clamp-2 mb-2 group-hover:text-sage transition-colors">{{ $p->name }}</h3>
                    <span class="font-display text-lg" style="color:var(--color-primary)">₦{{ number_format($p->price, 0) }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
(function() {
    /* ── State ───────────────────────────────────────────── */
    var _qty = 1;
    var _adding = false;
    var _selectedVariant = null;

    /* ── Image Gallery ───────────────────────────────────── */
    window.setProductImage = function(src, thumbEl) {
        var img = document.getElementById('main-product-image');
        if (img) { img.style.opacity = '0.7'; img.src = src; img.onload = function() { img.style.opacity = '1'; }; }
        document.querySelectorAll('.product-thumb').forEach(function(t) {
            t.classList.remove('border-sage', 'opacity-100');
            t.classList.add('border-transparent', 'opacity-70');
        });
        if (thumbEl) {
            thumbEl.classList.remove('border-transparent', 'opacity-70');
            thumbEl.classList.add('border-sage', 'opacity-100');
        }
    };

    /* ── Variant selector ────────────────────────────────── */
    window.selectVariant = function(variantId, btn) {
        _selectedVariant = variantId;
        document.querySelectorAll('.variant-btn').forEach(function(b) {
            b.classList.remove('border-sage', 'bg-sage', 'text-cream', 'shadow-sm');
            b.classList.add('border-sand', 'text-text-dark');
        });
        btn.classList.add('border-sage', 'bg-sage', 'text-cream', 'shadow-sm');
        btn.classList.remove('border-sand', 'text-text-dark');
    };

    /* ── Qty stepper ─────────────────────────────────────── */
    window.changeQty = function(delta) {
        _qty = Math.max(1, _qty + delta);
        var el = document.getElementById('product-qty');
        if (el) el.textContent = _qty;
    };

    /* ── Add to cart ─────────────────────────────────────── */
    window.productAddToCart = function(productId) {
        if (_adding) return;
        _adding = true;
        var btn = document.getElementById('add-to-cart-btn');
        var label = document.getElementById('atc-label');
        var spinner = document.getElementById('atc-spinner');
        if (btn) btn.disabled = true;
        if (label) label.style.display = 'none';
        if (spinner) spinner.style.display = 'flex';

        (window.addToCartAjax
            ? window.addToCartAjax(productId, _selectedVariant, _qty)
            : Promise.reject(new Error('Cart not ready'))
        ).then(function() {
            if (label) { label.textContent = '✓ Added!'; label.style.display = 'block'; }
            if (window.showToast) window.showToast('Added to cart!');
            window.dispatchEvent(new CustomEvent('open-cart'));
            setTimeout(function() {
                if (label) { label.textContent = 'Add to Cart'; }
            }, 2500);
        }).catch(function(e) {
            if (window.showToast) window.showToast(e.message || 'Could not add to cart', 'error');
            if (label) label.style.display = 'block';
        }).finally(function() {
            _adding = false;
            if (btn) btn.disabled = false;
            if (spinner) spinner.style.display = 'none';
        });
    };

    /* ── Accordion ───────────────────────────────────────── */
    window.toggleAccordion = function(id, btnEl) {
        var panel = document.getElementById(id);
        if (!panel) return;
        var isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        var chevron = btnEl ? btnEl.querySelector('.acc-chevron') : null;
        if (chevron) chevron.classList.toggle('rotate-180', !isOpen);
    };

    /* ── Review stars ────────────────────────────────────── */
    var ratingLabels = ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent'];
    window.highlightStars = function(n) {
        n = parseInt(n);
        document.querySelectorAll('#review-stars [data-star]').forEach(function(btn) {
            var star = parseInt(btn.dataset.star);
            var svg = btn.querySelector('svg');
            if (svg) { svg.className = svg.className.replace(/text-\S+/g, '') + (star <= n ? ' text-mahogany' : ' text-warmSand-300'); }
        });
        var lbl = document.getElementById('review-rating-label');
        if (lbl) lbl.textContent = ratingLabels[n] || '';
    };
    window.setReviewRating = function(n) {
        var input = document.getElementById('review-rating-input');
        if (input) input.value = n;
        highlightStars(n);
    };

    /* ── FB Pixel ────────────────────────────────────────── */
    if (typeof fbq !== 'undefined') {
        fbq('track', 'ViewContent', {
            content_name: '{{ addslashes($product->name) }}',
            content_ids: ['{{ $product->id }}'],
            content_type: 'product',
            value: {{ (float) $product->price }},
            currency: 'NGN'
        });
    }
})();
</script>
@endpush
@endsection
