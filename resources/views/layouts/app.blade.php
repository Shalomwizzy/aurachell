<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Aurachell')) — Luxury Home Diffusers</title>
    <meta name="description" content="@yield('meta_description', 'Premium home diffusers crafted for calm, luxury living.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Premium home diffusers crafted for calm, luxury living.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    @hasSection('og_image')
    <meta property="og:image" content="@yield('og_image')">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Premium home diffusers crafted for calm, luxury living.')">
    @hasSection('og_image')
    <meta name="twitter:image" content="@yield('og_image')">
    @endif

    @php $favicon = \App\Models\Setting::get('favicon'); @endphp
    @if($favicon)
    <link rel="icon" href="{{ asset('images/' . $favicon) }}">
    @endif

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6B2016">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Aurachell">
    <link rel="apple-touch-icon" href="/images/icons/icon-192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/icon-152.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Aurachell">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>[x-cloak]{display:none!important}</style>

    @php $gaId = config('app.google_analytics_id') ?: \App\Models\Setting::get('ga_measurement_id'); @endphp
    @if($gaId)
    <script>
        window._gaId = '{{ $gaId }}';
        function loadGA() {
            if (document.getElementById('ga-script')) return;
            var s = document.createElement('script');
            s.id = 'ga-script';
            s.async = true;
            s.src = 'https://www.googletagmanager.com/gtag/js?id=' + window._gaId;
            document.head.appendChild(s);
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            window.gtag = gtag;
            gtag('js', new Date());
            gtag('config', window._gaId);
        }
        if (localStorage.getItem('aurachell_cookie_consent') === 'accepted') { loadGA(); }
    </script>
    @endif

    @php
        $fbPixelId      = \App\Models\Setting::get('facebook_pixel_id');
        $fbPixelEnabled = \App\Models\Setting::get('facebook_pixel_enabled');
    @endphp
    @if($fbPixelId && $fbPixelEnabled === '1')
    <script>
        window._fbPixelId = '{{ $fbPixelId }}';
        function loadFbPixel() {
            if (window.fbq) return;
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', window._fbPixelId);
            fbq('track', 'PageView');
        }
        if (localStorage.getItem('aurachell_cookie_consent') === 'accepted') { loadFbPixel(); }
        document.addEventListener('cookie-consent-accepted', loadFbPixel);
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"/></noscript>
    @endif
</head>
<body class="bg-surface" x-data="{ cartOpen: false, searchOpen: false, mobileMenuOpen: false }">

    {{-- Announcement Bar --}}
    @php $announcement = \App\Models\Setting::get('announcement_bar'); @endphp
    @if($announcement && \App\Models\Setting::get('announcement_bar_active'))
    <div class="relative bg-sage text-cream text-center py-2.5 px-4 text-xs tracking-widest uppercase font-sans" x-data="{ show: true }" x-show="show">
        <span>{{ $announcement }}</span>
        <button @click="show = false" class="absolute right-4 top-1/2 -translate-y-1/2 text-cream/60 hover:text-cream">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    {{-- Navbar --}}
    <header
        class="sticky top-0 z-50 transition-all duration-300"
        style="background:var(--color-surface);"
        x-data="{ scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 20"
        :class="scrolled ? 'backdrop-blur-sm shadow-luxury' : ''"
    >
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-text-dark" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <a href="{{ route('home') }}" class="flex-shrink-0">
                    <span class="font-display text-2xl text-sage tracking-wider">Aurachell</span>
                </a>

                <div class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                    <div class="relative" x-data="{ open: false, timer: null }"
                         @mouseenter="clearTimeout(timer); open = true"
                         @mouseleave="timer = setTimeout(() => open = false, 150)">
                        <a href="{{ route('shop') }}" class="nav-link flex items-center gap-1 py-2">
                            Shop
                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full left-1/2 -translate-x-1/2 pt-3 w-80"
                             style="display:none;">
                            <div class="border border-sand/50 shadow-luxury-lg p-6" style="background:var(--color-surface);">
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach(\App\Models\Category::active()->orderBy('sort_order')->limit(6)->get() as $cat)
                                    <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="group flex items-center gap-2 text-sm text-text-dark hover:text-sage transition-colors py-1">
                                        <span class="w-1 h-1 bg-sand rounded-full group-hover:bg-sage transition-colors flex-shrink-0"></span>
                                        {{ $cat->name }}
                                    </a>
                                    @endforeach
                                </div>
                                <div class="mt-4 pt-4 border-t border-sand/50">
                                    <a href="{{ route('shop') }}" class="text-xs tracking-widest uppercase text-sage font-medium hover:underline">View All Products →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('about') }}" class="nav-link">Our Story</a>
                    <a href="{{ route('blog.index') }}" class="nav-link">Journal</a>
                    <a href="{{ route('product-request.create') }}" class="nav-link">Request</a>
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="searchOpen = !searchOpen" class="p-1.5 text-text-dark hover:text-sage transition-colors" aria-label="Search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>

                    @auth
                    <a href="{{ route('account.wishlist') }}"
                       class="hidden sm:flex relative p-1.5 text-text-dark hover:text-sage transition-colors"
                       aria-label="Wishlist"
                       x-data="{ wishCount: {{ auth()->user()->wishlist()->count() }} }">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span x-show="wishCount > 0"
                              x-text="wishCount > 9 ? '9+' : wishCount"
                              class="absolute -top-1.5 -right-1.5 w-4 h-4 text-white text-[10px] font-bold rounded-full flex items-center justify-center"
                              style="background:#6B2016;display:none;"></span>
                    </a>
                    <div class="relative hidden sm:block" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="p-1.5 text-text-dark hover:text-sage transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-44 bg-cream border border-sand/50 shadow-luxury py-2" style="display:none;">
                            <a href="{{ route('account.overview') }}" class="block px-4 py-2 text-sm text-text-dark hover:bg-sand/30">My Account</a>
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-sm text-text-dark hover:bg-sand/30">Orders</a>
                            @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'sales_rep', 'inventory_manager', 'support']))
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-sage font-medium hover:bg-sand/30">Admin Panel</a>
                            @endif
                            <div class="border-t border-sand/50 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-text-muted hover:bg-sand/30">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="hidden sm:flex p-1.5 text-text-dark hover:text-sage transition-colors" aria-label="Sign In">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                    @endauth

                    {{-- Dark/Light mode toggle --}}
                    <button x-data="themeToggle()" @click="toggle()"
                            class="p-1.5 transition-colors"
                            style="color:var(--color-text-dark)"
                            aria-label="Toggle dark mode"
                            :title="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    {{-- Cart button with count badge --}}
                    <button @click="cartOpen = true; $dispatch('open-cart')"
                            class="relative p-1.5 text-text-dark hover:text-sage transition-colors" aria-label="Cart"
                            x-data="{ cartCount: {{ app(\App\Services\CartService::class)->getItemCount() }} }"
                            @cart-updated.window="cartCount = $event.detail.count">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span x-show="cartCount > 0"
                              x-text="cartCount > 9 ? '9+' : cartCount"
                              class="absolute -top-1.5 -right-1.5 w-4 h-4 text-white text-[10px] font-bold rounded-full flex items-center justify-center"
                              style="background:#6B2016;"></span>
                    </button>
                </div>
            </div>

            {{-- Search Bar --}}
            <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="py-4 border-t border-sand/30" style="display:none;">
                <form action="{{ route('shop') }}" method="GET" class="relative max-w-2xl mx-auto">
                    <input type="text" name="q" placeholder="Search for diffusers, scents..." class="input-luxury pr-10" autofocus>
                    <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 p-2 text-text-muted hover:text-sage">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </form>
            </div>

            {{-- Mobile Nav --}}
            <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-sand/30 py-4 space-y-1" style="display:none;">
                <a href="{{ route('home') }}" class="block py-2 nav-link">Home</a>
                <a href="{{ route('shop') }}" class="block py-2 nav-link">Shop</a>
                @foreach(\App\Models\Category::active()->orderBy('sort_order')->limit(6)->get() as $cat)
                <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="block py-1.5 pl-4 text-xs tracking-widest uppercase text-text-muted hover:text-sage">{{ $cat->name }}</a>
                @endforeach
                <a href="{{ route('about') }}" class="block py-2 nav-link">Our Story</a>
                <a href="{{ route('blog.index') }}" class="block py-2 nav-link">Journal</a>
                <a href="{{ route('product-request.create') }}" class="block py-2 nav-link">Request a Product</a>
                <a href="{{ route('contact') }}" class="block py-2 nav-link">Contact</a>
                @auth
                <a href="{{ route('account.overview') }}" class="block py-2 nav-link">My Account</a>
                @else
                <a href="{{ route('login') }}" class="block py-2 nav-link">Sign In</a>
                @endauth
            </div>
        </nav>
    </header>

    {{-- Cart Drawer --}}
    <div
        x-data="{
            items: [],
            subtotal: 0,
            csrf() { return document.querySelector('meta[name=csrf-token]').content; },
            async loadCart() {
                try {
                    const res = await fetch('/cart/data', { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    this.items = data.items;
                    this.subtotal = data.subtotal;
                } catch(e) {}
            },
            async updateQty(itemId, qty) {
                try {
                    const res = await fetch('/cart/update', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                        body: JSON.stringify({ item_id: itemId, quantity: qty }),
                    });
                    const data = await res.json();
                    this.items = data.items;
                    this.subtotal = data.subtotal;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                } catch(e) {}
            },
            async removeItem(itemId) {
                try {
                    const res = await fetch('/cart/remove', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                        body: JSON.stringify({ item_id: itemId }),
                    });
                    const data = await res.json();
                    this.items = data.items;
                    this.subtotal = data.subtotal;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                } catch(e) {}
            },
            fmt(n) { return '₦' + Math.round(n).toLocaleString('en-NG'); }
        }"
        @open-cart.window="loadCart()"
        @cart-updated.window="if (cartOpen) loadCart()"
        class="fixed top-0 right-0 h-full w-full sm:w-96 bg-cream z-50 flex flex-col shadow-luxury-lg transition-transform duration-300"
        x-show="cartOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        style="display:none;"
    >
        <div class="flex items-center justify-between px-6 py-5 border-b border-sand/50">
            <h2 class="font-display text-xl text-text-dark">Your Cart</h2>
            <button @click="cartOpen = false" class="p-1.5 text-text-muted hover:text-text-dark transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            <template x-if="items.length === 0">
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <svg class="w-16 h-16 text-sand mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <p class="font-display text-lg text-text-dark mb-2">Your cart is empty</p>
                    <p class="text-sm text-text-muted mb-6">Discover our luxurious diffuser collection</p>
                    <a href="{{ route('shop') }}" @click="cartOpen = false" class="btn-primary text-xs">Shop Now</a>
                </div>
            </template>
            <template x-for="item in items" :key="item.id">
                <div class="flex gap-4 py-3 border-b border-sand/30">
                    <div class="w-20 h-20 bg-sand/20 flex-shrink-0 overflow-hidden">
                        <template x-if="item.product.image">
                            <img :src="'/images/products/' + item.product.image" :alt="item.product.name" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!item.product.image">
                            <div class="w-full h-full bg-sand/30 flex items-center justify-center">
                                <svg class="w-8 h-8 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-text-dark truncate" x-text="item.product.name"></h4>
                        <p class="text-xs text-text-muted" x-show="item.variant" x-text="item.variant ? item.variant.name : ''"></p>
                        <p class="text-sm text-sage font-medium mt-1" x-text="fmt(item.price_at_add)"></p>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="flex items-center border border-sand">
                                <button @click="updateQty(item.id, item.quantity - 1)" class="px-2 py-1 text-text-muted hover:text-text-dark transition-colors text-sm">−</button>
                                <span class="px-3 py-1 text-sm border-x border-sand" x-text="item.quantity"></span>
                                <button @click="updateQty(item.id, item.quantity + 1)" class="px-2 py-1 text-text-muted hover:text-text-dark transition-colors text-sm">+</button>
                            </div>
                            <button @click="removeItem(item.id)" class="text-xs text-text-muted hover:text-red-500 transition-colors underline">Remove</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <template x-if="items.length > 0">
            <div class="border-t border-sand/50 px-6 py-5 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="font-sans text-sm text-text-muted">Subtotal</span>
                    <span class="font-display text-lg text-text-dark" x-text="fmt(subtotal)"></span>
                </div>
                <p class="text-xs text-text-muted">Shipping and taxes calculated at checkout.</p>
                <a href="{{ route('checkout') }}" @click="cartOpen = false" class="btn-primary w-full text-center block">
                    Proceed to Checkout
                </a>
                <a href="{{ route('cart') }}" @click="cartOpen = false" class="btn-secondary w-full text-center block">
                    View Cart
                </a>
            </div>
        </template>
    </div>

    {{-- Cart backdrop --}}
    <div x-show="cartOpen" @click="cartOpen = false" class="fixed inset-0 bg-black/40 z-40 backdrop-blur-sm" x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display:none;"></div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-24 right-4 z-50 bg-sage text-cream px-6 py-3 shadow-luxury text-sm font-sans" x-transition>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-24 right-4 z-50 bg-red-600 text-white px-6 py-3 shadow-lg text-sm font-sans" x-transition>
        {{ session('error') }}
    </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-cream mt-20" style="background:#1a0a06;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">

                {{-- Brand --}}
                <div class="md:col-span-2 lg:col-span-1">
                    <span class="font-display text-2xl tracking-wider">Aurachell</span>
                    <p class="mt-4 text-cream/70 text-sm leading-relaxed">Crafted for calm. Designed for home. Every scent is a story waiting to be told in your space.</p>
                    @php
                        $socials = [
                            'instagram' => ['key'=>'instagram_url','show'=>'show_social_instagram','label'=>'Instagram','svg'=>'<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>'],
                            'facebook'  => ['key'=>'facebook_url','show'=>'show_social_facebook','label'=>'Facebook','svg'=>'<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>'],
                            'twitter'   => ['key'=>'twitter_url','show'=>'show_social_twitter','label'=>'X (Twitter)','svg'=>'<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>'],
                            'tiktok'    => ['key'=>'tiktok_url','show'=>'show_social_tiktok','label'=>'TikTok','svg'=>'<path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.15 8.15 0 004.77 1.52V6.78a4.85 4.85 0 01-1-.09z"/>'],
                            'whatsapp'  => ['key'=>'whatsapp_number','show'=>'show_social_whatsapp','label'=>'WhatsApp','svg'=>'<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>'],
                        ];
                    @endphp
                    <div class="flex items-center gap-3 mt-6">
                        @foreach($socials as $id => $s)
                        @php
                            $rawUrl = \App\Models\Setting::get($s['key']);
                            $shown  = \App\Models\Setting::get($s['show'], '1');
                            if ($id === 'whatsapp' && $rawUrl) {
                                $linkUrl = 'https://wa.me/' . preg_replace('/\D/', '', $rawUrl) . '?text=Hi%20Aurachell%2C%20I%20have%20a%20question';
                            } else {
                                $linkUrl = $rawUrl ?: '#';
                            }
                        @endphp
                        @if($shown !== '0')
                        <a href="{{ $linkUrl }}" {{ $rawUrl ? 'target="_blank" rel="noopener"' : '' }}
                           class="text-cream/60 hover:text-cream transition-colors" aria-label="{{ $s['label'] }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">{!! $s['svg'] !!}</svg>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>

                {{-- Shop --}}
                <div>
                    <h4 class="font-sans text-xs tracking-widest uppercase text-cream/50 mb-5">Shop</h4>
                    <ul class="space-y-3">
                        @foreach(\App\Models\Category::active()->orderBy('sort_order')->limit(5)->get() as $cat)
                        <li><a href="{{ route('shop', ['category' => $cat->slug]) }}" class="text-cream/70 text-sm hover:text-cream transition-colors">{{ $cat->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('shop') }}" class="text-cream/70 text-sm hover:text-cream transition-colors">All Products</a></li>
                    </ul>
                </div>

                {{-- Journal --}}
                <div>
                    <h4 class="font-sans text-xs tracking-widest uppercase text-cream/50 mb-5">Journal</h4>
                    @php
                        $footerPosts = \App\Models\BlogPost::where('is_published', true)
                            ->latest('published_at')->limit(4)->get(['title','slug','published_at']);
                    @endphp
                    <ul class="space-y-3">
                        @forelse($footerPosts as $fp)
                        <li>
                            <a href="{{ route('blog.show', $fp->slug) }}" class="text-cream/70 text-sm hover:text-cream transition-colors leading-snug block">
                                {{ Str::limit($fp->title, 40) }}
                            </a>
                        </li>
                        @empty
                        <li><a href="{{ route('blog.index') }}" class="text-cream/70 text-sm hover:text-cream transition-colors">Read the Journal</a></li>
                        @endforelse
                        <li class="pt-1">
                            <a href="{{ route('blog.index') }}" class="text-xs tracking-widest uppercase text-cream/40 hover:text-cream/70 transition-colors">All Articles →</a>
                        </li>
                    </ul>
                </div>

                {{-- Help --}}
                <div>
                    <h4 class="font-sans text-xs tracking-widest uppercase text-cream/50 mb-5">Help</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('track-order') }}" class="text-cream/70 text-sm hover:text-cream transition-colors">Track Order</a></li>
                        <li><a href="{{ route('faq') }}" class="text-cream/70 text-sm hover:text-cream transition-colors">FAQ</a></li>
                        <li><a href="{{ route('shipping-returns') }}" class="text-cream/70 text-sm hover:text-cream transition-colors">Shipping & Returns</a></li>
                        <li><a href="{{ route('about') }}" class="text-cream/70 text-sm hover:text-cream transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-cream/70 text-sm hover:text-cream transition-colors">Contact Us</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div>
                    <h4 class="font-sans text-xs tracking-widest uppercase text-cream/50 mb-5">Stay in the Loop</h4>
                    <p class="text-cream/70 text-sm mb-4">Be the first to know about new collections and exclusive offers.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex">
                        @csrf
                        <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="email" name="email" placeholder="Your email" required
                               class="flex-1 bg-white/10 border-b border-cream/30 px-3 py-2.5 text-cream placeholder-cream/40 text-sm focus:outline-none focus:border-cream/70 transition-colors min-w-0">
                        <button type="submit" class="bg-sand text-sage px-4 py-2.5 text-xs tracking-widest uppercase font-medium hover:bg-sand/80 transition-colors shrink-0">Join</button>
                    </form>
                </div>

            </div>
            <div class="border-t border-cream/10 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-cream/40 text-xs">© {{ date('Y') }} Aurachell. All rights reserved.</p>
                <div class="flex items-center gap-4 text-xs text-cream/40">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-cream/70 transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-cream/70 transition-colors">Terms</a>
                    <a href="{{ route('cookie-policy') }}" class="hover:text-cream/70 transition-colors">Cookie Policy</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-cream/70 transition-colors">Journal</a>
                </div>
                <div class="flex items-center gap-2 text-cream/40 text-xs tracking-wider uppercase">
                    <span>Paystack</span><span>·</span><span>Secure Checkout</span>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-cream/10 text-center">
                <p class="text-cream/30 text-[11px] tracking-wide">Crafted with love by <a href="https://www.instagram.com/tavs_technology?igsh=czZ6OTg0dDE4a2Q3" target="_blank" rel="noopener" class="text-cream/50 hover:text-cream/80 transition-colors">TavsTechnology</a></p>
            </div>
        </div>
    </footer>

    {{-- WhatsApp click-to-chat button --}}
    @php $waNumber = \App\Models\Setting::get('whatsapp_number'); @endphp
    @if($waNumber)
    <a href="https://wa.me/{{ preg_replace('/\D/', '', $waNumber) }}?text=Hi%20Aurachell%2C%20I%20have%20a%20question"
       target="_blank" rel="noopener"
       class="fixed bottom-24 right-6 z-50 w-12 h-12 rounded-full flex items-center justify-center shadow-luxury-lg transition-transform hover:scale-110"
       style="background:#25D366;"
       aria-label="Chat on WhatsApp">
        <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
    @endif

    {{-- AI Chatbot --}}
    <div class="fixed bottom-6 right-6 z-50"
         x-data="{
             open: false,
             message: '',
             messages: [],
             typing: false,
             csrf() { return document.querySelector('meta[name=csrf-token]').content; },
             async init() {
                 try {
                     const res = await fetch('/chatbot/history', { headers: { 'Accept': 'application/json' } });
                     const data = await res.json();
                     this.messages = data.messages;
                 } catch(e) {}
             },
             async send() {
                 const text = this.message.trim();
                 if (!text) return;
                 this.message = '';
                 this.messages.push({ role: 'user', content: text });
                 this.typing = true;
                 this.$nextTick(() => this.scrollToBottom());
                 try {
                     const history = this.messages.filter(m => m.role !== 'cart_action').slice(0, -1).slice(-19);
                     const res = await fetch('/chatbot', {
                         method: 'POST',
                         headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                         body: JSON.stringify({ message: text, history }),
                     });
                     const data = await res.json();
                     this.messages.push({ role: 'assistant', content: data.reply });
                     if (data.cart_action) {
                         this.messages.push({ role: 'cart_action', product_id: data.cart_action.product_id, name: data.cart_action.name, price: data.cart_action.price, added: false });
                     }
                 } catch(e) {
                     this.messages.push({ role: 'assistant', content: 'Having a moment of calm. Please try again shortly.' });
                 }
                 this.typing = false;
                 this.$nextTick(() => this.scrollToBottom());
             },
             async addToCart(msgIndex) {
                 const msg = this.messages[msgIndex];
                 if (!msg || msg.added) return;
                 try {
                     const res = await fetch('/cart/add', {
                         method: 'POST',
                         headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                         body: JSON.stringify({ product_id: msg.product_id, quantity: 1 }),
                     });
                     const data = await res.json();
                     if (data.success) {
                         this.messages[msgIndex] = { ...msg, added: true };
                         window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                     }
                 } catch(e) {}
             },
             scrollToBottom() {
                 const el = document.getElementById('chat-messages');
                 if (el) el.scrollTop = el.scrollHeight;
             },
             setQuick(q) { this.message = q; }
         }">

        <button @click="open = !open"
                class="w-14 h-14 rounded-full shadow-luxury-lg flex items-center justify-center transition-all duration-300 focus:outline-none group"
                style="background:#6B2016;"
                aria-label="Chat with Aura">
            <svg x-show="!open" class="w-6 h-6 transition-transform duration-200 group-hover:scale-110" style="color:#F5EDE4;" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
            </svg>
            <svg x-show="open" class="w-5 h-5" style="color:#F5EDE4;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 15l-7-7-7 7"/>
            </svg>
            <span x-show="messages.length === 0 && !open" class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white animate-pulse" style="background:#C4A48C;"></span>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="absolute bottom-16 right-0 w-[340px] flex flex-col overflow-hidden shadow-luxury-lg"
             style="display:none; height:460px; background:#1C0F0B; border:1px solid rgba(212,184,160,0.15); border-radius:2px;">

            <div class="px-4 py-3.5 flex items-center gap-3" style="background:#261410; border-bottom:1px solid rgba(212,184,160,0.1);">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-display font-bold shrink-0" style="background:#6B2016;color:#F5EDE4;">A</div>
                <div class="flex-1">
                    <p class="text-sm font-semibold font-sans" style="color:rgba(245,237,228,0.90);">Aura</p>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:#C4A48C;"></span>
                        <p class="text-xs" style="color:rgba(212,184,160,0.50);">Aurachell Assistant · Online</p>
                    </div>
                </div>
                <button @click="open = false" class="p-1 rounded hover:opacity-70 transition-opacity" style="color:rgba(212,184,160,0.40);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chat-messages">
                <template x-if="messages.length === 0">
                    <div>
                        <div class="text-center py-6 px-2">
                            <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center" style="background:rgba(107,32,22,0.3);">
                                <svg class="w-6 h-6" style="color:#C4A48C;" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                            </div>
                            <p class="text-sm leading-relaxed" style="color:rgba(212,184,160,0.60);">
                                Hi! I'm <strong style="color:#C4A48C;">Aura</strong>, your Aurachell fragrance guide.<br>
                                Ask me anything about our diffusers, scents, or orders.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <button @click="setQuick('What diffusers do you have?')"
                                    class="text-xs px-3 py-1.5 rounded-full border transition-colors"
                                    style="border-color:rgba(107,32,22,0.4);color:rgba(196,164,140,0.70);">
                                What diffusers do you have?
                            </button>
                            <button @click="setQuick('How do I track my order?')"
                                    class="text-xs px-3 py-1.5 rounded-full border transition-colors"
                                    style="border-color:rgba(107,32,22,0.4);color:rgba(196,164,140,0.70);">
                                How do I track my order?
                            </button>
                            <button @click="setQuick('Gift recommendations')"
                                    class="text-xs px-3 py-1.5 rounded-full border transition-colors"
                                    style="border-color:rgba(107,32,22,0.4);color:rgba(196,164,140,0.70);">
                                Gift recommendations
                            </button>
                        </div>
                    </div>
                </template>

                <template x-for="(msg, i) in messages" :key="i">
                    <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <template x-if="msg.role === 'assistant' || msg.role === 'cart_action'">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs shrink-0 mr-2 mt-0.5" style="background:#6B2016;color:#F5EDE4;font-weight:600;">A</div>
                        </template>
                        <template x-if="msg.role !== 'cart_action'">
                            <div class="max-w-[78%] px-3 py-2.5 text-sm leading-relaxed rounded-sm"
                                 :style="msg.role === 'user' ? 'background:#6B2016;color:#F5EDE4;' : 'background:rgba(212,184,160,0.08);color:rgba(245,237,228,0.75);border:1px solid rgba(212,184,160,0.08);'"
                                 x-text="msg.content"></div>
                        </template>
                        <template x-if="msg.role === 'cart_action'">
                            <div class="max-w-[78%] px-3 py-2.5 rounded-sm text-sm" style="background:rgba(107,32,22,0.18);border:1px solid rgba(107,32,22,0.35);">
                                <p class="font-medium mb-0.5" style="color:rgba(245,237,228,0.85);" x-text="msg.name"></p>
                                <p class="text-xs mb-2" style="color:rgba(196,164,140,0.70);">₦<span x-text="Number(msg.price).toLocaleString()"></span></p>
                                <button @click="addToCart(i)"
                                        :disabled="msg.added"
                                        class="w-full py-1.5 text-xs tracking-[0.15em] uppercase font-medium transition-all"
                                        :style="msg.added ? 'background:rgba(34,197,94,0.2);color:#4ade80;cursor:default;' : 'background:#6B2016;color:#F5EDE4;'"
                                        x-text="msg.added ? '✓ Added to Cart' : 'Add to Cart'">
                                </button>
                            </div>
                        </template>
                    </div>
                </template>

                <div x-show="typing" class="flex justify-start">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs shrink-0 mr-2" style="background:#6B2016;color:#F5EDE4;font-weight:600;">A</div>
                    <div class="px-3 py-2.5 rounded-sm" style="background:rgba(212,184,160,0.08);">
                        <div class="flex gap-1 items-center">
                            <span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:#C4A48C;animation-delay:0ms;"></span>
                            <span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:#C4A48C;animation-delay:150ms;"></span>
                            <span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:#C4A48C;animation-delay:300ms;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3" style="border-top:1px solid rgba(212,184,160,0.08);background:#261410;">
                <div class="flex items-center gap-2">
                    <input type="text"
                           x-model="message"
                           @keydown.enter.prevent="send()"
                           placeholder="Ask Aura anything..."
                           class="flex-1 text-sm border-0 border-b py-2 focus:outline-none transition-colors"
                           style="background:transparent;color:rgba(245,237,228,0.85);border-color:rgba(212,184,160,0.20);">
                    <button @click="send()"
                            class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:opacity-80"
                            style="background:#6B2016;color:#F5EDE4;">
                        <svg class="w-4 h-4 rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cookie Consent Banner --}}
    <div id="cookie-banner"
         x-data="{
             show: false,
             init() {
                 const consent = localStorage.getItem('aurachell_cookie_consent');
                 if (!consent) this.show = true;
             },
             accept() {
                 localStorage.setItem('aurachell_cookie_consent', 'accepted');
                 this.show = false;
                 if (window.loadGA) loadGA();
             },
             decline() {
                 localStorage.setItem('aurachell_cookie_consent', 'declined');
                 this.show = false;
             }
         }"
         x-show="show"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed bottom-0 left-0 right-0 z-50 px-4 py-4 sm:px-6"
         style="background:#1a0a06;border-top:1px solid rgba(212,184,160,0.15);">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3 flex-1">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="#C4A48C" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs leading-relaxed" style="color:rgba(212,184,160,0.8);">
                    We use cookies to improve your experience, remember your preferences, and analyse site traffic.
                    By clicking <strong style="color:#D4B8A0;">Accept</strong>, you agree to our use of cookies.
                    <a href="{{ route('cookie-policy') }}" class="underline hover:opacity-80 transition-opacity" style="color:#C4A48C;">Learn more</a>
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <button @click="decline()"
                        class="text-xs px-4 py-2 tracking-wider uppercase transition-colors"
                        style="color:rgba(212,184,160,0.5);border:1px solid rgba(212,184,160,0.2);"
                        onmouseover="this.style.color='rgba(212,184,160,0.8)'" onmouseout="this.style.color='rgba(212,184,160,0.5)'">
                    Decline
                </button>
                <button @click="accept()"
                        class="text-xs px-5 py-2 tracking-wider uppercase font-medium transition-opacity hover:opacity-90"
                        style="background:#6B2016;color:#F5EDE4;">
                    Accept
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')

    {{-- PWA Service Worker --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js', { scope: '/' })
                    .catch(() => {});
            });
        }
    </script>

    {{-- OneSignal Web Push --}}
    @if(config('services.onesignal.app_id'))
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "{{ config('services.onesignal.app_id') }}",
                notifyButton: { enable: false },
            });
        });
    </script>
    @endif

    {{-- PWA Install Banner --}}
    <script>
    function pwaInstallBanner() {
        return {
            show: false,
            platform: 'android',
            deferredPrompt: null,

            init() {
                // Never show if permanently dismissed or already installed
                if (localStorage.getItem('pwa_install') === 'never') return;
                if (window.navigator.standalone) return;
                if (window.matchMedia('(display-mode: standalone)').matches) return;

                const ua = navigator.userAgent;
                const isIOS    = /iPad|iPhone|iPod/.test(ua) && !window.MSStream;
                const isIPadOS = /Macintosh/.test(ua) && navigator.maxTouchPoints > 1;
                const isSafari = /^((?!chrome|android).)*safari/i.test(ua);

                if (isIOS || isIPadOS) {
                    this.platform = 'ios';
                    // Only show on Safari — Chrome on iOS can't install PWAs
                    if (isSafari || isIPadOS) {
                        setTimeout(() => { this.show = true; }, 4000);
                    }
                    return;
                }

                // Chrome / Edge / Samsung — fires beforeinstallprompt
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.platform = window.innerWidth >= 1024 ? 'desktop' : 'android';
                    setTimeout(() => { this.show = true; }, 4000);
                });
            },

            async install() {
                if (this.deferredPrompt) {
                    this.deferredPrompt.prompt();
                    await this.deferredPrompt.userChoice;
                    this.deferredPrompt = null;
                }
                this.dismiss('never');
            },

            dismiss(type) {
                if (type === 'never') localStorage.setItem('pwa_install', 'never');
                this.show = false;
            },
        };
    }
    </script>

    <div
        x-data="pwaInstallBanner()"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-6"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-6"
        class="fixed inset-0 z-[9999] flex items-end justify-center p-4 sm:items-center"
        style="display:none"
        @keydown.escape.window="dismiss('later')">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="dismiss('later')"></div>

        {{-- Panel --}}
        <div class="relative w-full max-w-sm shadow-2xl rounded-3xl overflow-hidden" style="background:var(--color-surface)">

            {{-- Drag handle --}}
            <div class="flex justify-center pt-4 pb-1">
                <div class="w-10 h-1 rounded-full opacity-30" style="background:var(--color-text-dark)"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center gap-4 px-6 pt-4 pb-5">
                <div class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0 shadow-lg">
                    <img src="/images/icons/icon-192.png" alt="Aurachell"
                         class="w-full h-full object-cover"
                         onerror="this.style.display='none';this.parentElement.style.cssText='background:var(--color-primary);display:flex;align-items:center;justify-content:center;font-size:1.75rem;color:white'">
                </div>
                <div>
                    <h3 class="font-display text-xl leading-tight" style="color:var(--color-text-dark)">Install Aurachell</h3>
                    <p class="font-sans text-xs mt-1 leading-snug" style="color:var(--color-text-muted)">Add to your home screen for the best experience</p>
                </div>
            </div>

            {{-- Benefits strip --}}
            <div class="flex items-center justify-center gap-4 mx-6 py-3 px-4 rounded-2xl mb-5" style="background:var(--color-base)">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--color-primary)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="font-sans text-[11px] font-medium" style="color:var(--color-text-dark)">Fast</span>
                </div>
                <div class="w-px h-4 opacity-20" style="background:var(--color-text-dark)"></div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--color-primary)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728M15.536 8.464a5 5 0 010 7.072M6.343 6.343a8 8 0 000 11.314"/></svg>
                    <span class="font-sans text-[11px] font-medium" style="color:var(--color-text-dark)">Works Offline</span>
                </div>
                <div class="w-px h-4 opacity-20" style="background:var(--color-text-dark)"></div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--color-primary)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="font-sans text-[11px] font-medium" style="color:var(--color-text-dark)">Home Screen</span>
                </div>
            </div>

            {{-- Steps --}}
            <div class="px-6 pb-5">
                <p class="font-sans text-[10px] tracking-[0.2em] uppercase font-semibold mb-4" style="color:var(--color-text-muted)">How to install</p>
                <div class="space-y-4">

                    {{-- iOS steps --}}
                    <template x-if="platform === 'ios'">
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">1</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap the Share button</p>
                                    <p class="font-sans text-xs mt-0.5 leading-relaxed" style="color:var(--color-text-muted)">The <svg xmlns="http://www.w3.org/2000/svg" class="inline w-3.5 h-3.5 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8M16 6l-4-4-4 4M12 2v13"/></svg> icon at the bottom of your Safari browser</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">2</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap "Add to Home Screen"</p>
                                    <p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Scroll down in the share sheet to find it</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">3</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap "Add" to confirm</p>
                                    <p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Aurachell will appear on your home screen instantly</p>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Android / Chrome steps --}}
                    <template x-if="platform === 'android'">
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">1</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap "Install Now" below</p>
                                    <p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Or tap the ⋮ menu in Chrome and choose "Add to Home Screen"</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">2</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap "Install" in the popup</p>
                                    <p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">A small confirmation will appear at the bottom</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">3</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">You're all set!</p>
                                    <p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Find Aurachell on your home screen like any other app</p>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Desktop steps --}}
                    <template x-if="platform === 'desktop'">
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">1</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Click "Install Now" below</p>
                                    <p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Or click the install icon <svg class="inline w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/><rect x="3" y="3" width="18" height="18" rx="2"/></svg> in your address bar</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">2</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Click "Install" to confirm</p>
                                    <p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">A browser dialog will open asking for confirmation</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">3</span>
                                <div>
                                    <p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Aurachell opens as an app</p>
                                    <p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Find it pinned to your taskbar or desktop</p>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            </div>

            {{-- Divider --}}
            <div class="h-px mx-6 mb-5" style="background:var(--color-base)"></div>

            {{-- Actions --}}
            <div class="px-6 pb-8 space-y-3">
                <button
                    @click="install()"
                    class="w-full py-4 rounded-2xl font-sans font-semibold text-sm tracking-widest uppercase text-white transition-all active:scale-95 flex items-center justify-center gap-2"
                    style="background:var(--color-primary)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span x-text="platform === 'ios' ? 'Got It — I\'ll Install' : 'Install Now'"></span>
                </button>
                <div class="grid grid-cols-2 gap-3">
                    <button
                        @click="dismiss('later')"
                        class="py-3.5 rounded-2xl font-sans text-sm font-medium border transition-all active:scale-95"
                        style="border-color:var(--color-base);color:var(--color-text-muted)">
                        Later
                    </button>
                    <button
                        @click="dismiss('never')"
                        class="py-3.5 rounded-2xl font-sans text-sm font-medium transition-all active:scale-95"
                        style="color:var(--color-text-muted)">
                        Never Show Again
                    </button>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
