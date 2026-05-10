@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">

        {{-- Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="bg-white p-6 shadow-luxury">
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-sand/50">
                    <div class="w-12 h-12 rounded-full bg-sage/10 flex items-center justify-center text-sage font-display font-bold text-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-display text-base text-text-dark truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-text-muted truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <nav class="space-y-1">
                    @foreach([
                        ['route' => 'account.overview', 'label' => 'Overview'],
                        ['route' => 'account.orders', 'label' => 'My Orders'],
                        ['route' => 'account.track', 'label' => 'Track Order'],
                        ['route' => 'account.addresses', 'label' => 'Addresses'],
                        ['route' => 'account.wishlist', 'label' => 'Wishlist'],
                        ['route' => 'account.reviews', 'label' => 'My Reviews'],
                        ['route' => 'account.profile', 'label' => 'Profile'],
                    ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block px-3 py-2.5 text-sm font-sans transition-colors {{ request()->routeIs($item['route']) ? 'bg-sage text-cream' : 'text-text-muted hover:text-sage hover:bg-sand/30' }}">
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="w-full text-left px-3 py-2.5 text-sm font-sans text-text-muted hover:text-red-500 hover:bg-red-50 transition-colors mt-2 border-t border-sand/50">
                            Sign Out
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="lg:col-span-3">
            @yield('account-content')
        </div>
    </div>
</div>
@endsection
