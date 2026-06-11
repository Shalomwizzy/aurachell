<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <script>
        (function(){var t=localStorage.getItem('aurachell_theme');if(t==='dark')document.documentElement.classList.add('dark');})();
    </script>
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

    @php
        $favicon = \App\Models\Setting::get('favicon');
        $logo    = \App\Models\Setting::get('logo');
    @endphp
    @if($favicon)
    <link rel="icon" type="image/png" href="{{ asset('images/' . $favicon) }}">
    <link rel="apple-touch-icon" href="{{ asset('images/' . $favicon) }}">
    @else
    <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/icon-32.png">
    @endif

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6B2016">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Aurachell">
    <link rel="apple-touch-icon" href="/images/icons/icon-192.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/images/icons/icon-192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/icon-152.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/icons/icon-144.png">
    <link rel="apple-touch-icon" sizes="128x128" href="/images/icons/icon-128.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Aurachell">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <script src="{{ asset('build/assets/app2.js') }}" defer></script>
    @stack('styles')

    <style>
    :root{
        --color-base:rgba(247,242,235,.60);
        --color-primary:#371220;
        --color-ghost:#C9A96F;
        --color-bg:#F7F2EB;
        --color-surface:#FFFFFF;
        --color-text-dark:#2A2522;
        --color-text-body:#2A2522;
        --color-text-muted:rgba(42,37,34,.55);
        --color-accent:#C9A96F;
        --color-primary-light:rgba(55,18,32,.70);
        --color-primary-dark:rgba(55,18,32,.92);
        --color-surface-hover:rgba(247,242,235,.80);
        --color-border:rgba(201,169,111,.25);
        --color-border-strong:rgba(201,169,111,.45);
        --shadow-luxury:0 4px 30px rgba(42,37,34,.08);
        --shadow-luxury-lg:0 8px 60px rgba(42,37,34,.14);
    }
    html.dark{
        --color-base:rgba(55,18,32,.25);
        --color-bg:#160c0b;
        --color-surface:rgba(55,18,32,.80);
        --color-text-dark:#F7F2EB;
        --color-text-body:rgba(247,242,235,.85);
        --color-text-muted:rgba(247,242,235,.50);
        --color-surface-hover:rgba(55,18,32,.85);
        --color-border:rgba(201,169,111,.20);
        --color-border-strong:rgba(201,169,111,.35);
        --color-accent:#C9A96F;
    }
    /* Theme toggle icons */
    .theme-icon-moon { display: block; }
    .theme-icon-sun  { display: none; }
    html.dark .theme-icon-moon { display: none; }
    html.dark .theme-icon-sun  { display: block; }
    /* Mobile menu — CSS only (no inline style on panel) */
    .menu-icon-x { display: none; }
    #mobile-menu-panel { display: none; }
    body.mobile-menu-open .menu-icon-bars { display: none; }
    body.mobile-menu-open .menu-icon-x { display: block; }
    body.mobile-menu-open #mobile-menu-panel { display: block !important; }
    /* Search bar — CSS only */
    #search-bar { display: none; }
    body.search-open #search-bar { display: block !important; }
    /* Shop dropdown */
    .shop-dropdown-panel { display: none; }
    @media (hover: hover) {
        .shop-dropdown-wrapper:hover .shop-dropdown-panel { display: block !important; }
        .shop-dropdown-wrapper:hover .shop-chevron { transform: rotate(180deg); }
    }
    .shop-dropdown-wrapper.dropdown-open .shop-dropdown-panel { display: block !important; }
    .shop-dropdown-wrapper.dropdown-open .shop-chevron { transform: rotate(180deg); }
    .shop-chevron { transition: transform 0.2s; }
    /* Prevent horizontal overflow on mobile */
    body { overflow-x: hidden; }
    /* Cart drawer slide */
    #cart-drawer { transform: translateX(100%); transition: transform 0.3s ease-out; display: none; }
    #cart-drawer.cart-open { transform: translateX(0); }
    /* Cart backdrop */
    #cart-backdrop { display: none; }
    #cart-backdrop.cart-open { display: block; }
    /* Chatbot icons */
    #chatbot-icon-chat  { display: block; }
    #chatbot-icon-close { display: none; }
    body.chatbot-open #chatbot-icon-chat  { display: none; }
    body.chatbot-open #chatbot-icon-close { display: block; }
    body.chatbot-open #chatbot-pulse { display: none; }
    #chatbot-window { display: none; }
    body.chatbot-open #chatbot-window { display: flex; }
    </style>

    @php $gaId = config('app.google_analytics_id') ?: \App\Models\Setting::get('ga_measurement_id'); @endphp
    @if($gaId)
    <script>
        window._gaId = '{{ $gaId }}';
        function loadGA() {
            if (document.getElementById('ga-script')) return;
            var s = document.createElement('script');
            s.id = 'ga-script'; s.async = true;
            s.src = 'https://www.googletagmanager.com/gtag/js?id=' + window._gaId;
            document.head.appendChild(s);
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            window.gtag = gtag;
            gtag('js', new Date()); gtag('config', window._gaId);
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
            fbq('init', window._fbPixelId); fbq('track', 'PageView');
        }
        if (localStorage.getItem('aurachell_cookie_consent') === 'accepted') { loadFbPixel(); }
        document.addEventListener('cookie-consent-accepted', loadFbPixel);
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"/></noscript>
    @endif
</head>
<body class="bg-surface">

    {{-- Announcement Bar --}}
    @php $announcement = \App\Models\Setting::get('announcement_bar'); @endphp
    @if($announcement && \App\Models\Setting::get('announcement_bar_active'))
    <div id="announce-bar" class="relative bg-sage text-cream text-center py-2.5 px-4 text-xs tracking-widest uppercase font-sans">
        <span>{{ $announcement }}</span>
        <button onclick="document.getElementById('announce-bar').style.display='none'" class="absolute right-4 top-1/2 -translate-y-1/2 text-cream/60 hover:text-cream">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    {{-- Navbar --}}
    <header id="main-header" class="sticky top-0 z-50 transition-all duration-300" style="background:var(--color-bg);">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">

                <a href="{{ route('home') }}" class="flex-shrink-0 min-w-0">
                    @if($logo)
                    <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-9 sm:h-12 w-auto object-contain dark:brightness-0 dark:invert" style="max-width:140px;">
                    @else
                    <span class="font-display text-lg sm:text-2xl text-sage tracking-wider">Aurachell</span>
                    @endif
                </a>

                <div class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                    <div class="shop-dropdown-wrapper relative">
                        <a href="{{ route('shop') }}" class="nav-link flex items-center gap-1 py-2">
                            Shop
                            <svg class="shop-chevron w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="shop-dropdown-panel absolute top-full left-1/2 -translate-x-1/2 pt-3 w-80">
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

                <div class="flex items-center gap-1 sm:gap-2">
                    <button onclick="document.body.classList.toggle('search-open')" class="p-1.5 text-text-dark hover:text-sage transition-colors" aria-label="Search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>

                    @auth
                    @php $wishCount = auth()->user()->wishlist()->count(); @endphp
                    <a href="{{ route('account.wishlist') }}"
                       class="hidden sm:flex relative p-1.5 text-text-dark hover:text-sage transition-colors"
                       aria-label="Wishlist">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        @if($wishCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 text-white text-[10px] font-bold rounded-full flex items-center justify-center" style="background:#371220;">{{ $wishCount > 9 ? '9+' : $wishCount }}</span>
                        @endif
                    </a>
                    <div class="relative hidden sm:block">
                        <button id="account-btn" class="p-1.5 text-text-dark hover:text-sage transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </button>
                        <div id="account-dropdown" class="absolute right-0 top-full mt-2 w-44 bg-cream border border-sand/50 shadow-luxury py-2" style="display:none;">
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

                    {{-- Theme toggle --}}
                    <button onclick="window.ThemeManager && window.ThemeManager.toggle()"
                            class="p-1.5 transition-colors"
                            style="color:var(--color-text-dark)"
                            aria-label="Toggle dark mode">
                        <svg class="w-5 h-5 theme-icon-moon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="w-5 h-5 theme-icon-sun" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    {{-- Cart button --}}
                    <button onclick="window.openCartDrawer()"
                            class="relative p-1.5 text-text-dark hover:text-sage transition-colors" aria-label="Cart">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @php $cartCount = app(\App\Services\CartService::class)->getItemCount(); @endphp
                        <span id="cart-count-badge"
                              class="absolute -top-1.5 -right-1.5 w-4 h-4 text-white text-[10px] font-bold rounded-full flex items-center justify-center"
                              style="background:#371220;display:{{ $cartCount > 0 ? 'flex' : 'none' }};">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    </button>

                    {{-- Hamburger — rightmost, mobile only --}}
                    <button onclick="document.body.classList.toggle('mobile-menu-open')"
                            class="lg:hidden p-2 ml-1 text-text-dark hover:text-sage transition-colors rounded"
                            style="background:rgba(0,0,0,0.06);"
                            aria-label="Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path class="menu-icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/>
                            <path class="menu-icon-x" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Search Bar --}}
            <div id="search-bar" class="py-4 border-t border-sand/30">
                <form action="{{ route('shop') }}" method="GET" class="relative max-w-2xl mx-auto">
                    <input type="text" name="q" placeholder="Search for diffusers, scents..." class="input-luxury pr-10" autofocus>
                    <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 p-2 text-text-muted hover:text-sage">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </form>
            </div>

            {{-- Mobile Nav --}}
            <div id="mobile-menu-panel" class="lg:hidden border-t border-sand/30 py-4 space-y-1" style="background:var(--color-bg);">
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
    <div id="cart-drawer" class="fixed top-0 right-0 h-full w-full sm:w-96 z-50 flex flex-col shadow-luxury-lg" style="background:var(--color-surface);">
        <div class="flex items-center justify-between px-6 py-5 border-b border-sand/50">
            <h2 class="font-display text-xl text-text-dark">Your Cart</h2>
            <button onclick="window.closeCartDrawer()" class="p-1.5 text-text-muted hover:text-text-dark transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="cart-items-container" class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <svg class="w-16 h-16 text-sand mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <p class="font-display text-lg text-text-dark mb-2">Your cart is empty</p>
                <p class="text-sm text-text-muted mb-6">Discover our luxurious diffuser collection</p>
                <a href="{{ route('shop') }}" onclick="window.closeCartDrawer()" class="btn-primary text-xs">Shop Now</a>
            </div>
        </div>
        <div id="cart-footer" class="border-t border-sand/50 px-6 py-5 space-y-4" style="display:none;">
            <div class="flex items-center justify-between">
                <span class="font-sans text-sm text-text-muted">Subtotal</span>
                <span id="cart-subtotal" class="font-display text-lg text-text-dark">₦0</span>
            </div>
            <p class="text-xs text-text-muted">Shipping and taxes calculated at checkout.</p>
            <a href="{{ route('checkout') }}" onclick="window.closeCartDrawer()" class="btn-primary w-full text-center block">Proceed to Checkout</a>
            <a href="{{ route('cart') }}" onclick="window.closeCartDrawer()" class="btn-secondary w-full text-center block">View Cart</a>
        </div>
    </div>

    {{-- Cart backdrop --}}
    <div id="cart-backdrop" onclick="window.closeCartDrawer()" class="fixed inset-0 bg-black/40 z-40 backdrop-blur-sm"></div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="flash-message fixed top-24 right-4 z-50 bg-sage text-cream px-6 py-3 shadow-luxury text-sm font-sans transition-opacity duration-500">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flash-message fixed top-24 right-4 z-50 bg-mahogany text-white px-6 py-3 shadow-lg text-sm font-sans transition-opacity duration-500">
        {{ session('error') }}
    </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-cream mt-20" style="background:#371220;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">

                <div class="md:col-span-2 lg:col-span-1">
                    @if($logo)
                    <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-9 w-auto mb-1 brightness-0 invert opacity-90">
                    @else
                    <span class="font-display text-2xl tracking-wider">Aurachell</span>
                    @endif
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

                <div>
                    <h4 class="font-sans text-xs tracking-widest uppercase text-cream/50 mb-5">Shop</h4>
                    <ul class="space-y-3">
                        @foreach(\App\Models\Category::active()->orderBy('sort_order')->limit(5)->get() as $cat)
                        <li><a href="{{ route('shop', ['category' => $cat->slug]) }}" class="text-cream/70 text-sm hover:text-cream transition-colors">{{ $cat->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('shop') }}" class="text-cream/70 text-sm hover:text-cream transition-colors">All Products</a></li>
                    </ul>
                </div>

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
       style="background:#371220;"
       aria-label="Chat on WhatsApp">
        <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
    @endif

    {{-- AI Chatbot --}}
    <div class="fixed bottom-6 right-6 z-[60]">

        <button id="chatbot-toggle"
                class="w-14 h-14 rounded-full shadow-luxury-lg flex items-center justify-center transition-all duration-300 focus:outline-none group relative"
                style="background:#371220;"
                aria-label="Chat with Aura">
            {{-- Chat bubble icon: shown when closed --}}
            <svg id="chatbot-icon-chat" class="w-6 h-6 transition-transform duration-200 group-hover:scale-110" style="color:#F7F2EB;" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
            </svg>
            {{-- Chevron icon: shown when open --}}
            <svg id="chatbot-icon-close" class="w-5 h-5 absolute" style="color:#F7F2EB;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 15l-7-7-7 7"/>
            </svg>
            {{-- Pulse dot: indicates new message / first visit --}}
            <span id="chatbot-pulse" class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white animate-pulse" style="background:#C9A96F;"></span>
        </button>

        <div id="chatbot-window"
             class="absolute bottom-16 right-0 w-[340px] flex-col overflow-hidden shadow-luxury-lg"
             style="height:460px; background:rgba(55,18,32,0.95); border:1px solid rgba(55,18,32,0.15); border-radius:2px;">

            <div class="px-4 py-3.5 flex items-center gap-3" style="background:rgba(55,18,32,0.90); border-bottom:1px solid rgba(55,18,32,0.1);">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-display font-bold shrink-0" style="background:#371220;color:#F7F2EB;">A</div>
                <div class="flex-1">
                    <p class="text-sm font-semibold font-sans" style="color:rgba(247,242,235,0.90);">Aura</p>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:#C9A96F;"></span>
                        <p class="text-xs" style="color:rgba(201,169,111,0.50);">Aurachell Assistant · Online</p>
                    </div>
                </div>
                <button id="chatbot-close-btn" class="p-1 rounded hover:opacity-70 transition-opacity" style="color:rgba(201,169,111,0.40);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chat-messages" style="flex:1;">
                <div id="chat-welcome">
                    <div class="text-center py-6 px-2">
                        <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center" style="background:rgba(55,18,32,0.3);">
                            <svg class="w-6 h-6" style="color:#C9A96F;" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                        </div>
                        <p class="text-sm leading-relaxed" style="color:rgba(201,169,111,0.60);">
                            Hi! I'm <strong style="color:#C9A96F;">Aura</strong>, your Aurachell fragrance guide.<br>
                            Ask me anything about our diffusers, scents, or orders.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <button class="chat-quick-btn text-xs px-3 py-1.5 rounded-full border transition-colors" style="border-color:rgba(201,169,111,0.4);color:rgba(201,169,111,0.70);" data-q="What diffusers do you have?">What diffusers do you have?</button>
                        <button class="chat-quick-btn text-xs px-3 py-1.5 rounded-full border transition-colors" style="border-color:rgba(201,169,111,0.4);color:rgba(201,169,111,0.70);" data-q="How do I track my order?">How do I track my order?</button>
                        <button class="chat-quick-btn text-xs px-3 py-1.5 rounded-full border transition-colors" style="border-color:rgba(201,169,111,0.4);color:rgba(201,169,111,0.70);" data-q="Gift recommendations">Gift recommendations</button>
                    </div>
                </div>
                <div id="chat-messages-list"></div>
                <div id="chat-typing" style="display:none;" class="flex justify-start">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs shrink-0 mr-2" style="background:#371220;color:#F7F2EB;font-weight:600;">A</div>
                    <div class="px-3 py-2.5 rounded-sm" style="background:rgba(201,169,111,0.08);border:1px solid rgba(201,169,111,0.12);">
                        <div class="flex gap-1 items-center">
                            <span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:#C9A96F;animation-delay:0ms;"></span>
                            <span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:#C9A96F;animation-delay:150ms;"></span>
                            <span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:#C9A96F;animation-delay:300ms;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3" style="border-top:1px solid rgba(55,18,32,0.08);background:rgba(55,18,32,0.90);">
                <div class="flex items-center gap-2">
                    <input type="text" id="chat-input"
                           placeholder="Ask Aura anything..."
                           class="flex-1 text-sm border-0 border-b py-2 focus:outline-none transition-colors"
                           style="background:transparent;color:rgba(247,242,235,0.85);border-color:rgba(201,169,111,0.20);">
                    <button id="chat-send-btn"
                            class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:opacity-80"
                            style="background:#371220;color:#F7F2EB;">
                        <svg class="w-4 h-4 rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cookie Consent Banner --}}
    <div id="cookie-banner"
         class="fixed bottom-0 left-0 right-0 z-50 px-4 py-4 sm:px-6"
         style="background:#371220;border-top:1px solid rgba(55,18,32,0.18);display:none;">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3 flex-1">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="#C9A96F" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs leading-relaxed" style="color:rgba(201,169,111,0.8);">
                    We use cookies to improve your experience, remember your preferences, and analyse site traffic.
                    By clicking <strong style="color:#C9A96F;">Accept</strong>, you agree to our use of cookies.
                    <a href="{{ route('cookie-policy') }}" class="underline hover:opacity-80 transition-opacity" style="color:#C9A96F;">Learn more</a>
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <button id="cookie-decline"
                        class="text-xs px-4 py-2 tracking-wider uppercase transition-colors"
                        style="color:rgba(201,169,111,0.5);border:1px solid rgba(201,169,111,0.20);"
                        onmouseover="this.style.color='#C9A96F'" onmouseout="this.style.color='rgba(201,169,111,0.5)'">
                    Decline
                </button>
                <button id="cookie-accept"
                        class="text-xs px-5 py-2 tracking-wider uppercase font-medium transition-opacity hover:opacity-90"
                        style="background:#C9A96F;color:#1A0800;">
                    Accept
                </button>
            </div>
        </div>
    </div>

    {{-- PWA Install Banner --}}
    <div id="pwa-banner" class="fixed inset-0 z-[9999] flex items-end justify-center p-4 sm:items-center" style="display:none;">
        <div id="pwa-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-sm shadow-2xl rounded-3xl overflow-hidden" style="background:var(--color-surface)">
            <div class="flex justify-center pt-4 pb-1">
                <div class="w-10 h-1 rounded-full opacity-30" style="background:var(--color-text-dark)"></div>
            </div>
            <div class="flex items-center gap-4 px-6 pt-4 pb-5">
                <div class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0 shadow-lg">
                    <img src="/images/icons/icon-192.png" alt="Aurachell" class="w-full h-full object-cover"
                         onerror="this.style.display='none';this.parentElement.style.cssText='background:var(--color-primary);display:flex;align-items:center;justify-content:center;font-size:1.75rem;color:white'">
                </div>
                <div>
                    <h3 class="font-display text-xl leading-tight" style="color:var(--color-text-dark)">Install Aurachell</h3>
                    <p class="font-sans text-xs mt-1 leading-snug" style="color:var(--color-text-muted)">Add to your home screen for the best experience</p>
                </div>
            </div>
            <div class="flex items-center justify-center gap-4 mx-6 py-3 px-4 rounded-2xl mb-5" style="background:var(--color-base)">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--color-primary)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="font-sans text-[11px] font-medium" style="color:var(--color-text-dark)">Fast</span>
                </div>
                <div class="w-px h-4 opacity-20" style="background:var(--color-text-dark)"></div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--color-primary)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636a9 9 0 010 12.728M15.536 8.464a5 5 0 010 7.072M6.343 6.343a8 8 0 000 11.314"/></svg>
                    <span class="font-sans text-[11px] font-medium" style="color:var(--color-text-dark)">Works Offline</span>
                </div>
                <div class="w-px h-4 opacity-20" style="background:var(--color-text-dark)"></div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--color-primary)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="font-sans text-[11px] font-medium" style="color:var(--color-text-dark)">Home Screen</span>
                </div>
            </div>
            <div class="px-6 pb-5">
                <p class="font-sans text-[10px] tracking-[0.2em] uppercase font-semibold mb-4" style="color:var(--color-text-muted)">How to install</p>
                <div id="pwa-steps-ios" class="space-y-4" style="display:none;">
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">1</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap the Share button</p><p class="font-sans text-xs mt-0.5 leading-relaxed" style="color:var(--color-text-muted)">The share icon at the bottom of your Safari browser</p></div></div>
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">2</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap "Add to Home Screen"</p><p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Scroll down in the share sheet to find it</p></div></div>
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">3</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap "Add" to confirm</p><p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Aurachell will appear on your home screen instantly</p></div></div>
                </div>
                <div id="pwa-steps-android" class="space-y-4" style="display:none;">
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">1</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap "Install Now" below</p><p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Or tap the ⋮ menu in Chrome and choose "Add to Home Screen"</p></div></div>
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">2</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Tap "Install" in the popup</p><p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">A small confirmation will appear at the bottom</p></div></div>
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">3</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">You're all set!</p><p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Find Aurachell on your home screen like any other app</p></div></div>
                </div>
                <div id="pwa-steps-desktop" class="space-y-4" style="display:none;">
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">1</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Click "Install Now" below</p><p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Or click the install icon in your address bar</p></div></div>
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">2</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Click "Install" to confirm</p><p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">A browser dialog will open asking for confirmation</p></div></div>
                    <div class="flex items-start gap-3"><span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5" style="background:var(--color-primary)">3</span><div><p class="font-sans text-sm font-medium" style="color:var(--color-text-dark)">Aurachell opens as an app</p><p class="font-sans text-xs mt-0.5" style="color:var(--color-text-muted)">Find it pinned to your taskbar or desktop</p></div></div>
                </div>
            </div>
            <div class="h-px mx-6 mb-5" style="background:var(--color-base)"></div>
            <div class="px-6 pb-8 space-y-3">
                <button id="pwa-install-btn"
                    class="w-full py-4 rounded-2xl font-sans font-semibold text-sm tracking-widest uppercase text-white transition-all active:scale-95 flex items-center justify-center gap-2"
                    style="background:var(--color-primary)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span id="pwa-install-label">Install Now</span>
                </button>
                <div class="grid grid-cols-2 gap-3">
                    <button id="pwa-later-btn" class="py-3.5 rounded-2xl font-sans text-sm font-medium border transition-all active:scale-95" style="border-color:var(--color-base);color:var(--color-text-muted)">Later</button>
                    <button id="pwa-never-btn" class="py-3.5 rounded-2xl font-sans text-sm font-medium transition-all active:scale-95" style="color:var(--color-text-muted)">Never Show Again</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        /* ── Helpers ──────────────────────────────────────── */
        function esc(str) {
            var d = document.createElement('div');
            d.textContent = str || '';
            return d.innerHTML;
        }
        function fmt(n) { return '₦' + Math.round(n).toLocaleString('en-NG'); }
        function csrf() {
            var m = document.querySelector('meta[name=csrf-token]');
            return m ? m.content : '';
        }

        /* ── Mobile menu close on outside click ─────────── */
        document.addEventListener('click', function(e) {
            var mobilePanel = document.getElementById('mobile-menu-panel');
            var menuBtn = document.querySelector('button[aria-label="Menu"]');
            if (document.body.classList.contains('mobile-menu-open')) {
                var header = document.getElementById('main-header');
                if (header && !header.contains(e.target)) {
                    document.body.classList.remove('mobile-menu-open');
                }
            }
        });

        /* ── Scroll header ───────────────────────────────── */
        var header = document.getElementById('main-header');
        window.addEventListener('scroll', function() {
            if (!header) return;
            if (window.scrollY > 20) {
                header.style.borderBottom = '1px solid rgba(201,169,111,0.15)';
                header.style.backdropFilter = 'blur(8px)';
                header.style.boxShadow = '0 4px 30px rgba(42,37,34,0.08)';
            } else {
                header.style.borderBottom = '';
                header.style.backdropFilter = '';
                header.style.boxShadow = '';
            }
        });

        /* ── Shop dropdown (touch devices) ──────────────── */
        var shopWrapper = document.querySelector('.shop-dropdown-wrapper');
        if (shopWrapper) {
            shopWrapper.querySelector('a').addEventListener('click', function(e) {
                if (window.matchMedia('(hover: none)').matches) {
                    e.preventDefault();
                    shopWrapper.classList.toggle('dropdown-open');
                }
            });
            document.addEventListener('click', function(e) {
                if (shopWrapper && !shopWrapper.contains(e.target)) {
                    shopWrapper.classList.remove('dropdown-open');
                }
            });
        }

        /* ── Account dropdown ────────────────────────────── */
        var accountBtn = document.getElementById('account-btn');
        var accountDropdown = document.getElementById('account-dropdown');
        if (accountBtn && accountDropdown) {
            accountBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                var open = accountDropdown.style.display !== 'none';
                accountDropdown.style.display = open ? 'none' : 'block';
            });
            document.addEventListener('click', function() {
                if (accountDropdown) accountDropdown.style.display = 'none';
            });
        }

        /* ── Cart Drawer ─────────────────────────────────── */
        window.openCartDrawer = function() {
            var d = document.getElementById('cart-drawer');
            var b = document.getElementById('cart-backdrop');
            if (!d) return;
            d.style.display = 'flex';
            if (b) b.classList.add('cart-open');
            requestAnimationFrame(function() { d.classList.add('cart-open'); });
            loadCart();
        };
        window.closeCartDrawer = function() {
            var d = document.getElementById('cart-drawer');
            var b = document.getElementById('cart-backdrop');
            if (!d) return;
            d.classList.remove('cart-open');
            if (b) b.classList.remove('cart-open');
            setTimeout(function() { if (!d.classList.contains('cart-open')) d.style.display = 'none'; }, 300);
        };
        window.addEventListener('close-cart', window.closeCartDrawer);
        window.addEventListener('open-cart', window.openCartDrawer);

        function renderCartItems(items, subtotal) {
            var container = document.getElementById('cart-items-container');
            var footer = document.getElementById('cart-footer');
            if (!container) return;
            if (items.length === 0) {
                container.innerHTML = '<div class="flex flex-col items-center justify-center py-16 text-center"><svg class="w-16 h-16 text-sand mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg><p class="font-display text-lg text-text-dark mb-2">Your cart is empty</p><p class="text-sm text-text-muted mb-6">Discover our luxurious diffuser collection</p><a href="/shop" onclick="window.closeCartDrawer()" class="btn-primary text-xs">Shop Now</a></div>';
                if (footer) footer.style.display = 'none';
                return;
            }
            container.innerHTML = items.map(function(item) {
                var img = item.product.image
                    ? '<img src="/images/products/' + esc(item.product.image) + '" alt="' + esc(item.product.name) + '" class="w-full h-full object-cover">'
                    : '<div class="w-full h-full bg-sand/30 flex items-center justify-center"><svg class="w-8 h-8 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>';
                var variantHtml = item.variant ? '<p class="text-xs text-text-muted">' + esc(item.variant.name) + '</p>' : '';
                return '<div class="flex gap-4 py-3 border-b border-sand/30"><div class="w-20 h-20 bg-sand/20 flex-shrink-0 overflow-hidden">' + img + '</div><div class="flex-1 min-w-0"><h4 class="text-sm font-medium text-text-dark truncate">' + esc(item.product.name) + '</h4>' + variantHtml + '<p class="text-sm text-sage font-medium mt-1">' + fmt(item.price_at_add) + '</p><div class="flex items-center gap-3 mt-2"><div class="flex items-center border border-sand"><button class="px-2 py-1 text-text-muted hover:text-text-dark text-sm cart-qty-btn" data-id="' + item.id + '" data-qty="' + (item.quantity - 1) + '">−</button><span class="px-3 py-1 text-sm border-x border-sand">' + item.quantity + '</span><button class="px-2 py-1 text-text-muted hover:text-text-dark text-sm cart-qty-btn" data-id="' + item.id + '" data-qty="' + (item.quantity + 1) + '">+</button></div><button class="text-xs text-text-muted hover:text-mahogany transition-colors underline cart-remove-btn" data-id="' + item.id + '">Remove</button></div></div></div>';
            }).join('');
            if (footer) {
                footer.style.display = 'block';
                var subtotalEl = document.getElementById('cart-subtotal');
                if (subtotalEl) subtotalEl.textContent = fmt(subtotal);
            }
            var c = document.getElementById('cart-items-container');
            if (c) {
                c.addEventListener('click', function(e) {
                    var qb = e.target.closest('.cart-qty-btn');
                    if (qb) { cartUpdateQty(qb.dataset.id, parseInt(qb.dataset.qty)); return; }
                    var rb = e.target.closest('.cart-remove-btn');
                    if (rb) { cartRemove(rb.dataset.id); }
                }, { once: true });
            }
        }

        function loadCart() {
            fetch('/cart/data', { headers: { 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(data) { renderCartItems(data.items, data.subtotal); })
                .catch(function() {});
        }

        function cartUpdateQty(itemId, qty) {
            fetch('/cart/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
                body: JSON.stringify({ item_id: itemId, quantity: qty })
            }).then(function(r) { return r.json(); })
              .then(function(data) {
                  renderCartItems(data.items, data.subtotal);
                  updateCartBadge(data.count);
              }).catch(function() {});
        }

        function cartRemove(itemId) {
            fetch('/cart/remove', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
                body: JSON.stringify({ item_id: itemId })
            }).then(function(r) { return r.json(); })
              .then(function(data) {
                  renderCartItems(data.items, data.subtotal);
                  updateCartBadge(data.count);
              }).catch(function() {});
        }

        function updateCartBadge(count) {
            var badge = document.getElementById('cart-count-badge');
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        window.addEventListener('cart-updated', function(e) {
            if (e.detail && typeof e.detail.count !== 'undefined') {
                updateCartBadge(e.detail.count);
            }
        });

        /* ── Flash messages ──────────────────────────────── */
        document.querySelectorAll('.flash-message').forEach(function(el) {
            setTimeout(function() {
                el.style.opacity = '0';
                setTimeout(function() { if (el.parentNode) el.parentNode.removeChild(el); }, 500);
            }, 4000);
        });

        /* ── Cookie Consent ──────────────────────────────── */
        document.addEventListener('DOMContentLoaded', function() {
            var banner  = document.getElementById('cookie-banner');
            var accept  = document.getElementById('cookie-accept');
            var decline = document.getElementById('cookie-decline');
            if (banner && !localStorage.getItem('aurachell_cookie_consent')) {
                banner.style.display = 'block';
            }
            if (accept) accept.addEventListener('click', function() {
                localStorage.setItem('aurachell_cookie_consent', 'accepted');
                if (banner) banner.style.display = 'none';
                if (window.loadGA) window.loadGA();
                document.dispatchEvent(new Event('cookie-consent-accepted'));
            });
            if (decline) decline.addEventListener('click', function() {
                localStorage.setItem('aurachell_cookie_consent', 'declined');
                if (banner) banner.style.display = 'none';
            });

            /* ── Chatbot ─────────────────────────────────── */
            var chatMessages = [];
            var chatTyping = false;

            var toggleBtn   = document.getElementById('chatbot-toggle');
            var closeBtn    = document.getElementById('chatbot-close-btn');
            var sendBtn     = document.getElementById('chat-send-btn');
            var chatInput   = document.getElementById('chat-input');
            var msgList     = document.getElementById('chat-messages-list');
            var welcome     = document.getElementById('chat-welcome');
            var typing      = document.getElementById('chat-typing');

            function openChatbot() {
                document.body.classList.add('chatbot-open');
                if (chatMessages.length === 0) {
                    fetch('/chatbot/history', { headers: { 'Accept': 'application/json' } })
                        .then(function(r) { return r.json(); })
                        .then(function(d) { chatMessages = d.messages || []; renderChatMessages(); })
                        .catch(function() {});
                }
            }
            function closeChatbot() {
                document.body.classList.remove('chatbot-open');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', function() {
                if (document.body.classList.contains('chatbot-open')) { closeChatbot(); } else { openChatbot(); }
            });
            if (closeBtn) closeBtn.addEventListener('click', closeChatbot);

            function renderChatMessages() {
                if (!msgList) return;
                if (chatMessages.length === 0) {
                    if (welcome) welcome.style.display = 'block';
                    msgList.innerHTML = '';
                } else {
                    if (welcome) welcome.style.display = 'none';
                    msgList.innerHTML = chatMessages.map(function(msg, i) {
                        if (msg.role === 'user') {
                            return '<div class="flex justify-end"><div class="max-w-[78%] px-3 py-2.5 text-sm leading-relaxed rounded-sm" style="background:#371220;color:#F7F2EB;">' + esc(msg.content) + '</div></div>';
                        } else if (msg.role === 'assistant') {
                            return '<div class="flex justify-start"><div class="w-6 h-6 rounded-full flex items-center justify-center text-xs shrink-0 mr-2 mt-0.5" style="background:#371220;color:#F7F2EB;font-weight:600;">A</div><div class="max-w-[78%] px-3 py-2.5 text-sm leading-relaxed rounded-sm" style="background:rgba(201,169,111,0.08);color:rgba(247,242,235,0.80);border:1px solid rgba(201,169,111,0.14);">' + esc(msg.content) + '</div></div>';
                        } else if (msg.role === 'cart_action') {
                            var btnStyle = msg.added ? 'background:rgba(201,169,111,0.12);color:#C9A96F;cursor:default;' : 'background:#371220;color:#F7F2EB;';
                            var btnText = msg.added ? '✓ Added to Cart' : 'Add to Cart';
                            return '<div class="flex justify-start"><div class="w-6 h-6 rounded-full flex items-center justify-center text-xs shrink-0 mr-2 mt-0.5" style="background:#371220;color:#F7F2EB;font-weight:600;">A</div><div class="max-w-[78%] px-3 py-2.5 rounded-sm text-sm" style="background:rgba(55,18,32,0.18);border:1px solid rgba(55,18,32,0.35);"><p class="font-medium mb-0.5" style="color:rgba(247,242,235,0.85);">' + esc(msg.name) + '</p><p class="text-xs mb-2" style="color:rgba(201,169,111,0.70);">₦' + Number(msg.price).toLocaleString() + '</p><button class="w-full py-1.5 text-xs tracking-[0.15em] uppercase font-medium transition-all chat-cart-btn" data-index="' + i + '" ' + (msg.added ? 'disabled' : '') + ' style="' + btnStyle + '">' + btnText + '</button></div></div>';
                        }
                        return '';
                    }).join('');
                }
                if (typing) typing.style.display = chatTyping ? 'flex' : 'none';
                var scrollEl = document.getElementById('chat-messages');
                if (scrollEl) scrollEl.scrollTop = scrollEl.scrollHeight;
            }

            if (msgList) {
                msgList.addEventListener('click', function(e) {
                    var btn = e.target.closest('.chat-cart-btn');
                    if (!btn || btn.disabled) return;
                    var idx = parseInt(btn.dataset.index);
                    var msg = chatMessages[idx];
                    if (!msg || msg.added) return;
                    fetch('/cart/add', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
                        body: JSON.stringify({ product_id: msg.product_id, quantity: 1 })
                    }).then(function(r) { return r.json(); })
                      .then(function(data) {
                          if (data.success) {
                              chatMessages[idx] = Object.assign({}, msg, { added: true });
                              updateCartBadge(data.count);
                              renderChatMessages();
                          }
                      }).catch(function() {});
                });
            }

            document.querySelectorAll('.chat-quick-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    if (chatInput) { chatInput.value = btn.dataset.q; chatInput.focus(); }
                });
            });

            function sendChat() {
                if (!chatInput) return;
                var text = chatInput.value.trim();
                if (!text) return;
                chatInput.value = '';
                chatMessages.push({ role: 'user', content: text });
                chatTyping = true;
                renderChatMessages();
                var history = chatMessages.filter(function(m) { return m.role !== 'cart_action'; }).slice(0, -1).slice(-19);
                fetch('/chatbot', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
                    body: JSON.stringify({ message: text, history: history })
                }).then(function(r) { return r.json(); })
                  .then(function(data) {
                      chatMessages.push({ role: 'assistant', content: data.reply });
                      if (data.cart_action) {
                          chatMessages.push({ role: 'cart_action', product_id: data.cart_action.product_id, name: data.cart_action.name, price: data.cart_action.price, added: false });
                      }
                      chatTyping = false;
                      renderChatMessages();
                  }).catch(function() {
                      chatMessages.push({ role: 'assistant', content: 'Having a moment of calm. Please try again shortly.' });
                      chatTyping = false;
                      renderChatMessages();
                  });
            }

            if (sendBtn) sendBtn.addEventListener('click', sendChat);
            if (chatInput) chatInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); sendChat(); } });

            /* ── PWA Install Banner ───────────────────────── */
            var pwaBanner      = document.getElementById('pwa-banner');
            var pwaInstallBtn  = document.getElementById('pwa-install-btn');
            var pwaInstallLbl  = document.getElementById('pwa-install-label');
            var pwaLaterBtn    = document.getElementById('pwa-later-btn');
            var pwaNeverBtn    = document.getElementById('pwa-never-btn');
            var pwaBackdrop    = document.getElementById('pwa-backdrop');
            var pwaStepsIos    = document.getElementById('pwa-steps-ios');
            var pwaStepsAndroid = document.getElementById('pwa-steps-android');
            var pwaStepsDesktop = document.getElementById('pwa-steps-desktop');
            var pwaDeferredPrompt = null;
            var pwaPlatform = 'android';

            function showPwa(platform) {
                if (!pwaBanner) return;
                pwaPlatform = platform;
                if (pwaStepsIos) pwaStepsIos.style.display = platform === 'ios' ? 'block' : 'none';
                if (pwaStepsAndroid) pwaStepsAndroid.style.display = platform === 'android' ? 'block' : 'none';
                if (pwaStepsDesktop) pwaStepsDesktop.style.display = platform === 'desktop' ? 'block' : 'none';
                if (pwaInstallLbl) pwaInstallLbl.textContent = platform === 'ios' ? "Got It — I'll Install" : 'Install Now';
                pwaBanner.style.display = 'flex';
            }
            function dismissPwa(type) {
                if (type === 'never') localStorage.setItem('pwa_install', 'never');
                if (pwaBanner) pwaBanner.style.display = 'none';
            }

            if (!localStorage.getItem('pwa_install') && !window.navigator.standalone && !window.matchMedia('(display-mode: standalone)').matches) {
                var ua = navigator.userAgent;
                var isIOS    = /iPad|iPhone|iPod/.test(ua) && !window.MSStream;
                var isIPadOS = /Macintosh/.test(ua) && navigator.maxTouchPoints > 1;
                var isSafari = /^((?!chrome|android).)*safari/i.test(ua);
                if (isIOS || isIPadOS) {
                    if (isSafari || isIPadOS) { setTimeout(function() { showPwa('ios'); }, 4000); }
                } else {
                    window.addEventListener('beforeinstallprompt', function(e) {
                        e.preventDefault();
                        pwaDeferredPrompt = e;
                        setTimeout(function() { showPwa(window.innerWidth >= 1024 ? 'desktop' : 'android'); }, 4000);
                    });
                }
            }

            if (pwaInstallBtn) pwaInstallBtn.addEventListener('click', function() {
                if (pwaDeferredPrompt) {
                    pwaDeferredPrompt.prompt();
                    pwaDeferredPrompt.userChoice.then(function() { pwaDeferredPrompt = null; dismissPwa('never'); });
                } else { dismissPwa('never'); }
            });
            if (pwaLaterBtn) pwaLaterBtn.addEventListener('click', function() { dismissPwa('later'); });
            if (pwaNeverBtn) pwaNeverBtn.addEventListener('click', function() { dismissPwa('never'); });
            if (pwaBackdrop) pwaBackdrop.addEventListener('click', function() { dismissPwa('later'); });
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && pwaBanner && pwaBanner.style.display !== 'none') { dismissPwa('later'); } });

        }); // end DOMContentLoaded
    })();
    </script>

    @stack('scripts')

    {{-- PWA Service Worker --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(function() {});
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

</body>
</html>
