@extends('layouts.admin')
@section('title', 'Wishlists')
@section('breadcrumb', 'Wishlists')

@section('content')
<div class="p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text)">Customer Wishlists</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted)">Products customers have saved across all accounts</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($stats['total_entries']) }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">Total Saved Items</p>
        </div>
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($stats['users_with_list']) }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">Customers with Wishlists</p>
        </div>
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-sm font-semibold truncate" style="color:var(--adm-gold);">{{ $stats['top_product'] }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">Most Wishlisted Product</p>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-6 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by name or email…"
               class="flex-1 px-4 py-2.5 text-sm focus:outline-none"
               style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
        <button type="submit" class="px-5 py-2.5 text-xs tracking-wider uppercase transition-colors"
                style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
            Search
        </button>
        @if(request('search'))
        <a href="{{ route('admin.wishlists.index') }}"
           class="px-5 py-2.5 text-xs tracking-wider uppercase"
           style="border:1px solid var(--adm-border);color:var(--adm-muted);">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div style="border:1px solid var(--adm-border);overflow:hidden;">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:var(--adm-surface);border-bottom:1px solid var(--adm-border);">
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Customer</th>
                    <th class="text-center px-4 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Saved Items</th>
                    <th class="text-left px-4 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Products</th>
                    <th class="px-4 py-3.5"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
            @php $wId = 'wl-' . $user->id; @endphp
            <tr style="border-top:1px solid var(--adm-border);">

                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold shrink-0"
                             style="background:rgba(55,18,32,0.30);color:var(--adm-gold);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium" style="color:var(--adm-text);">{{ $user->name }}</p>
                            <p class="text-xs" style="color:var(--adm-muted);">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>

                <td class="px-4 py-4 text-center">
                    <button onclick="var r=document.getElementById('{{ $wId }}');r.style.display=r.style.display==='none'?'':'none';"
                            class="text-xs underline underline-offset-2 transition-colors"
                            style="color:var(--adm-gold);">
                        {{ $user->wishlist_count }} item{{ $user->wishlist_count === 1 ? '' : 's' }}
                    </button>
                </td>

                <td class="px-4 py-4 hidden md:table-cell">
                    <p class="text-xs truncate max-w-xs" style="color:var(--adm-muted);">
                        {{ $user->wishlist->take(3)->pluck('product.name')->filter()->implode(', ') }}
                        @if($user->wishlist_count > 3)
                            <span style="color:var(--adm-gold);">+{{ $user->wishlist_count - 3 }} more</span>
                        @endif
                    </p>
                </td>

                <td class="px-4 py-4 text-right">
                    <a href="{{ route('admin.customers.show', $user) }}"
                       class="text-[10px] tracking-wider uppercase transition-colors"
                       style="color:var(--adm-muted);"
                       onmouseover="this.style.color='var(--adm-gold)'" onmouseout="this.style.color='var(--adm-muted)'">
                        View Profile →
                    </a>
                </td>
            </tr>

            {{-- Expandable wishlist items --}}
            <tr id="{{ $wId }}" style="display:none;border-top:1px solid var(--adm-border);background:rgba(55,18,32,0.06);">
                <td colspan="4" class="px-8 py-4">
                    <p class="text-[10px] tracking-[0.2em] uppercase mb-4" style="color:var(--adm-muted);">Saved Products</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($user->wishlist as $item)
                        @if($item->product)
                        <a href="{{ route('admin.products.edit', $item->product) }}"
                           class="flex items-center gap-3 p-3 transition-colors"
                           style="background:var(--adm-surface);border:1px solid var(--adm-border);"
                           onmouseover="this.style.borderColor='var(--adm-gold)'" onmouseout="this.style.borderColor='var(--adm-border)'">
                            <div class="w-10 h-12 shrink-0 overflow-hidden" style="background:var(--adm-bg);">
                                <img src="{{ $item->product->primary_image_url }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'">
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium truncate" style="color:var(--adm-text);">{{ $item->product->name }}</p>
                                <p class="text-[10px]" style="color:var(--adm-gold);">₦{{ number_format($item->product->price) }}</p>
                                @unless($item->product->isInStock())
                                <p class="text-[9px] tracking-wider uppercase" style="color:var(--adm-text);">Out of stock</p>
                                @endunless
                            </div>
                        </a>
                        @endif
                        @endforeach
                    </div>
                    <div class="mt-4 pt-3" style="border-top:1px solid var(--adm-border);">
                        <a href="{{ route('admin.customers.show', $user) }}"
                           class="text-[10px] tracking-wider uppercase transition-colors"
                           style="color:var(--adm-muted);"
                           onmouseover="this.style.color='var(--adm-gold)'" onmouseout="this.style.color='var(--adm-muted)'">
                            View Full Customer Profile →
                        </a>
                    </div>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="4" class="px-5 py-16 text-center" style="color:var(--adm-muted);">
                    <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <p class="text-sm">No wishlists found.</p>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif

</div>
@endsection
