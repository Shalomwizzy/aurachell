@extends('layouts.admin')
@section('title', 'Abandoned Carts')
@section('breadcrumb', 'Abandoned Carts')

@section('content')
<div class="p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text)">Abandoned Carts</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted)">Customers who added items but didn't complete checkout</p>
        </div>
        @if($stats['unreminded'] > 0)
        <form action="{{ route('admin.abandoned-carts.send-all') }}" method="POST">
            @csrf
            <button type="submit"
                    onclick="return confirm('Send reminder emails to {{ $stats['unreminded'] }} customer(s) who have not been reminded in the last 24h?')"
                    class="px-5 py-2.5 text-xs tracking-wider uppercase font-medium transition-colors"
                    style="background:var(--adm-gold);color:rgba(55,18,32,0.95);">
                Send All Reminders ({{ $stats['unreminded'] }})
            </button>
        </form>
        @endif
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(55,18,32,0.10);border:1px solid rgba(55,18,32,0.25);color:var(--adm-gold);">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.22);color:rgba(250,245,237,0.80);">
        {{ session('error') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($stats['total']) }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">Abandoned Carts</p>
        </div>
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-2xl font-semibold" style="color:var(--adm-gold);">₦{{ number_format($stats['value']) }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">Total Cart Value</p>
        </div>
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($stats['unreminded']) }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">Not Yet Reminded</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by name or email…"
               class="flex-1 min-w-[200px] px-4 py-2.5 text-sm focus:outline-none"
               style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">

        <select name="age" class="px-4 py-2.5 text-sm focus:outline-none"
                style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
            <option value="">All (24h+)</option>
            <option value="48h"  {{ request('age') === '48h'  ? 'selected' : '' }}>48h+</option>
            <option value="72h"  {{ request('age') === '72h'  ? 'selected' : '' }}>72h+</option>
            <option value="week" {{ request('age') === 'week' ? 'selected' : '' }}>1 week+</option>
        </select>

        <button type="submit" class="px-5 py-2.5 text-xs tracking-wider uppercase transition-colors"
                style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
            Filter
        </button>
        @if(request('search') || request('age'))
        <a href="{{ route('admin.abandoned-carts.index') }}"
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
                    <th class="text-center px-4 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Items</th>
                    <th class="text-right px-4 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Cart Value</th>
                    <th class="text-left px-4 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Abandoned</th>
                    <th class="text-left px-4 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden lg:table-cell" style="color:var(--adm-muted);">Last Reminded</th>
                    <th class="px-4 py-3.5"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($carts as $cart)
            <tr x-data="{ open: false }"
                style="border-top:1px solid var(--adm-border);">

                {{-- Main row --}}
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold shrink-0"
                             style="background:rgba(55,18,32,0.30);color:var(--adm-gold);">
                            {{ strtoupper(substr($cart->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium" style="color:var(--adm-text);">{{ $cart->user->name }}</p>
                            <p class="text-xs" style="color:var(--adm-muted);">{{ $cart->user->email }}</p>
                        </div>
                    </div>
                </td>

                <td class="px-4 py-4 text-center">
                    <button @click="open = !open"
                            class="text-xs underline underline-offset-2 transition-colors"
                            style="color:var(--adm-gold);">
                        {{ $cart->item_count }} item{{ $cart->item_count === 1 ? '' : 's' }}
                    </button>
                </td>

                <td class="px-4 py-4 text-right font-medium" style="color:var(--adm-text);">
                    ₦{{ number_format($cart->total) }}
                </td>

                <td class="px-4 py-4 hidden md:table-cell">
                    <p class="text-xs" style="color:var(--adm-text);">{{ $cart->updated_at->diffForHumans() }}</p>
                    <p class="text-[10px]" style="color:var(--adm-muted);">{{ $cart->updated_at->format('d M Y, H:i') }}</p>
                </td>

                <td class="px-4 py-4 hidden lg:table-cell">
                    @if($cart->last_reminder_at)
                    <p class="text-xs" style="color:var(--adm-muted);">{{ $cart->last_reminder_at->diffForHumans() }}</p>
                    @else
                    <span class="text-[10px] tracking-wider uppercase px-2 py-1"
                          style="background:rgba(55,18,32,0.10);color:var(--adm-gold);">Not sent</span>
                    @endif
                </td>

                <td class="px-4 py-4">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Send reminder --}}
                        <form action="{{ route('admin.abandoned-carts.remind', $cart) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Send reminder email to {{ addslashes($cart->user->name) }}?')"
                                    class="px-3 py-1.5 text-[10px] tracking-wider uppercase transition-colors"
                                    style="border:1px solid var(--adm-gold);color:var(--adm-gold);"
                                    onmouseover="this.style.background='var(--adm-gold)';this.style.color='rgba(55,18,32,0.95)'"
                                    onmouseout="this.style.background='transparent';this.style.color='var(--adm-gold)'">
                                Remind
                            </button>
                        </form>

                        {{-- Delete cart --}}
                        <form action="{{ route('admin.abandoned-carts.destroy', $cart) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Clear this abandoned cart? This cannot be undone.')"
                                    class="px-3 py-1.5 text-[10px] tracking-wider uppercase transition-colors"
                                    style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                                    onmouseover="this.style.borderColor='rgba(250,245,237,0.80)';this.style.color='rgba(250,245,237,0.80)'"
                                    onmouseout="this.style.borderColor='var(--adm-border)';this.style.color='var(--adm-muted)'">
                                Clear
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            {{-- Expandable items row --}}
            <tr x-show="open" style="border-top:1px solid var(--adm-border);background:rgba(55,18,32,0.06);"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <td colspan="6" class="px-8 py-4">
                    <p class="text-[10px] tracking-[0.2em] uppercase mb-3" style="color:var(--adm-muted);">Cart Items</p>
                    <div class="space-y-3">
                        @foreach($cart->items as $item)
                        <div class="flex items-center gap-4">
                            {{-- Product image --}}
                            <div class="w-12 h-14 shrink-0 overflow-hidden"
                                 style="background:var(--adm-surface);">
                                @if($item->product?->primary_image_url)
                                <img src="{{ $item->product->primary_image_url }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate" style="color:var(--adm-text);">
                                    {{ $item->product?->name ?? 'Product removed' }}
                                </p>
                                @if($item->scent_note)
                                <p class="text-xs" style="color:var(--adm-muted);">Scent: {{ $item->scent_note }}</p>
                                @endif
                                <p class="text-xs" style="color:var(--adm-muted);">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-medium shrink-0" style="color:var(--adm-gold);">
                                ₦{{ number_format($item->price_at_add * $item->quantity) }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-3 flex justify-between items-center"
                         style="border-top:1px solid var(--adm-border);">
                        <p class="text-xs" style="color:var(--adm-muted);">
                            Cart total: <span class="font-semibold" style="color:var(--adm-gold);">₦{{ number_format($cart->total) }}</span>
                        </p>
                        <a href="{{ route('admin.customers.show', $cart->user) }}"
                           class="text-[10px] tracking-wider uppercase transition-colors"
                           style="color:var(--adm-muted);"
                           onmouseover="this.style.color='var(--adm-gold)'" onmouseout="this.style.color='var(--adm-muted)'">
                            View Customer Profile →
                        </a>
                    </div>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center" style="color:var(--adm-muted);">
                    <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm">No abandoned carts found.</p>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($carts->hasPages())
    <div class="mt-6">
        {{ $carts->links() }}
    </div>
    @endif

</div>
@endsection
