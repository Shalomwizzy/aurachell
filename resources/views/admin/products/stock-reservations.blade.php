@extends('layouts.admin')
@section('title', 'Stock Reservations')
@section('breadcrumb', 'Catalog')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Stock Reservations</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">
            {{ $reservations->total() }} active hold{{ $reservations->total() !== 1 ? 's' : '' }}
            — {{ $totalHeld }} unit{{ $totalHeld !== 1 ? 's' : '' }} currently reserved in open carts
        </p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.low-stock') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs tracking-widest uppercase transition-colors"
           style="background:rgba(255,255,255,0.05);color:var(--adm-muted);">
            Low Stock
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs tracking-widest uppercase transition-colors"
           style="background:rgba(255,255,255,0.05);color:var(--adm-muted);">
            All Products
        </a>
    </div>
</div>

@if($reservations->isEmpty())
<div class="text-center py-20" style="color:var(--adm-muted);">
    <svg class="w-12 h-12 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-sm tracking-wide">No active reservations — all stock is fully available.</p>
</div>
@else
<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr style="border-bottom:1px solid var(--adm-border);">
                <th class="px-5 py-3 text-left text-xs tracking-widest uppercase font-medium" style="color:var(--adm-muted);">Product</th>
                <th class="px-5 py-3 text-left text-xs tracking-widest uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Variant</th>
                <th class="px-5 py-3 text-center text-xs tracking-widest uppercase font-medium" style="color:var(--adm-muted);">Qty Held</th>
                <th class="px-5 py-3 text-left text-xs tracking-widest uppercase font-medium hidden lg:table-cell" style="color:var(--adm-muted);">Cart / Customer</th>
                <th class="px-5 py-3 text-center text-xs tracking-widest uppercase font-medium" style="color:var(--adm-muted);">Expires</th>
            </tr>
        </thead>
        <tbody class="divide-y" style="border-color:var(--adm-border);">
            @foreach($reservations as $r)
            @php $expiresIn = now()->diffInMinutes($r->expires_at, false); @endphp
            <tr class="transition-colors hover:bg-white/[0.02]">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        @if($r->product?->primaryImage)
                        <img src="{{ asset('images/products/' . basename($r->product->primaryImage->image_path)) }}"
                             alt="" class="w-10 h-10 object-cover flex-shrink-0" style="opacity:0.85;">
                        @else
                        <div class="w-10 h-10 flex-shrink-0" style="background:var(--adm-surface-alt);"></div>
                        @endif
                        <div>
                            <p class="text-white text-sm font-medium">{{ $r->product?->name ?? '—' }}</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">{{ $r->product?->sku }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 hidden md:table-cell" style="color:var(--adm-muted);">
                    {{ $r->variant?->name ?? '—' }}
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full"
                          style="background:rgba(55,18,32,0.15);color:#371220;">
                        {{ $r->quantity }}
                    </span>
                </td>
                <td class="px-5 py-4 hidden lg:table-cell">
                    @if($r->cart?->user)
                    <p class="text-white text-sm">{{ $r->cart->user->name }}</p>
                    <p class="text-xs" style="color:var(--adm-muted);">{{ $r->cart->user->email }}</p>
                    @else
                    <span class="text-xs" style="color:var(--adm-muted);">Guest cart #{{ $r->cart_id }}</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    @if($expiresIn <= 5)
                    <span class="text-xs font-medium" style="color:rgba(55,18,32,0.80);">{{ $expiresIn }}m</span>
                    @elseif($expiresIn <= 15)
                    <span class="text-xs font-medium" style="color:#371220;">{{ $expiresIn }}m</span>
                    @else
                    <span class="text-xs" style="color:var(--adm-muted);">{{ $expiresIn }}m</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $reservations->links() }}
</div>

<p class="mt-4 text-xs" style="color:var(--adm-muted);">
    Reservations expire after 30 minutes of inactivity. Expired holds are cleared automatically every 5 minutes by the scheduler.
</p>
@endif
@endsection
