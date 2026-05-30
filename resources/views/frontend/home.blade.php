@extends('layouts.app')

@section('title', 'Aurachell · Some rooms ask you to stay.')
@section('meta_description', 'Luxury handcrafted home diffusers. Natural ingredients. Premium craft. Made in Nigeria. Scents that transform everyday spaces into moments of calm.')

@push('styles')
<style>
/* Ticker animation */
@keyframes ticker {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}
/* Scroll indicator pulse */
@keyframes scrollLine {
    0%   { transform: translateY(-100%); opacity: 0; }
    20%  { opacity: 1; }
    80%  { opacity: 1; }
    100% { transform: translateY(250%); opacity: 0; }
}
/* Ring rotation */
@keyframes rotateRing {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
/* Gentle float for chips */
@keyframes floatUp {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-7px); }
}
@keyframes floatDown {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(7px); }
}
/* Core glow pulse */
@keyframes coreGlow {
    0%, 100% { opacity: 0.07; transform: scale(1); }
    50%       { opacity: 0.16; transform: scale(1.12); }
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

/* Ring: outer spinning wrapper */
.ring-spin-cw  { animation: rotateRing 18s linear infinite; }
.ring-spin-ccw { animation: rotateRing 28s linear infinite reverse; }

/* Chip floats */
.chip-a { animation: floatUp   5s ease-in-out infinite; }
.chip-b { animation: floatDown 5s ease-in-out infinite 2.5s; }

/* Core glow */
.core-glow { animation: coreGlow 5s ease-in-out infinite; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════════
     HERO — Some rooms ask you to stay.
═══════════════════════════════════════════════════════════════ --}}
<section class="relative min-h-screen flex items-center overflow-x-hidden" style="background:var(--color-primary)">

    {{-- Grain texture --}}
    <div class="absolute inset-0 opacity-25 pointer-events-none"
         style="background-image:url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noise%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.75%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noise)%22/%3E%3C/svg%3E');
                background-size:200px;opacity:0.04"></div>

    {{-- Atmospheric glow --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background:radial-gradient(ellipse at 12% 70%,rgba(55,18,32,0.13) 0%,transparent 50%),
                radial-gradient(ellipse at 88% 15%,rgba(55,18,32,0.07) 0%,transparent 45%)"></div>

    {{-- Main content: text + decorative stacked on mobile, side-by-side on desktop --}}
    <div class="relative z-10 w-full max-w-7xl mx-auto px-5 sm:px-8 lg:px-10
                pt-28 pb-20 sm:pt-32 sm:pb-24
                lg:py-0 lg:min-h-screen lg:flex lg:items-center">

        {{-- ── Left: copy ── --}}
        <div class="w-full lg:w-[54%] lg:pr-16">

            {{-- Eyebrow --}}
            <div class="mb-8 lg:mb-12">
                <span class="font-sans text-[10px] tracking-[0.4em] uppercase leading-none"
                      style="color:var(--color-ghost)">Luxury Home Fragrance, Nigeria</span>
            </div>

            {{-- Headline --}}
            <h1 class="font-display tracking-tight mb-6 lg:mb-8"
                style="font-size:clamp(2.8rem,9vw,6.5rem);line-height:0.93;color:#F7F2EB">
                Some rooms<br>
                ask you<br>
                <em class="not-italic" style="color:#C9A96F">to stay.</em>
            </h1>

            {{-- Pull quote --}}
            <p class="font-display italic mb-5 lg:mb-7"
               style="font-size:clamp(0.9rem,1.6vw,1.1rem);
                      color:rgba(247,242,235,0.55);
                      letter-spacing:0.01em;
                      line-height:1.65">
                Handcrafted diffusers for the spaces<br class="hidden sm:block"> you love most.
            </p>

            {{-- Brand proof --}}
            <p class="font-sans text-[10px] tracking-[0.22em] mb-10 lg:mb-14"
               style="color:rgba(247,242,235,0.35)">
                Natural ingredients, Premium craft, Made in Nigeria
            </p>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-10">
                <a href="{{ route('shop') }}"
                   class="inline-flex items-center gap-3 font-sans text-xs tracking-[0.28em] uppercase font-semibold
                          px-8 py-4 transition-all duration-300 hover:opacity-85 active:scale-95 flex-shrink-0"
                   style="background:#FFFFFF;color:#371220">
                    Explore the Collection
                </a>
                <a href="{{ route('about') }}"
                   class="inline-flex items-center gap-2 font-sans text-xs tracking-[0.22em] uppercase
                          transition-all duration-300 group"
                   style="color:rgba(247,242,235,0.45)">
                    Our Philosophy
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-300"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

        </div>

        {{-- ── Right / Bottom: animated decorative ring — ALL screen sizes ── --}}
        {{-- Mobile: centered below text with margin-top; Desktop: right column --}}
        <div class="flex items-center justify-center relative
                    mt-14 sm:mt-16 lg:mt-0 lg:w-[46%]"
             aria-hidden="true">

            {{-- Responsive container --}}
            <div class="relative flex items-center justify-center"
                 style="width:clamp(220px,64vw,420px);height:clamp(220px,64vw,420px)">

                {{-- ══ SVG: circular rotating text + rings ══ --}}
                {{-- One SVG covers the full container; animateTransform spins each group independently --}}
                <svg class="absolute inset-0 w-full h-full overflow-visible" viewBox="0 0 420 420"
                     xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        {{-- Outer text path — sits just inside the outer border ring --}}
                        <path id="outerArc"
                              d="M 210,210 m -196,0 a 196,196 0 1,1 392,0 a 196,196 0 1,1 -392,0"/>
                        {{-- Inner text path — sits at the middle ring level --}}
                        <path id="innerArc"
                              d="M 210,210 m -148,0 a 148,148 0 1,1 296,0 a 148,148 0 1,1 -296,0"/>
                    </defs>

                    {{-- Outer ring border --}}
                    <circle cx="210" cy="210" r="200"
                            fill="none" stroke="rgba(201,169,111,0.18)" stroke-width="1"/>

                    {{-- Middle ring border --}}
                    <circle cx="210" cy="210" r="155"
                            fill="none" stroke="rgba(201,169,111,0.10)" stroke-width="1"/>

                    {{-- Inner dashed ring --}}
                    <circle cx="210" cy="210" r="105"
                            fill="none" stroke="rgba(201,169,111,0.08)" stroke-width="1"
                            stroke-dasharray="4 6"/>

                    {{-- ── Outer circular text — counter-clockwise ── --}}
                    <g>
                        <animateTransform attributeName="transform" type="rotate"
                                          from="0 210 210" to="-360 210 210"
                                          dur="38s" repeatCount="indefinite"/>
                        <text fill="rgba(201,169,111,0.55)" font-size="11.5" letter-spacing="4.5"
                              font-family="Georgia, 'Times New Roman', serif">
                            <textPath href="#outerArc" xlink:href="#outerArc">
                                SOME ROOMS ASK YOU TO STAY  ·  AURACHELL  ·  LUXURY HOME FRAGRANCE  ·  LAGOS NIGERIA  ·  SOME ROOMS ASK YOU TO STAY  ·  AURACHELL  ·  LUXURY HOME FRAGRANCE  ·
                            </textPath>
                        </text>
                    </g>

                    {{-- ── Inner circular text — clockwise ── --}}
                    <g>
                        <animateTransform attributeName="transform" type="rotate"
                                          from="0 210 210" to="360 210 210"
                                          dur="24s" repeatCount="indefinite"/>
                        <text fill="rgba(201,169,111,0.38)" font-size="9.5" letter-spacing="3.5"
                              font-family="Georgia, 'Times New Roman', serif">
                            <textPath href="#innerArc" xlink:href="#innerArc">
                                HANDCRAFTED  ·  NATURAL INGREDIENTS  ·  EST 2022  ·  PREMIUM CRAFT  ·  HANDCRAFTED  ·  NATURAL INGREDIENTS  ·  EST 2022  ·
                            </textPath>
                        </text>
                    </g>

                    {{-- ── Orbiting dot on outer ring — clockwise ── --}}
                    <g>
                        <animateTransform attributeName="transform" type="rotate"
                                          from="0 210 210" to="360 210 210"
                                          dur="14s" repeatCount="indefinite"/>
                        <circle cx="210" cy="10" r="4"
                                fill="#C9A96F"/>
                        <circle cx="210" cy="410" r="2.5"
                                fill="rgba(201,169,111,0.40)"/>
                    </g>

                    {{-- ── Orbiting dot on middle ring — counter-clockwise ── --}}
                    <g>
                        <animateTransform attributeName="transform" type="rotate"
                                          from="0 210 210" to="-360 210 210"
                                          dur="20s" repeatCount="indefinite"/>
                        <circle cx="210" cy="55" r="3"
                                fill="rgba(201,169,111,0.60)"/>
                    </g>

                    {{-- Core glow --}}
                    <circle cx="210" cy="210" r="38" fill="url(#coreGradient)"/>
                    <defs>
                        <radialGradient id="coreGradient" cx="50%" cy="50%" r="50%">
                            <stop offset="0%" stop-color="rgba(201,169,111,0.12)"/>
                            <stop offset="100%" stop-color="rgba(201,169,111,0)"/>
                        </radialGradient>
                    </defs>

                    {{-- Watermark A --}}
                    <text x="210" y="248" text-anchor="middle"
                          fill="rgba(201,169,111,0.07)" font-size="140"
                          font-family="Georgia, 'Times New Roman', serif">A</text>

                </svg>

                {{-- ── Floating chips (sm+ only) ── --}}
                <div class="chip-a absolute hidden sm:block px-4 py-2.5 z-10"
                     style="right:-8%;top:36%;
                            background:rgba(55,18,32,0.80);
                            border:1px solid rgba(55,18,32,0.22);
                            backdrop-filter:blur(10px)">
                    <p class="font-sans text-[8px] tracking-[0.28em] uppercase mb-1"
                       style="color:rgba(201,169,111,0.50)">Signature Blend</p>
                    <p class="font-display text-sm" style="color:rgba(250,245,237,0.88)">Oud &amp; Amber</p>
                </div>

                <div class="chip-b absolute hidden sm:block px-3 py-2 z-10"
                     style="left:-6%;bottom:28%;
                            background:rgba(55,18,32,0.75);
                            border:1px solid rgba(55,18,32,0.15)">
                    <p class="font-sans text-[8px] tracking-[0.22em] uppercase"
                       style="color:rgba(201,169,111,0.48)">Est. Lagos, 2022</p>
                </div>

            </div>
        </div>

    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-3"
         aria-hidden="true">
        <span class="font-sans text-[9px] tracking-[0.45em] uppercase"
              style="color:rgba(250,245,237,0.16)">Scroll</span>
        <div class="w-px h-12 relative overflow-hidden"
             style="background:rgba(250,245,237,0.07)">
            <div class="scroll-indicator-line"></div>
        </div>
    </div>

</section>

{{-- ═══════════════════════════════════════════════════════════════
     TICKER STRIP
═══════════════════════════════════════════════════════════════ --}}
<div class="overflow-hidden py-4 select-none"
     style="background:#371220;
            border-top:1px solid rgba(55,18,32,0.12);
            border-bottom:1px solid rgba(55,18,32,0.12)">
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
        <span class="font-sans text-[10px] tracking-[0.32em] uppercase mx-7 whitespace-nowrap"
              style="color:rgba(201,169,111,0.55)">{{ $item }}</span>
        <span class="text-[5px] flex-shrink-0 self-center mx-2"
              style="color:rgba(201,169,111,0.22)">◆</span>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     FIND YOUR ATMOSPHERE — Collections
═══════════════════════════════════════════════════════════════ --}}
<section class="py-20 lg:py-28 overflow-hidden" style="background:var(--color-bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section head --}}
        <div class="flex items-end justify-between mb-10 lg:mb-14 gap-4 flex-wrap">
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
               class="group relative overflow-hidden block md:row-span-2 min-h-[300px] md:min-h-[560px]"
               style="background:var(--color-base)">
                @if($featuredCategories->first()->image)
                <img src="{{ $featuredCategories->first()->image_url }}"
                     alt="{{ $featuredCategories->first()->name }}"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                @else
                <div class="absolute inset-0 flex items-center justify-center"
                     style="background:linear-gradient(160deg,var(--color-primary) 0%,rgba(55,18,32,0.15) 100%)">
                    <span class="font-display select-none pointer-events-none"
                          style="font-size:10rem;color:rgba(250,245,237,0.04)">A</span>
                </div>
                @endif
                <div class="absolute inset-0 transition-opacity duration-500"
                     style="background:linear-gradient(to top,rgba(55,18,32,0.92) 0%,rgba(55,18,32,0.15) 55%,transparent 100%)"></div>
                <div class="absolute inset-x-0 bottom-0 p-7 lg:p-10">
                    <p class="font-sans text-[9px] tracking-[0.35em] uppercase mb-2"
                       style="color:rgba(201,169,111,0.65)">Collection</p>
                    <h3 class="font-display text-3xl lg:text-4xl mb-3"
                        style="color:#FAF5ED">{{ $featuredCategories->first()->name }}</h3>
                    @if($featuredCategories->first()->description)
                    <p class="font-sans text-sm mb-4 max-w-xs leading-relaxed"
                       style="color:rgba(250,245,237,0.50)">{{ Str::limit($featuredCategories->first()->description, 80) }}</p>
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
               class="group relative overflow-hidden block min-h-[180px] md:min-h-[268px]"
               style="background:var(--color-base)">
                @if($cat->image)
                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                @else
                <div class="absolute inset-0 flex items-center justify-center"
                     style="background:linear-gradient(160deg,rgba(55,18,32,0.85) 0%,rgba(55,18,32,0.18) 100%)">
                    <span class="font-display select-none pointer-events-none"
                          style="font-size:7rem;color:rgba(250,245,237,0.04)">A</span>
                </div>
                @endif
                <div class="absolute inset-0"
                     style="background:linear-gradient(to top,rgba(55,18,32,0.86) 0%,rgba(55,18,32,0.06) 60%,transparent 100%)"></div>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($featuredCategories as $cat)
            <a href="{{ route('shop', ['category' => $cat->slug]) }}"
               class="group relative overflow-hidden block min-h-[260px]"
               style="background:var(--color-base)">
                @if($cat->image)
                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                @else
                <div class="absolute inset-0"
                     style="background:linear-gradient(160deg,var(--color-primary) 0%,rgba(55,18,32,0.2) 100%)"></div>
                @endif
                <div class="absolute inset-0"
                     style="background:linear-gradient(to top,rgba(55,18,32,0.88) 0%,transparent 60%)"></div>
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
     ⚠  Wishlist forms are OUTSIDE the <a> tag (nested form bug fix)
═══════════════════════════════════════════════════════════════ --}}
<section class="py-20 lg:py-28 overflow-hidden"
         style="background:var(--color-bg);
                border-top:1px solid rgba(55,18,32,0.10)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section head --}}
        <div class="flex items-end justify-between mb-10 lg:mb-14 gap-4 flex-wrap">
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

            <div class="group relative min-w-0"
                 x-data="{ adding: false, added: false }"
                 @cart-reset.window="added = false">

                {{-- ✅ Wishlist form OUTSIDE the <a> — positioned absolutely --}}
                @auth
                <form action="{{ route('account.wishlist.toggle', $product) }}"
                      method="POST"
                      class="absolute top-3 right-3 z-10">
                    @csrf
                    <button type="submit"
                            class="w-8 h-8 rounded-full flex items-center justify-center shadow-sm
                                   transition-transform duration-200 hover:scale-110 active:scale-95"
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
                            : 'background:transparent;color:var(--color-text-dark);border-color:rgba(55,18,32,0.20);'"
                        x-text="adding ? 'Adding…' : added ? '✓ Added' : 'Add to Ritual'">
                        Add to Ritual
                    </button>
                </div>
                @endif

            </div>
            @endforeach
        </div>

        {{-- View all CTA --}}
        <div class="text-center mt-12">
            <a href="{{ route('shop') }}"
               class="inline-flex items-center gap-3 font-sans text-xs tracking-[0.28em] uppercase
                      border py-4 px-10 transition-all duration-300 hover:opacity-60 group"
               style="border-color:rgba(55,18,32,0.20);color:var(--color-text-dark)">
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
     PHILOSOPHY — Compact full-bleed quote + stats strip
═══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden" style="background:var(--color-primary)">

    <div class="absolute inset-0 pointer-events-none"
         style="background:radial-gradient(ellipse at center,rgba(55,18,32,0.07) 0%,transparent 65%)"></div>

    {{-- Quote block --}}
    <div class="relative z-10 text-center max-w-3xl mx-auto px-6 py-16 lg:py-20">

        {{-- Decorative rule above --}}
        <div class="flex items-center gap-5 justify-center mb-10">
            <div class="flex-1 h-px max-w-[80px]" style="background:rgba(201,169,111,0.20)"></div>
            <span class="font-sans text-[8px] tracking-[0.45em] uppercase"
                  style="color:rgba(201,169,111,0.35)">Philosophy</span>
            <div class="flex-1 h-px max-w-[80px]" style="background:rgba(201,169,111,0.20)"></div>
        </div>

        <blockquote class="font-display italic leading-tight mb-10"
                    style="font-size:clamp(1.6rem,4vw,3rem);color:#F7F2EB">
            "Every fragrance is a memory<br>
            <em style="color:#C9A96F">you haven't made yet."</em>
        </blockquote>

        <p class="font-sans text-[10px] tracking-[0.38em] uppercase"
           style="color:rgba(201,169,111,0.38)">Aurachell &nbsp;·&nbsp; Lagos</p>
    </div>

    {{-- Stats strip with count-up animation --}}
    <div class="relative z-10 border-t grid grid-cols-3 divide-x"
         style="border-color:rgba(201,169,111,0.12);divide-color:rgba(201,169,111,0.12)">
        <div class="text-center py-10 px-4">
            <p class="font-display mb-2 stat-count"
               data-target="100" data-suffix="%"
               style="font-size:clamp(1.6rem,4vw,2.6rem);color:var(--color-ghost)">0%</p>
            <p class="font-sans text-[9px] tracking-[0.32em] uppercase"
               style="color:rgba(250,245,237,0.28)">Natural Luxury</p>
        </div>
        <div class="text-center py-10 px-4">
            <p class="font-display mb-2 stat-count"
               data-target="3000" data-suffix="+" data-format="thousands"
               style="font-size:clamp(1.6rem,4vw,2.6rem);color:var(--color-ghost)">0+</p>
            <p class="font-sans text-[9px] tracking-[0.32em] uppercase"
               style="color:rgba(250,245,237,0.28)">Happy Customers</p>
        </div>
        <div class="text-center py-10 px-4">
            <p class="font-display mb-2"
               style="font-size:clamp(1.6rem,4vw,2.6rem);color:var(--color-ghost)">Est. 2022</p>
            <p class="font-sans text-[9px] tracking-[0.32em] uppercase"
               style="color:rgba(250,245,237,0.28)">Made in Lagos</p>
        </div>
    </div>

</section>

<script>
(function () {
    function runCounter(el) {
        var target   = parseInt(el.dataset.target, 10);
        var suffix   = el.dataset.suffix  || '';
        var format   = el.dataset.format  || '';
        var duration = 2200;
        var start    = null;

        function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

        function step(ts) {
            if (!start) start = ts;
            var progress = Math.min((ts - start) / duration, 1);
            var value    = Math.floor(easeOutQuart(progress) * target);
            var display  = format === 'thousands'
                ? value.toLocaleString()
                : String(value);
            el.textContent = display + suffix;
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    var counters = document.querySelectorAll('.stat-count');
    if (!counters.length) return;

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                runCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(function (el) { observer.observe(el); });
})();
</script>

{{-- ═══════════════════════════════════════════════════════════════
     TESTIMONIALS — always visible with static fallback
═══════════════════════════════════════════════════════════════ --}}
@php
    $displayReviews = $reviews->count() ? $reviews->take(3) : collect([
        (object)['body' => 'My home smells incredible. Every guest asks what candle I\'m burning. It\'s not a candle, it\'s Aurachell. I\'ve been obsessed since the first diffuser.', 'user' => (object)['name' => 'Chisom A.']],
        (object)['body' => 'The Oud & Amber blend is everything. I light it every evening and it genuinely transforms my mood. Pure luxury at a fair price.', 'user' => (object)['name' => 'Tobi F.']],
        (object)['body' => 'Received this as a birthday gift and I\'ve been ordering more ever since. The scent lasts for hours and the packaging feels so premium.', 'user' => (object)['name' => 'Amaka O.']],
    ]);
@endphp

<section class="py-20 lg:py-28 overflow-hidden" style="background:var(--color-bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10 lg:mb-14">
            <div class="mb-4">
                <span class="font-sans text-[10px] tracking-[0.35em] uppercase"
                      style="color:var(--color-ghost)">Customer Voices</span>
            </div>
            <h2 class="font-display leading-tight"
                style="font-size:clamp(1.8rem,4vw,3rem);color:var(--color-text-dark)">
                What They're Saying
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">
            @foreach($displayReviews as $review)
            <div class="flex flex-col p-7 border min-w-0"
                 style="border-color:rgba(201,169,111,0.14);background:var(--color-surface);
                        box-shadow:0 2px 24px rgba(55,18,32,0.04)">
                {{-- Quote --}}
                <blockquote class="font-display italic text-base leading-relaxed flex-1 mb-7"
                            style="color:var(--color-text-dark)">
                    "{{ Str::limit($review->body, 140) }}"
                </blockquote>
                {{-- Reviewer --}}
                <div class="flex items-center gap-3 min-w-0 mt-auto">
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

{{-- ═══════════════════════════════════════════════════════════════
     THE INNER CIRCLE — Newsletter
═══════════════════════════════════════════════════════════════ --}}
<section class="relative py-20 lg:py-28 overflow-hidden" style="background:var(--color-surface)">

    <div class="absolute inset-0 pointer-events-none"
         style="background:radial-gradient(ellipse at 80% 50%,rgba(247,242,235,0.80) 0%,transparent 55%)"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg">

            <div class="flex items-center gap-3 mb-8">
                <div class="w-6 h-px flex-shrink-0" style="background:var(--color-ghost)"></div>
                <span class="font-sans text-[10px] tracking-[0.35em] uppercase"
                      style="color:var(--color-ghost)">Stay Close</span>
            </div>

            <h2 class="font-display leading-tight mb-4"
                style="font-size:clamp(2rem,4.5vw,3.2rem);color:var(--color-text-dark)">
                The Inner Circle
            </h2>

            <p class="font-sans text-sm leading-loose mb-10"
               style="color:var(--color-text-muted)">
                First access to new collections, private offers,<br class="hidden sm:block">
                and the stories behind our scents.
            </p>

            <form action="{{ route('newsletter.subscribe') }}" method="POST"
                  class="flex flex-col sm:flex-row gap-3 max-w-sm">
                @csrf
                <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                </div>
                <input type="email" name="email" placeholder="Your email" required
                       class="flex-1 min-w-0 px-4 py-3.5 font-sans text-xs tracking-wider focus:outline-none"
                       style="background:rgba(247,242,235,0.60);
                              border:1px solid rgba(201,169,111,0.22);
                              color:var(--color-text-dark)">
                <button type="submit"
                        class="flex-shrink-0 px-8 py-3.5 font-sans text-xs tracking-[0.28em] uppercase
                               font-semibold transition-opacity duration-300 hover:opacity-85 active:scale-95"
                        style="background:var(--color-primary);color:#FFFFFF">
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
<section class="py-20 lg:py-28 overflow-hidden" style="background:var(--color-bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between mb-10 lg:mb-14 gap-4 flex-wrap">
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

                <div class="overflow-hidden mb-5" style="aspect-ratio:16/9;background:var(--color-base)">
                    @if($post->cover_image)
                    <img src="{{ asset('images/blog/' . $post->cover_image) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                    @else
                    <div class="w-full h-full flex items-center justify-center"
                         style="background:linear-gradient(135deg,var(--color-primary),rgba(55,18,32,0.25))">
                        <span class="font-display select-none pointer-events-none"
                              style="font-size:5rem;color:rgba(250,245,237,0.05)">J</span>
                    </div>
                    @endif
                </div>

                <div class="w-8 h-px mb-4 transition-all duration-500 group-hover:w-16"
                     style="background:var(--color-ghost)"></div>

                <h3 class="font-display text-lg leading-snug mb-3 min-w-0"
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
