@extends('layouts.app')

@section('title', 'Aurachell — Some rooms ask you to stay.')
@section('meta_description', 'Luxury handcrafted home diffusers. Natural ingredients. Premium craft. Made in Nigeria. Scents that transform everyday spaces into moments of calm.')

@push('styles')
<style>
@keyframes ticker {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}
@keyframes scrollLine {
    0%   { transform: translateY(-100%); opacity: 0; }
    20%  { opacity: 1; }
    80%  { opacity: 1; }
    100% { transform: translateY(250%); opacity: 0; }
}
.hero-ticker-track {
    display: flex;
    width: max-content;
    animation: ticker 50s linear infinite;
}
.hero-ticker-track:hover { animation-play-state: paused; }
.scroll-indicator-line {
    position: absolute; top: 0; left: 0;
    width: 100%; height: 45%;
    background: var(--color-ghost);
    animation: scrollLine 2.8s ease-in-out infinite;
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════════
     HERO — Some rooms ask you to stay.
═══════════════════════════════════════════════════════════════ --}}
<section class="relative min-h-screen flex items-center overflow-x-hidden" style="background:var(--color-primary)">

    {{-- Grain texture --}}
    <div class="absolute inset-0 bg-grain opacity-25 pointer-events-none"></div>

    {{-- Atmospheric light --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background:radial-gradient(ellipse at 10% 65%,rgba(201,169,111,0.11) 0%,transparent 50%),
                radial-gradient(ellipse at 90% 10%,rgba(212,185,154,0.06) 0%,transparent 45%)"></div>

    {{-- Vertical accent rule — desktop only --}}
    <div class="absolute top-0 right-0 w-px h-full hidden lg:block pointer-events-none"
         style="background:linear-gradient(to bottom,transparent 0%,rgba(201,169,111,0.18) 30%,rgba(201,169,111,0.18) 70%,transparent 100%)"></div>

    {{-- Main content --}}
    <div class="relative z-10 w-full max-w-7xl mx-auto px-5 sm:px-8 lg:px-10
                py-28 sm:py-36
                lg:py-0 lg:min-h-screen lg:flex lg:items-center">

        {{-- ── Left: copy ── --}}
        <div class="w-full lg:w-[54%] lg:pr-16">

            {{-- Eyebrow --}}
            <div class="flex items-center gap-3 mb-10 lg:mb-14">
                <div class="w-8 h-px flex-shrink-0" style="background:var(--color-ghost)"></div>
                <span class="font-sans text-[10px] tracking-[0.4em] uppercase leading-none"
                      style="color:var(--color-ghost)">Luxury Home Fragrance&nbsp;&nbsp;·&nbsp;&nbsp;Nigeria</span>
            </div>

            {{-- Headline --}}
            <h1 class="font-display tracking-tight mb-8 lg:mb-10"
                style="font-size:clamp(3rem,8vw,6.5rem);line-height:0.95;color:var(--color-surface)">
                Some rooms<br>
                ask you<br>
                <em class="not-italic" style="color:var(--color-ghost)">to stay.</em>
            </h1>

            {{-- Pull quote --}}
            <p class="font-display italic mb-6 lg:mb-8"
               style="font-size:clamp(0.95rem,1.6vw,1.15rem);
                      color:rgba(201,169,111,0.60);
                      letter-spacing:0.01em;
                      line-height:1.6">
                — Handcrafted diffusers for the spaces<br class="hidden sm:block"> you love most.
            </p>

            {{-- Brand proof --}}
            <p class="font-sans text-xs tracking-[0.22em] mb-12 lg:mb-16"
               style="color:rgba(250,245,237,0.35)">
                Natural ingredients&nbsp;&nbsp;·&nbsp;&nbsp;Premium craft&nbsp;&nbsp;·&nbsp;&nbsp;Made in Nigeria
            </p>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-10">
                <a href="{{ route('shop') }}"
                   class="inline-flex items-center gap-3 font-sans text-xs tracking-[0.28em] uppercase font-semibold
                          px-8 py-4 transition-all duration-300 hover:opacity-85 active:scale-95 flex-shrink-0"
                   style="background:var(--color-ghost);color:var(--color-text-dark)">
                    Explore the Collection
                </a>
                <a href="{{ route('about') }}"
                   class="inline-flex items-center gap-2 font-sans text-xs tracking-[0.22em] uppercase
                          transition-all duration-300 group"
                   style="color:rgba(250,245,237,0.40)">
                    Our Philosophy
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-300"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

        </div>

        {{-- ── Right: decorative composition — desktop only ── --}}
        <div class="hidden lg:flex lg:w-[46%] items-center justify-center relative" aria-hidden="true">
            <div class="relative w-[400px] h-[400px] flex items-center justify-center">

                {{-- Rings --}}
                <div class="absolute inset-0 rounded-full"
                     style="border:1px solid rgba(201,169,111,0.12)"></div>
                <div class="absolute inset-[48px] rounded-full"
                     style="border:1px solid rgba(201,169,111,0.07)"></div>

                {{-- Core glow --}}
                <div class="w-40 h-40 rounded-full"
                     style="background:radial-gradient(circle,rgba(201,169,111,0.09) 0%,transparent 70%)"></div>

                {{-- Giant background letter --}}
                <span class="absolute font-display pointer-events-none select-none"
                      style="font-size:21rem;color:rgba(201,169,111,0.04);
                             line-height:1;top:50%;left:50%;
                             transform:translate(-50%,-50%)">A</span>

                {{-- Accent dots --}}
                <div class="absolute top-8 right-14 w-2 h-2 rounded-full"
                     style="background:rgba(201,169,111,0.28)"></div>
                <div class="absolute bottom-16 left-6 w-1 h-1 rounded-full"
                     style="background:rgba(201,169,111,0.20)"></div>

                {{-- Vertical accent --}}
                <div class="absolute top-1/3 left-2 w-px h-14"
                     style="background:linear-gradient(to bottom,transparent,rgba(201,169,111,0.22),transparent)"></div>

                {{-- Floating info chips --}}
                <div class="absolute -right-6 top-[38%] px-4 py-3 text-left"
                     style="background:rgba(212,185,154,0.06);
                            border:1px solid rgba(201,169,111,0.16);
                            backdrop-filter:blur(8px)">
                    <p class="font-sans text-[9px] tracking-[0.28em] uppercase mb-1"
                       style="color:rgba(201,169,111,0.50)">Signature Blend</p>
                    <p class="font-display text-sm" style="color:rgba(250,245,237,0.82)">Oud &amp; Amber</p>
                </div>
                <div class="absolute -left-8 bottom-[28%] px-3 py-2"
                     style="background:rgba(212,185,154,0.06);
                            border:1px solid rgba(201,169,111,0.12)">
                    <p class="font-sans text-[9px] tracking-[0.22em] uppercase"
                       style="color:rgba(201,169,111,0.45)">Est. Lagos · 2022</p>
                </div>

            </div>
        </div>

    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-3"
         aria-hidden="true">
        <span class="font-sans text-[9px] tracking-[0.45em] uppercase"
              style="color:rgba(250,245,237,0.18)">Scroll</span>
        <div class="w-px h-14 relative overflow-hidden"
             style="background:rgba(250,245,237,0.08)">
            <div class="scroll-indicator-line"></div>
        </div>
    </div>

</section>

{{-- ═══════════════════════════════════════════════════════════════
     TICKER STRIP
═══════════════════════════════════════════════════════════════ --}}
<div class="overflow-hidden py-4 select-none"
     style="background:rgba(30,12,20,0.97);
            border-top:1px solid rgba(201,169,111,0.14);
            border-bottom:1px solid rgba(201,169,111,0.14)">
    @php
        $tickerItems = [
            'Handcrafted in Nigeria',
            'Natural Ingredients',
            'Free Delivery over ₦20k',
            '30-Day Returns',
            'Secure Paystack Checkout',
            'Luxury Home Fragrance',
            'Premium Diffusers',
        ];
    @endphp
    <div class="hero-ticker-track">
        @foreach(array_merge($tickerItems, $tickerItems) as $item)
        <span class="font-sans text-[10px] tracking-[0.32em] uppercase mx-6 whitespace-nowrap"
              style="color:rgba(201,169,111,0.60)">{{ $item }}</span>
        <span class="text-[5px] flex-shrink-0 self-center mx-2"
              style="color:rgba(201,169,111,0.25)">◆</span>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     FIND YOUR ATMOSPHERE — Collections
═══════════════════════════════════════════════════════════════ --}}
<section class="py-24 lg:py-32 overflow-hidden" style="background:var(--color-surface)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section head --}}
        <div class="flex items-end justify-between mb-12 lg:mb-16 gap-4 flex-wrap">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-6 h-px flex-shrink-0" style="background:var(--color-ghost)"></div>
                    <span class="font-sans text-[10px] tracking-[0.35em] uppercase"
                          style="color:var(--color-ghost)">Collections</span>
                </div>
                <h2 class="font-display leading-tight"
                    style="font-size:clamp(1.8rem,4vw,3rem);color:var(--color-text-dark)">
                    Find Your<br>Atmosphere
                </h2>
            </div>
            <a href="{{ route('shop') }}"
               class="flex items-center gap-2 font-sans text-xs tracking-[0.22em] uppercase
                      transition-opacity hover:opacity-50 group flex-shrink-0"
               style="color:var(--color-text-muted)">
                All Collections
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-300"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        {{-- Editorial asymmetric grid --}}
        @if($featuredCategories->count() >= 2)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">

            {{-- Large card --}}
            <a href="{{ route('shop', ['category' => $featuredCategories->first()->slug]) }}"
               class="group relative overflow-hidden block md:row-span-2 min-h-[320px] md:min-h-[580px]"
               style="background:var(--color-base)">
                @if($featuredCategories->first()->image)
                <img src="{{ $featuredCategories->first()->image_url }}"
                     alt="{{ $featuredCategories->first()->name }}"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                @else
                <div class="absolute inset-0 flex items-center justify-center"
                     style="background:linear-gradient(160deg,var(--color-primary) 0%,rgba(201,169,111,0.15) 100%)">
                    <span class="font-display select-none pointer-events-none"
                          style="font-size:10rem;color:rgba(250,245,237,0.04)">A</span>
                </div>
                @endif
                <div class="absolute inset-0 transition-opacity duration-500"
                     style="background:linear-gradient(to top,rgba(30,12,20,0.88) 0%,rgba(30,12,20,0.15) 55%,transparent 100%)"></div>
                <div class="absolute inset-x-0 bottom-0 p-7 lg:p-10">
                    <p class="font-sans text-[9px] tracking-[0.35em] uppercase mb-2"
                       style="color:rgba(201,169,111,0.65)">Collection</p>
                    <h3 class="font-display text-3xl lg:text-4xl mb-3"
                        style="color:#FAF5ED">{{ $featuredCategories->first()->name }}</h3>
                    @if($featuredCategories->first()->description)
                    <p class="font-sans text-sm mb-4 max-w-xs leading-relaxed"
                       style="color:rgba(250,245,237,0.55)">{{ Str::limit($featuredCategories->first()->description, 80) }}</p>
                    @endif
                    <div class="flex items-center gap-2 font-sans text-xs tracking-[0.22em] uppercase
                                group-hover:gap-4 transition-all duration-300"
                         style="color:var(--color-ghost)">
                        Shop Now
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Smaller cards --}}
            @foreach($featuredCategories->skip(1)->take(2) as $cat)
            <a href="{{ route('shop', ['category' => $cat->slug]) }}"
               class="group relative overflow-hidden block min-h-[200px] md:min-h-[280px]"
               style="background:var(--color-base)">
                @if($cat->image)
                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                @else
                <div class="absolute inset-0 flex items-center justify-center"
                     style="background:linear-gradient(160deg,rgba(55,18,32,0.85) 0%,rgba(201,169,111,0.18) 100%)">
                    <span class="font-display select-none pointer-events-none"
                          style="font-size:7rem;color:rgba(250,245,237,0.04)">A</span>
                </div>
                @endif
                <div class="absolute inset-0"
                     style="background:linear-gradient(to top,rgba(30,12,20,0.82) 0%,rgba(30,12,20,0.08) 60%,transparent 100%)"></div>
                <div class="absolute inset-x-0 bottom-0 p-6">
                    <p class="font-sans text-[9px] tracking-[0.3em] uppercase mb-1.5"
                       style="color:rgba(201,169,111,0.60)">Collection</p>
                    <h3 class="font-display text-xl lg:text-2xl mb-3"
                        style="color:#FAF5ED">{{ $cat->name }}</h3>
                    <div class="flex items-center gap-2 font-sans text-xs tracking-[0.2em] uppercase
                                group-hover:gap-3 transition-all duration-300"
                         style="color:var(--color-ghost)">
                        Shop Now
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach

        </div>

        @else
        {{-- Fallback: single-column or one category --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($featuredCategories as $cat)
            <a href="{{ route('shop', ['category' => $cat->slug]) }}"
               class="group relative overflow-hidden block min-h-[280px]"
               style="background:var(--color-base)">
                @if($cat->image)
                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                @else
                <div class="absolute inset-0"
                     style="background:linear-gradient(160deg,var(--color-primary) 0%,rgba(201,169,111,0.2) 100%)"></div>
                @endif
                <div class="absolute inset-0"
                     style="background:linear-gradient(to top,rgba(30,12,20,0.85) 0%,transparent 60%)"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h3 class="font-display text-2xl mb-3" style="color:#FAF5ED">{{ $cat->name }}</h3>
                    <div class="flex items-center gap-2 font-sans text-xs tracking-widest uppercase
                                group-hover:gap-3 transition-all duration-300"
                         style="color:var(--color-ghost)">
                        Shop Now
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     THE RITUALS — Bestsellers
     ⚠  Wishlist forms are OUTSIDE the <a> tag to avoid nested
     interactive elements and the cancel/submit bug.
═══════════════════════════════════════════════════════════════ --}}
<section class="py-24 lg:py-32 overflow-hidden"
         style="background:var(--color-surface);
                border-top:1px solid rgba(201,169,111,0.12)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section head --}}
        <div class="flex items-end justify-between mb-12 lg:mb-16 gap-4 flex-wrap">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-6 h-px flex-shrink-0" style="background:var(--color-ghost)"></div>
                    <span class="font-sans text-[10px] tracking-[0.35em] uppercase"
                          style="color:var(--color-ghost)">Most Loved</span>
                </div>
                <h2 class="font-display leading-tight"
                    style="font-size:clamp(1.8rem,4vw,3rem);color:var(--color-text-dark)">
                    The Rituals
                </h2>
            </div>
            <a href="{{ route('shop') }}"
               class="flex items-center gap-2 font-sans text-xs tracking-[0.22em] uppercase
                      transition-opacity hover:opacity-50 group flex-shrink-0"
               style="color:var(--color-text-muted)">
                View All
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-300"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        {{-- Product grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach($bestsellers->take(8) as $product)
            @php $inWishlist = auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp

            {{-- Card wrapper: relative so wishlist button can overlay --}}
            <div class="group relative min-w-0"
                 x-data="{ adding: false, added: false }"
                 @cart-reset.window="added = false">

                {{-- ✅ Wishlist form OUTSIDE <a>, positioned absolutely on the card --}}
                @auth
                <form action="{{ route('account.wishlist.toggle', $product) }}"
                      method="POST"
                      class="absolute top-3 right-3 z-10">
                    @csrf
                    <button type="submit"
                            class="w-8 h-8 rounded-full flex items-center justify-center
                                   shadow-sm transition-transform duration-200 hover:scale-110 active:scale-95"
                            style="background:rgba(250,245,237,0.92);backdrop-filter:blur(4px)"
                            title="{{ $inWishlist ? 'Remove from wishlist' : 'Save to wishlist' }}">
                        <svg class="w-4 h-4 {{ $inWishlist ? 'fill-mahogany stroke-mahogany' : 'fill-none stroke-current' }}"
                             style="{{ $inWishlist ? '' : 'color:var(--color-text-muted)' }}"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </form>
                @endauth

                {{-- Product link (image + info only) --}}
                <a href="{{ route('product.show', $product->slug) }}" class="block min-w-0">

                    {{-- Image container — 3:4 portrait --}}
                    <div class="relative overflow-hidden mb-4" style="aspect-ratio:3/4;background:var(--color-base)">
                        <img src="{{ $product->primary_image_url }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]"
                             loading="lazy"
                             onerror="this.src='https://placehold.co/400x533/D4B99A/371220?text=Aurachell'">

                        @if($product->compare_at_price)
                        <span class="absolute top-3 left-3 font-sans text-[9px] tracking-[0.2em] uppercase
                                     px-2 py-1 font-semibold"
                              style="background:var(--color-primary);color:var(--color-ghost)">Sale</span>
                        @endif

                        @unless($product->isInStock())
                        <div class="absolute inset-0 flex items-center justify-center"
                             style="background:rgba(250,245,237,0.80)">
                            <span class="font-sans text-[10px] tracking-[0.3em] uppercase"
                                  style="color:var(--color-text-muted)">Sold Out</span>
                        </div>
                        @endunless
                    </div>

                    {{-- Product info --}}
                    <div class="min-w-0 px-0.5">
                        <p class="font-sans text-[9px] tracking-[0.28em] uppercase mb-1.5 truncate"
                           style="color:var(--color-text-muted)">{{ $product->category?->name }}</p>
                        <h3 class="font-display text-base leading-snug mb-2 min-w-0 line-clamp-2"
                            style="color:var(--color-text-dark)">{{ $product->name }}</h3>
                        <div class="flex items-baseline gap-2 flex-wrap">
                            <span class="font-sans font-semibold text-sm"
                                  style="color:var(--color-primary)">₦{{ number_format($product->price) }}</span>
                            @if($product->compare_at_price)
                            <span class="font-sans text-xs line-through"
                                  style="color:var(--color-text-muted)">₦{{ number_format($product->compare_at_price) }}</span>
                            @endif
                        </div>
                    </div>

                </a>

                {{-- Add to cart — outside the link --}}
                @if($product->isInStock())
                <div class="mt-3 px-0.5">
                    <button
                        type="button"
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
                        class="w-full py-2.5 font-sans text-[10px] tracking-[0.25em] uppercase font-medium
                               transition-all duration-300 border active:scale-95"
                        :style="added
                            ? 'background:var(--color-primary);color:var(--color-surface);border-color:var(--color-primary);'
                            : 'background:transparent;color:var(--color-text-dark);border-color:rgba(30,12,20,0.18);'"
                        x-text="adding ? 'Adding…' : added ? '✓ Added' : 'Add to Ritual'">
                        Add to Ritual
                    </button>
                </div>
                @endif

            </div>
            @endforeach
        </div>

        {{-- View all CTA --}}
        <div class="text-center mt-14">
            <a href="{{ route('shop') }}"
               class="inline-flex items-center gap-3 font-sans text-xs tracking-[0.28em] uppercase
                      border py-4 px-10 transition-all duration-300 hover:border-primary
                      hover:text-primary group"
               style="border-color:rgba(30,12,20,0.18);color:var(--color-text-dark)">
                View All Rituals
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-300"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     PHILOSOPHY — Full-bleed editorial quote
═══════════════════════════════════════════════════════════════ --}}
<section class="relative py-32 lg:py-44 overflow-hidden" style="background:var(--color-primary)">

    <div class="absolute inset-0 bg-grain opacity-20 pointer-events-none"></div>
    <div class="absolute inset-0 pointer-events-none"
         style="background:radial-gradient(ellipse at center,rgba(201,169,111,0.07) 0%,transparent 60%)"></div>

    {{-- Vertical side label — desktop --}}
    <div class="absolute left-10 top-1/2 -translate-y-1/2 hidden lg:flex flex-col items-center gap-4"
         aria-hidden="true">
        <div class="w-px h-16" style="background:linear-gradient(to bottom,transparent,rgba(201,169,111,0.28))"></div>
        <span class="font-sans text-[9px] tracking-[0.45em] uppercase"
              style="color:rgba(201,169,111,0.30);writing-mode:vertical-rl">Philosophy</span>
        <div class="w-px h-16" style="background:linear-gradient(to bottom,rgba(201,169,111,0.28),transparent)"></div>
    </div>

    <div class="relative z-10 text-center max-w-3xl mx-auto px-6">
        <blockquote class="font-display italic leading-tight"
                    style="font-size:clamp(1.7rem,4.2vw,3.2rem);color:var(--color-surface)">
            "Every fragrance is a memory<br>
            <em style="color:var(--color-ghost)">you haven't made yet."</em>
        </blockquote>
        <div class="w-10 h-px mx-auto mt-12 lg:mt-16" style="background:rgba(201,169,111,0.35)"></div>
        <p class="font-sans text-[10px] tracking-[0.35em] uppercase mt-5"
           style="color:rgba(201,169,111,0.40)">Aurachell &nbsp;·&nbsp; Lagos</p>
    </div>

</section>

{{-- ═══════════════════════════════════════════════════════════════
     TESTIMONIALS
═══════════════════════════════════════════════════════════════ --}}
@if($reviews->count())
<section class="py-24 lg:py-32 overflow-hidden" style="background:var(--color-surface)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-12 lg:mb-16">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-6 h-px flex-shrink-0" style="background:var(--color-ghost)"></div>
                <span class="font-sans text-[10px] tracking-[0.35em] uppercase"
                      style="color:var(--color-ghost)">Customer Voices</span>
            </div>
            <h2 class="font-display leading-tight"
                style="font-size:clamp(1.8rem,4vw,3rem);color:var(--color-text-dark)">
                What They're Saying
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews->take(3) as $review)
            <div class="p-7 border min-w-0"
                 style="border-color:rgba(201,169,111,0.15);background:white">
                {{-- Gold line as rating indicator --}}
                <div class="w-10 h-px mb-7" style="background:var(--color-ghost)"></div>
                <blockquote class="font-display italic text-base leading-relaxed mb-7"
                            style="color:var(--color-text-dark)">
                    "{{ Str::limit($review->body, 120) }}"
                </blockquote>
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center
                                text-sm font-semibold font-display flex-shrink-0"
                         style="background:var(--color-base);color:var(--color-primary)">
                        {{ strtoupper(substr($review->user?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-sans text-xs font-semibold tracking-wider uppercase truncate"
                           style="color:var(--color-text-dark)">{{ $review->user?->name ?? 'Anonymous' }}</p>
                        <p class="font-sans text-[10px] tracking-wider uppercase"
                           style="color:var(--color-ghost)">Verified Purchase</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════
     THE INNER CIRCLE — Newsletter
═══════════════════════════════════════════════════════════════ --}}
<section class="relative py-24 lg:py-32 overflow-hidden" style="background:var(--color-primary)">

    <div class="absolute inset-0 bg-grain opacity-15 pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg">

            <div class="flex items-center gap-3 mb-8">
                <div class="w-6 h-px flex-shrink-0" style="background:var(--color-ghost)"></div>
                <span class="font-sans text-[10px] tracking-[0.35em] uppercase"
                      style="color:var(--color-ghost)">Stay Close</span>
            </div>

            <h2 class="font-display leading-tight mb-4"
                style="font-size:clamp(2rem,4.5vw,3.2rem);color:var(--color-surface)">
                The Inner Circle
            </h2>

            <p class="font-sans text-sm leading-loose mb-10"
               style="color:rgba(250,245,237,0.45)">
                First access to new collections, private offers,<br class="hidden sm:block">
                and the stories behind our scents.
            </p>

            <form action="{{ route('newsletter.subscribe') }}" method="POST"
                  class="flex flex-col sm:flex-row gap-3 max-w-sm">
                @csrf
                {{-- Honeypot --}}
                <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                </div>
                <input type="email" name="email" placeholder="Your email" required
                       class="flex-1 min-w-0 px-4 py-3.5 font-sans text-xs tracking-wider focus:outline-none"
                       style="background:rgba(250,245,237,0.07);
                              border:1px solid rgba(201,169,111,0.25);
                              color:var(--color-surface)">
                <button type="submit"
                        class="flex-shrink-0 px-8 py-3.5 font-sans text-xs tracking-[0.28em] uppercase
                               font-semibold transition-opacity duration-300 hover:opacity-85 active:scale-95"
                        style="background:var(--color-ghost);color:var(--color-text-dark)">
                    Join
                </button>
            </form>

            @if(session('newsletter_success'))
            <p class="mt-5 font-sans text-xs tracking-wider" style="color:var(--color-ghost)">
                {{ session('newsletter_success') }}
            </p>
            @endif

        </div>
    </div>

</section>

{{-- ═══════════════════════════════════════════════════════════════
     THE JOURNAL — Blog preview
═══════════════════════════════════════════════════════════════ --}}
@php $latestPosts = \App\Models\BlogPost::where('is_published', true)->latest('published_at')->limit(3)->get(); @endphp
@if($latestPosts->count())
<section class="py-24 lg:py-32 overflow-hidden" style="background:var(--color-surface)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between mb-12 lg:mb-16 gap-4 flex-wrap">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-6 h-px flex-shrink-0" style="background:var(--color-ghost)"></div>
                    <span class="font-sans text-[10px] tracking-[0.35em] uppercase"
                          style="color:var(--color-ghost)">The Journal</span>
                </div>
                <h2 class="font-display leading-tight"
                    style="font-size:clamp(1.8rem,4vw,3rem);color:var(--color-text-dark)">
                    Stories &amp; Rituals
                </h2>
            </div>
            <a href="{{ route('blog.index') }}"
               class="flex items-center gap-2 font-sans text-xs tracking-[0.22em] uppercase
                      transition-opacity hover:opacity-50 group flex-shrink-0"
               style="color:var(--color-text-muted)">
                All Posts
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-300"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block min-w-0">

                {{-- Cover image --}}
                <div class="overflow-hidden mb-5" style="aspect-ratio:16/9;background:var(--color-base)">
                    @if($post->cover_image)
                    <img src="{{ asset('images/blog/' . $post->cover_image) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                    @else
                    <div class="w-full h-full flex items-center justify-center"
                         style="background:linear-gradient(135deg,var(--color-primary),rgba(201,169,111,0.25))">
                        <span class="font-display select-none pointer-events-none"
                              style="font-size:5rem;color:rgba(250,245,237,0.05)">J</span>
                    </div>
                    @endif
                </div>

                {{-- Animated gold rule --}}
                <div class="w-8 h-px mb-4 transition-all duration-500 group-hover:w-16"
                     style="background:var(--color-ghost)"></div>

                <h3 class="font-display text-lg leading-snug mb-3 min-w-0 transition-colors duration-300"
                    style="color:var(--color-text-dark)">{{ $post->title }}</h3>

                <p class="font-sans text-[10px] tracking-[0.22em] uppercase"
                   style="color:var(--color-text-muted)">
                    {{ $post->reading_time }} min read &nbsp;·&nbsp; {{ $post->published_at?->format('d M Y') }}
                </p>

            </a>
            @endforeach
        </div>

    </div>
</section>
@endif

@endsection
