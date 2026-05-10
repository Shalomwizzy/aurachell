@extends('layouts.admin')
@section('title', 'Low Stock')
@section('breadcrumb', 'Catalog')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Low Stock</h1>
        <p class="text-gray-400 text-sm mt-1">
            {{ $lowStockCount }} active product{{ $lowStockCount !== 1 ? 's' : '' }} with 3 or fewer units remaining
        </p>
    </div>
    <a href="{{ route('admin.products.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 text-xs tracking-widest uppercase font-medium transition-colors"
       style="background:rgba(255,255,255,0.05);color:var(--adm-muted);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
        All Products
    </a>
</div>

@if($lowStockCount === 0)
<div class="adm-card p-12 text-center">
    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--adm-success-fg);">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-white text-lg font-medium mb-1">All stocked up</p>
    <p class="text-sm" style="color:var(--adm-muted);">No active products have 3 or fewer units remaining.</p>
</div>
@else

<div class="adm-card overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b" style="border-color:var(--adm-border);">
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Product</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Category</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Stock Left</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Price</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y" style="border-color:var(--adm-border);">
            @foreach($products as $product)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex-shrink-0 overflow-hidden" style="background:var(--adm-surface-alt);">
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'">
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text-strong);">{{ $product->name }}</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">{{ $product->sku }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <span class="text-sm" style="color:var(--adm-muted);">{{ $product->category?->name ?? '—' }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    @php $reserved = (int) $product->reserved_quantity; @endphp
                    @if($product->stock_quantity === 0)
                    <span class="px-2.5 py-1 text-xs font-semibold tracking-wide" style="background:rgba(239,68,68,0.15);color:#f87171;">Out of Stock</span>
                    @elseif($product->stock_quantity === 1)
                    <span class="px-2.5 py-1 text-xs font-semibold tracking-wide" style="background:rgba(239,68,68,0.15);color:#f87171;">1 left</span>
                    @elseif($product->stock_quantity === 2)
                    <span class="px-2.5 py-1 text-xs font-semibold tracking-wide" style="background:rgba(251,191,36,0.15);color:#fbbf24;">2 left</span>
                    @else
                    <span class="px-2.5 py-1 text-xs font-semibold tracking-wide" style="background:rgba(251,191,36,0.15);color:#fbbf24;">3 left</span>
                    @endif
                    @if($reserved > 0)
                    <div class="text-[10px] mt-1" style="color:#fbbf24;opacity:0.7;">{{ $reserved }} in cart</div>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    <span class="text-sm" style="color:var(--adm-text-strong);">₦{{ number_format($product->price, 0) }}</span>
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="px-3 py-1.5 text-xs transition-colors"
                       style="background:var(--adm-surface-alt);color:var(--adm-text);">
                        Restock
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="px-5 py-4 border-t" style="border-color:var(--adm-border);">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endif
@endsection
