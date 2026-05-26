<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Aurachell</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --adm-bg:           #130B09;
            --adm-sidebar:      #1A0E0B;
            --adm-surface:      #211410;
            --adm-surface-alt:  #2a1410;
            --adm-border:       rgba(212,185,154,0.16);
            --adm-text:         #F0E4D6;
            --adm-text-strong:  #FFFFFF;
            --adm-muted:        #B89C82;          /* solid, 6.7:1 contrast on dark bg */
            --adm-accent:       #371220;
            --adm-gold:         #D4B89C;          /* slightly stronger gold for visibility */
            --adm-success-bg:   rgba(34,197,94,0.12);
            --adm-success-fg:   #86efac;
            --adm-warn-bg:      rgba(234,179,8,0.12);
            --adm-warn-fg:      #fde68a;
            --adm-danger-bg:    rgba(239,68,68,0.12);
            --adm-danger-fg:    #fca5a5;
            --adm-info-bg:      rgba(59,130,246,0.12);
            --adm-info-fg:      #93c5fd;
        }
        body { background: var(--adm-bg); color: var(--adm-text); }
        .adm-sidebar { background: var(--adm-sidebar); border-right: 1px solid var(--adm-border); }
        .adm-topbar  { background: var(--adm-surface); border-bottom: 1px solid var(--adm-border); }
        .adm-card    { background: var(--adm-surface); border: 1px solid var(--adm-border); border-radius: 6px; }
        .adm-nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px; font-size: 13px; border-radius: 4px;
            color: var(--adm-text); transition: all .15s;
            white-space: nowrap; text-decoration: none;
            font-weight: 500;
        }
        .adm-nav-item:hover { background: rgba(212,185,154,0.10); color: var(--adm-text-strong); }
        .adm-nav-item.active {
            background: rgba(55,18,32,0.30);
            color: var(--adm-gold);
            border-left: 2px solid var(--adm-accent);
            padding-left: 12px;
            font-weight: 600;
        }
        .adm-group-label {
            padding: 14px 14px 6px;
            font-size: 10px;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--adm-muted);
            font-weight: 600;
        }
        .adm-group-chevron { transition: transform .2s; }
        .adm-group-chevron.open { transform: rotate(180deg); }
        /* Scrollbar */
        .adm-sidebar::-webkit-scrollbar { width: 3px; }
        .adm-sidebar::-webkit-scrollbar-thumb { background: rgba(201,169,111,0.12); }
        nav::-webkit-scrollbar { width: 4px; }
        nav::-webkit-scrollbar-thumb { background: rgba(201,169,111,0.15); border-radius: 2px; }

        /* Light mode overrides */
        .adm-light {
            --adm-bg:           #FAF5ED;
            --adm-sidebar:      #E0CFB8;          /* darker so sidebar separates from canvas */
            --adm-surface:      #FFFFFF;
            --adm-surface-alt:  #F9F3EC;
            --adm-border:       rgba(55,18,32,0.20);
            --adm-text:         #1E0C14;
            --adm-text-strong:  #1a0805;
            --adm-muted:        #7a4a3e;          /* solid, 7.9:1 on white / 6.8:1 on cream */
            --adm-accent:       #371220;
            --adm-gold:         #371220;
            --adm-success-bg:   rgba(34,197,94,0.10);
            --adm-success-fg:   #166534;
            --adm-warn-bg:      rgba(234,179,8,0.12);
            --adm-warn-fg:      #92400e;
            --adm-danger-bg:    rgba(239,68,68,0.10);
            --adm-danger-fg:    #b91c1c;
            --adm-info-bg:      rgba(59,130,246,0.10);
            --adm-info-fg:      #1e40af;
        }
        .adm-light .adm-sidebar::-webkit-scrollbar-thumb { background: rgba(55,18,32,0.25); }
        .adm-light .adm-nav-item { color: #1E0C14; }
        .adm-light .adm-nav-item:hover { background: rgba(55,18,32,0.10); color: #1a0805; }
        .adm-light .adm-nav-item.active { background: rgba(55,18,32,0.18); border-left-color: #371220; color: #371220; font-weight: 700; }
        .adm-light .adm-group-label { color: #371220; font-weight: 700; opacity: 0.85; }

        /* ── ADMIN-WIDE TAILWIND OVERRIDES ────────────────────────────
           Force all generic Tailwind grays/whites used in legacy admin
           views to follow the admin theme variables. This avoids
           per-file rewrites and keeps light + dark themes consistent. */

        /* Backgrounds */
        body .bg-white,
        body .bg-gray-50,
        body .bg-gray-100,
        body .bg-white\/95,
        body .bg-white\/90 { background-color: var(--adm-surface) !important; }
        body .bg-white\/5,
        body .bg-white\/10,
        body .bg-black\/5 { background-color: var(--adm-surface-alt) !important; }

        /* Text — primary */
        body .text-gray-900,
        body .text-gray-800,
        body .text-gray-700,
        body .text-black,
        body .text-white,
        body .text-white\/95,
        body .text-white\/90,
        body .text-white\/85,
        body .text-white\/80,
        body .text-white\/70 { color: var(--adm-text) !important; }

        /* Text — muted (all fine-grain opacity variants) */
        body .text-gray-600,
        body .text-gray-500,
        body .text-gray-400,
        body .text-gray-300,
        body .text-white\/60,
        body .text-white\/55,
        body .text-white\/50,
        body .text-white\/45,
        body .text-white\/40,
        body .text-white\/35,
        body .text-white\/30 { color: var(--adm-muted) !important; }

        /* Text — very faint (map to border-level, not invisible) */
        body .text-white\/25,
        body .text-white\/20,
        body .text-white\/15,
        body .text-white\/10 { color: var(--adm-border) !important; }
        /* But in light mode those should still be readable */
        .adm-light .text-white\/25,
        .adm-light .text-white\/20,
        .adm-light .text-white\/15,
        .adm-light .text-white\/10 { color: var(--adm-muted) !important; }

        /* Borders */
        body .border-gray-200,
        body .border-gray-100,
        body .border-gray-300,
        body .border-white\/10,
        body .border-white\/5,
        body .border-white\/15,
        body .border-white\/20 { border-color: var(--adm-border) !important; }

        /* Divide-y children */
        body .divide-gray-50 > :not([hidden]) ~ :not([hidden]),
        body .divide-gray-100 > :not([hidden]) ~ :not([hidden]),
        body .divide-gray-200 > :not([hidden]) ~ :not([hidden]),
        body .divide-white\/10 > :not([hidden]) ~ :not([hidden]) { border-color: var(--adm-border) !important; }

        /* Hover */
        body .hover\:bg-gray-50:hover,
        body .hover\:bg-gray-100:hover { background-color: var(--adm-surface-alt) !important; }
        body .hover\:text-white:hover { color: var(--adm-text-strong) !important; }
        body .hover\:text-sage:hover { color: var(--adm-gold) !important; }

        /* Status badges (orders, stock) — ensure light + dark legibility */
        body .bg-green-50, body .bg-green-100 { background-color: var(--adm-success-bg) !important; }
        body .text-green-600, body .text-green-700, body .text-green-800 { color: var(--adm-success-fg) !important; }
        body .bg-blue-50, body .bg-blue-100 { background-color: var(--adm-info-bg) !important; }
        body .text-blue-600, body .text-blue-700, body .text-blue-800 { color: var(--adm-info-fg) !important; }
        body .bg-yellow-50, body .bg-yellow-100 { background-color: var(--adm-warn-bg) !important; }
        body .text-yellow-600, body .text-yellow-700, body .text-yellow-800 { color: var(--adm-warn-fg) !important; }
        body .bg-red-50, body .bg-red-100 { background-color: var(--adm-danger-bg) !important; }
        body .text-red-600, body .text-red-700, body .text-red-800 { color: var(--adm-danger-fg) !important; }
        body .bg-purple-50, body .bg-purple-100 { background-color: rgba(168,85,247,0.12) !important; }
        body .text-purple-600, body .text-purple-700, body .text-purple-800 { color: #c4b5fd !important; }
        .adm-light .text-purple-600, .adm-light .text-purple-700, .adm-light .text-purple-800 { color: #6d28d9 !important; }

        /* Accent helpers used in admin views */
        body .text-sage { color: var(--adm-gold) !important; }
        body .text-mahogany { color: var(--adm-gold) !important; }

        /* Form controls — use real focus colors */
        body input:not([type=checkbox]):not([type=radio]):not([type=file]),
        body select,
        body textarea {
            background-color: var(--adm-surface-alt);
            color: var(--adm-text);
            border-color: var(--adm-border);
        }
        body input:focus:not([type=checkbox]):not([type=radio]):not([type=file]),
        body select:focus,
        body textarea:focus {
            outline: none;
            border-color: var(--adm-gold);
            box-shadow: 0 0 0 1px var(--adm-gold);
        }
        body input::placeholder,
        body textarea::placeholder { color: var(--adm-muted); opacity: 0.6; }

        /* ── Override hardcoded hex Tailwind arbitrary-value classes ──
           Many legacy admin views use bg-[#1E1E1E], border-[#2A2A2A], etc.
           These cannot be themed via class names alone, so override directly. */
        body .bg-\[\#1E1E1E\], body .bg-\[\#1e1e1e\],
        body .bg-\[\#1A0E0B\], body .bg-\[\#1a0e0b\],
        body .bg-\[\#211410\] { background-color: var(--adm-surface) !important; }
        body .bg-\[\#2A2A2A\], body .bg-\[\#2a2a2a\],
        body .bg-\[\#3A3A3A\], body .bg-\[\#3a3a3a\] { background-color: var(--adm-surface-alt) !important; }
        body .border-\[\#2A2A2A\], body .border-\[\#2a2a2a\],
        body .border-\[\#3A3A3A\], body .border-\[\#3a3a3a\],
        body .border-\[\#1E1E1E\] { border-color: var(--adm-border) !important; }
        body .hover\:bg-\[\#3A3A3A\]:hover,
        body .hover\:bg-\[\#2A2A2A\]:hover { background-color: var(--adm-surface-alt) !important; filter: brightness(1.08); }
        body .hover\:border-\[\#3A3A3A\]:hover,
        body .hover\:border-\[\#5A5A5A\]:hover { border-color: var(--adm-gold) !important; }
        body .placeholder-gray-600::placeholder { color: var(--adm-muted) !important; opacity: 0.55; }

        /* Hardcoded "sage" green button styles in legacy views */
        body .bg-sage,
        body .hover\:bg-sage:hover,
        body .hover\:bg-sage-800:hover { background-color: var(--adm-accent) !important; color: #FAF5ED !important; }
        body .text-cream { color: #FAF5ED !important; }
        body .accent-sage { accent-color: var(--adm-accent) !important; }
        body .bg-\[\#2F4A3A\] { background-color: var(--adm-accent) !important; }

        /* Common label/input legacy classes used in many admin pages */
        body .admin-label {
            display: block; font-size: 10px; letter-spacing: 0.2em;
            text-transform: uppercase; color: var(--adm-muted) !important;
            margin-bottom: 8px; font-weight: 500;
        }
        body .admin-input {
            width: 100%; background-color: var(--adm-surface-alt) !important;
            border: 1px solid var(--adm-border) !important;
            padding: 10px 14px; font-size: 13px;
            color: var(--adm-text) !important; border-radius: 4px;
            transition: border-color .15s, box-shadow .15s;
        }
        body .admin-input:focus {
            outline: none;
            border-color: var(--adm-gold) !important;
            box-shadow: 0 0 0 1px var(--adm-gold);
        }
        body .admin-input::placeholder { color: var(--adm-muted); opacity: 0.55; }

        /* Pulse unread */
        @keyframes adm-pulse { 0%,100%{transform:scale(1)}50%{transform:scale(1.15)} }
        .pulse { animation: adm-pulse 2s ease-in-out infinite; }
    </style>
    @php $gaId = config('app.google_analytics_id') ?: \App\Models\Setting::get('ga_measurement_id'); @endphp
    @if($gaId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
    @endif
</head>
<body class="h-full font-sans antialiased"
      :class="darkMode ? '' : 'adm-light'"
      x-data="{
          sidebar: localStorage.getItem('adm-sb') !== 'collapsed',
          darkMode: localStorage.getItem('adm-dark') !== '0',
          toggleSidebar() { this.sidebar = !this.sidebar; localStorage.setItem('adm-sb', this.sidebar ? 'open' : 'collapsed'); },
          toggleTheme() { this.darkMode = !this.darkMode; localStorage.setItem('adm-dark', this.darkMode ? '1' : '0'); },
          mobileMenu: false,
      }">

@php
if (!function_exists('adminNavItem')) {
    function adminNavItem($route, $label, $iconPath, $current, $sidebar = true) {
        $active = str_starts_with($current, str_replace('.index','',$route)) || str_starts_with($current, str_replace('.index','',str_replace('admin.','',$route)));
        $cls = $active ? 'adm-nav-item active' : 'adm-nav-item';
        $url = route($route);
        return "<a href=\"{$url}\" class=\"{$cls}\">
            <svg class=\"w-4 h-4 flex-shrink-0\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"{$iconPath}\"/></svg>
            <span class=\"nav-label text-[13px]\">{$label}</span>
        </a>";
    }
}
@endphp
<div class="flex h-screen overflow-hidden">

    {{-- ── MOBILE SIDEBAR OVERLAY ─────────────────────────────── --}}
    <div x-show="mobileMenu" x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 lg:hidden" style="background:rgba(0,0,0,0.6);"
         x-on:click="mobileMenu=false" style="display:none;"></div>

    <aside x-show="mobileMenu" x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
           class="adm-sidebar fixed inset-y-0 left-0 z-50 w-64 flex flex-col overflow-y-auto lg:hidden"
           style="display:none;">
        <div class="flex items-center justify-between px-4 py-5 border-b flex-shrink-0" style="border-color:var(--adm-border);">
            <div class="flex items-center gap-3">
                @php $logo = \App\Models\Setting::get('logo'); @endphp
                <div class="w-7 h-7 flex-shrink-0 flex items-center justify-center rounded-sm" style="background:rgba(55,18,32,0.3);">
                    @if($logo)<img src="{{ asset('images/' . $logo) }}" alt="" class="w-full h-full object-contain rounded-sm">
                    @else<span class="font-display text-sm font-bold" style="color:var(--adm-gold);">A</span>@endif
                </div>
                <p class="font-display text-sm tracking-wider" style="color:var(--adm-gold);">Aurachell</p>
            </div>
            <button x-on:click="mobileMenu=false" style="color:var(--adm-muted);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 py-3 space-y-0.5 overflow-y-auto">
            @php
                $cr = request()->route()->getName();
                $lowStockNav = \App\Models\Product::where('is_active',true)->where('stock_quantity','<=',3)->count();
                $pendingOrders = \App\Models\Order::where('status','pending')->count();
                $paidOrders    = \App\Models\Order::where('payment_status','paid')->count();
            @endphp
            <div class="px-2">
                {!! adminNavItem('admin.dashboard', 'Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', $cr) !!}
            </div>

            {{-- CATALOG --}}
            <p class="adm-group-label mt-2">Catalog</p>
            <div class="px-2 space-y-0.5">
                {!! adminNavItem('admin.products.index', 'Products', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', $cr) !!}
                {!! adminNavItem('admin.categories.index', 'Categories', 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', $cr) !!}
                {!! adminNavItem('admin.reviews.index', 'Reviews', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', $cr) !!}
                {!! adminNavItem('admin.blog.index', 'Blog', 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h2', $cr) !!}
                <a href="{{ route('admin.products.low-stock') }}" class="{{ $cr === 'admin.products.low-stock' ? 'adm-nav-item active' : 'adm-nav-item' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="nav-label">Low Stock</span>
                    @if($lowStockNav > 0)<span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full" style="background:rgba(251,191,36,0.2);color:#fbbf24;">{{ $lowStockNav }}</span>@endif
                </a>
                <a href="{{ route('admin.stock.reservations') }}" class="{{ $cr === 'admin.stock.reservations' ? 'adm-nav-item active' : 'adm-nav-item' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span class="nav-label">Reservations</span>
                </a>
            </div>

            {{-- ORDERS --}}
            <p class="adm-group-label mt-2">Orders</p>
            <div class="px-2 space-y-0.5">
                {!! adminNavItem('admin.orders.index', 'All Orders', 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', $cr) !!}
                <a href="{{ route('admin.orders.index', ['status'=>'pending']) }}" class="{{ ($cr==='admin.orders.index'&&request('status')==='pending') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="nav-label">Pending</span>
                    @if($pendingOrders > 0)<span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full" style="background:rgba(55,18,32,0.35);color:var(--adm-gold);">{{ $pendingOrders }}</span>@endif
                </a>
                <a href="{{ route('admin.orders.index', ['payment_status'=>'paid']) }}" class="{{ ($cr==='admin.orders.index'&&request('payment_status')==='paid') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="nav-label">Paid</span>
                    @if($paidOrders > 0)<span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full" style="background:rgba(22,163,74,0.20);color:#4ade80;">{{ $paidOrders }}</span>@endif
                </a>
                <a href="{{ route('admin.orders.index', ['status'=>'processing']) }}" class="{{ ($cr==='admin.orders.index'&&request('status')==='processing') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span class="nav-label">Processing</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status'=>'packed']) }}" class="{{ ($cr==='admin.orders.index'&&request('status')==='packed') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span class="nav-label">Packed</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status'=>'shipped']) }}" class="{{ ($cr==='admin.orders.index'&&request('status')==='shipped') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 17l4 4 4-4m-4-5v9M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"/></svg>
                    <span class="nav-label">Shipped</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status'=>'delivered']) }}" class="{{ ($cr==='admin.orders.index'&&request('status')==='delivered') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="nav-label">Delivered</span>
                </a>
                {!! adminNavItem('admin.returns.index', 'Returns', 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', $cr) !!}
            </div>

            {{-- FINANCE --}}
            <p class="adm-group-label mt-2">Finance</p>
            <div class="px-2 space-y-0.5">
                {!! adminNavItem('admin.coupons.index', 'Coupons', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', $cr) !!}
                {!! adminNavItem('admin.reports.index', 'Reports & Analytics', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', $cr) !!}
                {!! adminNavItem('admin.analytics.index', 'Google Analytics', 'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', $cr) !!}
            </div>

            {{-- CUSTOMERS --}}
            <p class="adm-group-label mt-2">Customers</p>
            <div class="px-2 space-y-0.5">
                {!! adminNavItem('admin.customers.index', 'All Customers', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', $cr) !!}
                {!! adminNavItem('admin.messages.index', 'Messages', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', $cr) !!}
                {!! adminNavItem('admin.newsletter.index', 'Newsletter', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', $cr) !!}
                {!! adminNavItem('admin.email-campaigns.index', 'Email Campaigns', 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8', $cr) !!}
            </div>

            {{-- REFERRALS --}}
            <p class="adm-group-label mt-2">Referrals</p>
            <div class="px-2 space-y-0.5">
                {!! adminNavItem('admin.referrals.index', 'Overview', 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z', $cr) !!}
                {!! adminNavItem('admin.referrals.settings', 'Program Settings', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', $cr) !!}
            </div>

            {{-- AI STUDIO --}}
            <p class="adm-group-label mt-2">AI Studio</p>
            <div class="px-2 space-y-0.5">
                {!! adminNavItem('admin.ai.assistant', 'AI Assistant', 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', $cr) !!}
                {!! adminNavItem('admin.chat.index', 'Chat Logs', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', $cr) !!}
            </div>

            {{-- SYSTEM --}}
            @if(auth()->user()->hasAnyRole(['super_admin','admin']))
            <p class="adm-group-label mt-2">System</p>
            <div class="px-2 space-y-0.5">
                {!! adminNavItem('admin.staff.index', 'Staff', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', $cr) !!}
                {!! adminNavItem('admin.shipping.index', 'Shipping Zones', 'M8 17l4 4 4-4m-4-5v9M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29', $cr) !!}
                {!! adminNavItem('admin.backups.index', 'Backups', 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', $cr) !!}
                {!! adminNavItem('admin.pages.index', 'Pages', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', $cr) !!}
                {!! adminNavItem('admin.settings.index', 'Settings', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', $cr) !!}
                {!! adminNavItem('admin.activity.index', 'Activity Log', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', $cr) !!}
            </div>
            @endif
        </nav>
    </aside>

    {{-- ── DESKTOP SIDEBAR ─────────────────────────────────────── --}}
    <aside class="adm-sidebar admin-sidebar flex-shrink-0 hidden lg:flex flex-col h-full overflow-y-auto transition-all duration-300"
           :class="sidebar ? 'w-[232px]' : 'w-14'">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-4 py-5 border-b flex-shrink-0" style="border-color:var(--adm-border); min-height:72px;">
            @php $logo = \App\Models\Setting::get('logo'); @endphp
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-sm" style="background:rgba(55,18,32,0.3);">
                @if($logo)
                <img src="{{ asset('images/' . $logo) }}" alt="" class="w-full h-full object-contain rounded-sm">
                @else
                <span class="font-display text-sm font-bold" style="color:var(--adm-gold);">A</span>
                @endif
            </div>
            <div class="nav-label overflow-hidden" x-show="sidebar">
                <p class="font-display text-sm tracking-wider logo-text" style="color:var(--adm-gold);">Aurachell</p>
                <p class="text-[9px] tracking-[.2em] uppercase mt-0.5" style="color:var(--adm-muted);">Admin Console</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav id="adm-nav" class="flex-1 py-3 space-y-0.5 overflow-y-auto"
             style="scrollbar-width:thin;scrollbar-color:rgba(201,169,111,0.15) transparent;">
            @php
            $currentRoute = request()->route()->getName();
            $lowStockNav = \App\Models\Product::where('is_active',true)->where('stock_quantity','<=',3)->count();
            @endphp

            {{-- Dashboard --}}
            <div class="px-2">
                {!! adminNavItem('admin.dashboard', 'Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', $currentRoute) !!}
            </div>

            {{-- ── CATALOG ──────────────────────────── --}}
            @php $u = auth()->user(); @endphp
            @if($u->hasAnyRole(['super_admin','admin']) || $u->hasAnyPermission(['products.view','categories.manage','reviews.moderate']))
            <div x-data="{ open: localStorage.getItem('adm-g-catalog') !== '0' }" class="mt-1">
                <button @click="open=!open; localStorage.setItem('adm-g-catalog', open?'1':'0')"
                        class="adm-group-label flex items-center justify-between w-full hover:text-[var(--adm-gold)] transition-colors px-3.5"
                        x-show="sidebar">
                    <span>Catalog</span>
                    <svg class="adm-group-chevron w-3 h-3" :class="open?'open':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="px-2 space-y-0.5" x-show="open || !sidebar">
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('products.view'))
                    {!! adminNavItem('admin.products.index', 'Products', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('categories.manage'))
                    {!! adminNavItem('admin.categories.index', 'Categories', 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('products.view'))
                    <a href="{{ route('admin.products.low-stock') }}"
                       class="{{ $currentRoute === 'admin.products.low-stock' ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="nav-label">Low Stock</span>
                        @if(isset($lowStockNav) && $lowStockNav > 0)
                        <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full" style="background:rgba(251,191,36,0.2);color:#fbbf24;">{{ $lowStockNav }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.stock.reservations') }}"
                       class="{{ $currentRoute === 'admin.stock.reservations' ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span class="nav-label">Reservations</span>
                    </a>
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('reviews.moderate'))
                    {!! adminNavItem('admin.reviews.index', 'Reviews', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']))
                    {!! adminNavItem('admin.blog.index', 'Blog', 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h2', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('products.view'))
                    <a href="{{ route('admin.product-requests.index') }}"
                       class="{{ $currentRoute === 'admin.product-requests.index' || $currentRoute === 'admin.product-requests.show' ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="nav-label">Product Requests</span>
                        @php try { $prCount = \App\Models\ProductRequest::where('status','pending')->count(); } catch (\Exception $e) { $prCount = 0; } @endphp
                        @if($prCount > 0)
                        <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full nav-label" style="background:rgba(55,18,32,0.35);color:var(--adm-gold);">{{ $prCount }}</span>
                        @endif
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- ── ORDERS ──────────────────────────── --}}
            @if($u->hasAnyRole(['super_admin','admin']) || $u->can('orders.view'))
            <div x-data="{ open: localStorage.getItem('adm-g-orders') !== '0' }" class="mt-1">
                <button @click="open=!open; localStorage.setItem('adm-g-orders', open?'1':'0')"
                        class="adm-group-label flex items-center justify-between w-full hover:text-[var(--adm-gold)] transition-colors px-3.5"
                        x-show="sidebar">
                    <span>Orders</span>
                    <svg class="adm-group-chevron w-3 h-3" :class="open?'open':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="px-2 space-y-0.5" x-show="open || !sidebar">
                    <a href="{{ route('admin.orders.index') }}"
                       class="{{ (str_starts_with($currentRoute, 'admin.orders') && !request('status') && !request('payment_status')) ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span class="nav-label">All Orders</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status'=>'pending']) }}"
                       class="{{ ($currentRoute === 'admin.orders.index' && request('status')==='pending') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="nav-label">Pending</span>
                        @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
                        @if($pending > 0)<span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full nav-label" style="background:rgba(55,18,32,0.35);color:var(--adm-gold);">{{ $pending }}</span>@endif
                    </a>
                    <a href="{{ route('admin.orders.index', ['payment_status'=>'paid']) }}"
                       class="{{ ($currentRoute === 'admin.orders.index' && request('payment_status')==='paid') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="nav-label">Paid</span>
                        @if($paidOrders > 0)<span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full nav-label" style="background:rgba(22,163,74,0.20);color:#4ade80;">{{ $paidOrders }}</span>@endif
                    </a>
                    <a href="{{ route('admin.orders.index', ['status'=>'processing']) }}"
                       class="{{ ($currentRoute === 'admin.orders.index' && request('status')==='processing') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span class="nav-label">Processing</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status'=>'packed']) }}"
                       class="{{ ($currentRoute === 'admin.orders.index' && request('status')==='packed') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <span class="nav-label">Packed</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status'=>'shipped']) }}"
                       class="{{ ($currentRoute === 'admin.orders.index' && request('status')==='shipped') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 17l4 4 4-4m-4-5v9M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"/></svg>
                        <span class="nav-label">Shipped</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status'=>'delivered']) }}"
                       class="{{ ($currentRoute === 'admin.orders.index' && request('status')==='delivered') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="nav-label">Delivered</span>
                    </a>
                    <a href="{{ route('admin.returns.index') }}"
                       class="{{ str_starts_with($currentRoute, 'admin.returns') ? 'adm-nav-item active' : 'adm-nav-item' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        <span class="nav-label">Returns</span>
                    </a>
                </div>
            </div>
            @endif

            {{-- ── FINANCE ──────────────────────────── --}}
            @if($u->hasAnyRole(['super_admin','admin']) || $u->hasAnyPermission(['coupons.manage','reports.view']))
            <div x-data="{ open: localStorage.getItem('adm-g-finance') !== '0' }" class="mt-1">
                <button @click="open=!open; localStorage.setItem('adm-g-finance', open?'1':'0')"
                        class="adm-group-label flex items-center justify-between w-full hover:text-[var(--adm-gold)] transition-colors px-3.5"
                        x-show="sidebar">
                    <span>Finance</span>
                    <svg class="adm-group-chevron w-3 h-3" :class="open?'open':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="px-2 space-y-0.5" x-show="open || !sidebar">
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('coupons.manage'))
                    {!! adminNavItem('admin.coupons.index', 'Coupons', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('reports.view'))
                    {!! adminNavItem('admin.reports.index', 'Reports & Analytics', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']))
                    {!! adminNavItem('admin.analytics.index', 'Google Analytics', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', $currentRoute) !!}
                    @endif
                </div>
            </div>
            @endif

            {{-- ── CUSTOMERS ──────────────────────────── --}}
            @if($u->hasAnyRole(['super_admin','admin']) || $u->hasAnyPermission(['users.view','messages.respond']))
            <div x-data="{ open: localStorage.getItem('adm-g-customers') !== '0' }" class="mt-1">
                <button @click="open=!open; localStorage.setItem('adm-g-customers', open?'1':'0')"
                        class="adm-group-label flex items-center justify-between w-full hover:text-[var(--adm-gold)] transition-colors px-3.5"
                        x-show="sidebar">
                    <span>Customers</span>
                    <svg class="adm-group-chevron w-3 h-3" :class="open?'open':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="px-2 space-y-0.5" x-show="open || !sidebar">
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('users.view'))
                    {!! adminNavItem('admin.customers.index', 'All Customers', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('messages.respond'))
                    {!! adminNavItem('admin.messages.index', 'Messages', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']))
                    {!! adminNavItem('admin.newsletter.index', 'Newsletter', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', $currentRoute) !!}
                    {!! adminNavItem('admin.email-campaigns.index', 'Email Campaigns', 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8', $currentRoute) !!}
                    @endif
                </div>
            </div>
            @endif

            {{-- ── REFERRALS ──────────────────────────── --}}
            @if($u->hasAnyRole(['super_admin','admin']))
            <div x-data="{ open: localStorage.getItem('adm-g-referrals') !== '0' }" class="mt-1">
                <button @click="open=!open; localStorage.setItem('adm-g-referrals', open?'1':'0')"
                        class="adm-group-label flex items-center justify-between w-full hover:text-[var(--adm-gold)] transition-colors px-3.5"
                        x-show="sidebar">
                    <span>Referrals</span>
                    <svg class="adm-group-chevron w-3 h-3" :class="open?'open':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="px-2 space-y-0.5" x-show="open || !sidebar">
                    {!! adminNavItem('admin.referrals.index', 'Overview', 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z', $currentRoute) !!}
                    {!! adminNavItem('admin.referrals.settings', 'Program Settings', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', $currentRoute) !!}
                </div>
            </div>
            @endif

            {{-- ── AI STUDIO ──────────────────────────── --}}
            @if($u->hasAnyRole(['super_admin','admin']) || $u->can('chat.view'))
            <div x-data="{ open: localStorage.getItem('adm-g-ai') !== '0' }" class="mt-1">
                <button @click="open=!open; localStorage.setItem('adm-g-ai', open?'1':'0')"
                        class="adm-group-label flex items-center justify-between w-full hover:text-[var(--adm-gold)] transition-colors px-3.5"
                        x-show="sidebar">
                    <span>AI Studio</span>
                    <svg class="adm-group-chevron w-3 h-3" :class="open?'open':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="px-2 space-y-0.5" x-show="open || !sidebar">
                    {!! adminNavItem('admin.ai.assistant', 'AI Assistant', 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', $currentRoute) !!}
                    {!! adminNavItem('admin.chat.index', 'Chat Logs', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', $currentRoute) !!}
                </div>
            </div>
            @endif

            {{-- ── SYSTEM ──────────────────────────── --}}
            @if($u->hasAnyRole(['super_admin','admin']) || $u->hasAnyPermission(['settings.manage','staff.manage']))
            <div x-data="{ open: localStorage.getItem('adm-g-system') !== '0' }" class="mt-1">
                <button @click="open=!open; localStorage.setItem('adm-g-system', open?'1':'0')"
                        class="adm-group-label flex items-center justify-between w-full hover:text-[var(--adm-gold)] transition-colors px-3.5"
                        x-show="sidebar">
                    <span>System</span>
                    <svg class="adm-group-chevron w-3 h-3" :class="open?'open':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="px-2 space-y-0.5" x-show="open || !sidebar">
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('staff.manage'))
                    {!! adminNavItem('admin.staff.index', 'Staff', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']))
                    {!! adminNavItem('admin.pages.index', 'Pages', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']))
                    {!! adminNavItem('admin.shipping.index', 'Shipping Zones', 'M8 17l4 4 4-4m-4-5v9M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29', $currentRoute) !!}
                    {!! adminNavItem('admin.backups.index', 'Backups', 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']) || $u->can('settings.manage'))
                    {!! adminNavItem('admin.settings.index', 'Settings', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', $currentRoute) !!}
                    @endif
                    @if($u->hasAnyRole(['super_admin','admin']))
                    {!! adminNavItem('admin.activity.index', 'Activity Log', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', $currentRoute) !!}
                    <form method="POST" action="{{ route('admin.cache.clear') }}" onsubmit="return confirm('Clear all caches?')">
                        @csrf
                        <button type="submit" class="adm-nav-item w-full text-left">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span class="nav-label">Clear Cache</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif
        </nav>

        {{-- Collapse toggle --}}
        <div class="flex-shrink-0 p-2 border-t" style="border-color:var(--adm-border);">
            <button @click="toggleSidebar()"
                    class="w-full flex items-center justify-center p-2.5 rounded transition-colors hover:bg-[rgba(212,185,154,0.06)]"
                    style="color:var(--adm-muted);">
                <svg class="w-4 h-4 transition-transform duration-300" :class="sidebar?'':'rotate-180'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>
    </aside>

    {{-- ── MAIN AREA ──────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden">

        {{-- Top bar --}}
        <header class="adm-topbar flex-shrink-0 px-6 h-[57px] flex items-center justify-between gap-4">

            <div class="flex items-center gap-4">
                {{-- Mobile sidebar toggle --}}
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden" style="color:var(--adm-muted);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                {{-- Breadcrumb page title --}}
                <div>
                    <p class="text-xs tracking-[.15em] uppercase font-medium" style="color:var(--adm-muted);">@yield('breadcrumb', 'Dashboard')</p>
                    <h1 class="text-base font-semibold leading-tight mt-0.5" style="color:var(--adm-text-strong);">@yield('title', 'Dashboard')</h1>
                </div>
            </div>

            <div class="flex items-center gap-4">

                {{-- Store link --}}
                <a href="{{ route('home') }}" target="_blank"
                   class="hidden sm:flex items-center gap-1.5 text-xs tracking-wider transition-colors"
                   style="color:var(--adm-muted);"
                   onmouseover="this.style.color='var(--adm-gold)'" onmouseout="this.style.color='var(--adm-muted)'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Store
                </a>

                <div class="w-px h-5" style="background:var(--adm-border);"></div>

                {{-- Theme toggle --}}
                <button @click="toggleTheme()"
                        class="w-8 h-8 flex items-center justify-center rounded transition-colors hover:bg-[rgba(212,185,154,0.08)]"
                        style="color:var(--adm-muted);"
                        :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- Messages badge --}}
                <a href="{{ route('admin.messages.index') }}" class="relative"
                   style="color:var(--adm-muted);"
                   onmouseover="this.style.color='var(--adm-gold)'" onmouseout="this.style.color='var(--adm-muted)'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    @php $unread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                    @if($unread > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-bold pulse"
                          style="background:#371220;color:#FAF5ED;">{{ $unread }}</span>
                    @endif
                </a>

                {{-- AI Quick Access --}}
                <a href="{{ route('admin.ai.assistant') }}"
                   class="hidden md:flex items-center gap-1.5 px-3 py-1.5 text-xs tracking-wider rounded transition-all"
                   style="background:rgba(55,18,32,0.2);color:var(--adm-gold);border:1px solid rgba(55,18,32,0.3);"
                   onmouseover="this.style.background='rgba(55,18,32,0.35)'" onmouseout="this.style.background='rgba(55,18,32,0.2)'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    AI Studio
                </a>

                {{-- User menu --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold"
                             style="background:rgba(55,18,32,0.35);color:var(--adm-gold);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-xs font-medium leading-none" style="color:var(--adm-text);">{{ Str::limit(auth()->user()->name, 18) }}</p>
                            <p class="text-[10px] mt-0.5" style="color:var(--adm-muted);">{{ str_replace('_',' ', auth()->user()->getRoleNames()->first() ?? 'Staff') }}</p>
                        </div>
                        <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="none" stroke="currentColor" style="color:var(--adm-muted);" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-52 shadow-2xl z-50 py-1 rounded"
                         style="background:var(--adm-sidebar);border:1px solid var(--adm-border);display:none;">
                        <div class="px-4 py-3 border-b" style="border-color:var(--adm-border);">
                            <p class="text-xs font-medium" style="color:var(--adm-text);">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] mt-0.5 truncate" style="color:var(--adm-muted);">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs transition-colors" style="color:var(--adm-muted);" onmouseover="this.style.color='var(--adm-text)';this.style.background='rgba(212,185,154,0.05)'" onmouseout="this.style.color='var(--adm-muted)';this.style.background='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Settings
                        </a>
                        <a href="{{ route('admin.ai.assistant') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs transition-colors" style="color:var(--adm-muted);" onmouseover="this.style.color='var(--adm-text)';this.style.background='rgba(212,185,154,0.05)'" onmouseout="this.style.color='var(--adm-muted)';this.style.background='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            AI Studio
                        </a>
                        <div class="border-t mt-1" style="border-color:var(--adm-border);">
                            <form method="POST" action="{{ route('admin.logout') }}">@csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs text-left transition-colors" style="color:rgba(239,68,68,0.60);" onmouseover="this.style.color='rgba(239,68,68,0.90)';this.style.background='rgba(239,68,68,0.05)'" onmouseout="this.style.color='rgba(239,68,68,0.60)';this.style.background='transparent'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
             class="px-6 py-3 text-sm border-b flex items-center gap-3" x-transition
             style="background:rgba(47,74,22,0.2);border-color:rgba(47,120,22,0.3);color:rgba(150,220,100,0.85);">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
             class="px-6 py-3 text-sm border-b flex items-center gap-3" x-transition
             style="background:rgba(55,18,32,0.2);border-color:rgba(55,18,32,0.4);color:rgba(255,130,100,0.85);">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
<script>
(function(){
    var nav = document.getElementById('adm-nav');
    if(!nav) return;
    var saved = localStorage.getItem('adm-nav-scroll');
    if(saved) nav.scrollTop = parseInt(saved, 10);
    var t;
    nav.addEventListener('scroll', function(){
        clearTimeout(t);
        t = setTimeout(function(){ localStorage.setItem('adm-nav-scroll', nav.scrollTop); }, 100);
    }, {passive:true});
})();
</script>
</body>
</html>
