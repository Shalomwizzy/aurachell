@extends('layouts.admin')
@section('title', 'Product Performance')
@section('breadcrumb', 'Finance')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text);">Product Performance</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">How every product is selling — paid orders only</p>
        </div>
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            <select name="period" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm focus:outline-none"
                    style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
                @foreach(['7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days', 'all' => 'All time'] as $val => $label)
                <option value="{{ $val }}" {{ $period == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="sort" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm focus:outline-none"
                    style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
                @foreach(['best' => 'Best sellers (revenue)', 'units' => 'Most units sold', 'worst' => 'Least selling', 'stock' => 'Lowest stock', 'name' => 'Name A–Z'] as $val => $label)
                <option value="{{ $val }}" {{ $sort == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-3" style="color:var(--adm-muted);">Total Revenue</p>
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">₦{{ number_format($totalRevenue, 0) }}</p>
        </div>
        <div class="p-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-3" style="color:var(--adm-muted);">Units Sold</p>
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($totalUnits) }}</p>
        </div>
        <div class="p-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-3" style="color:var(--adm-muted);">Products With No Sales</p>
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($noSales) }}</p>
        </div>
        <div class="p-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-3" style="color:var(--adm-muted);">Best Seller</p>
            <p class="text-sm font-semibold truncate" style="color:var(--adm-gold);" title="{{ $bestSeller->name ?? '' }}">
                {{ $bestSeller && $bestSeller->revenue > 0 ? $bestSeller->name : '—' }}
            </p>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto" style="border:1px solid var(--adm-border);">
        <table class="w-full text-sm" style="min-width:720px;">
            <thead>
                <tr style="border-bottom:1px solid var(--adm-border);background:rgba(55,18,32,0.04);">
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal" style="color:var(--adm-muted);">Product</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal hidden md:table-cell" style="color:var(--adm-muted);">Category</th>
                    <th class="text-right px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal" style="color:var(--adm-muted);">Units</th>
                    <th class="text-right px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal hidden sm:table-cell" style="color:var(--adm-muted);">Orders</th>
                    <th class="text-right px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal" style="color:var(--adm-muted);">Revenue</th>
                    <th class="text-right px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal" style="color:var(--adm-muted);">Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr style="border-bottom:1px solid var(--adm-border);" class="transition-colors hover:bg-[rgba(55,18,32,0.03)]">
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.products.edit', $p->id) }}" class="font-medium hover:underline" style="color:var(--adm-text);">{{ $p->name }}</a>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs" style="color:var(--adm-muted);">{{ $p->sku ?: '—' }}</span>
                            @if($p->units_sold == 0)
                            <span class="text-[9px] px-1.5 py-0.5 tracking-wider uppercase" style="background:rgba(55,18,32,0.10);color:var(--adm-muted);">No sales</span>
                            @endif
                            @if(!$p->is_active)
                            <span class="text-[9px] px-1.5 py-0.5 tracking-wider uppercase" style="background:rgba(55,18,32,0.10);color:var(--adm-muted);">Inactive</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell text-xs" style="color:var(--adm-muted);">{{ $p->category ?: '—' }}</td>
                    <td class="px-5 py-4 text-right font-medium" style="color:var(--adm-text);">{{ number_format($p->units_sold) }}</td>
                    <td class="px-5 py-4 text-right hidden sm:table-cell" style="color:var(--adm-muted);">{{ number_format($p->order_count) }}</td>
                    <td class="px-5 py-4 text-right font-semibold" style="color:{{ $p->revenue > 0 ? 'var(--adm-gold)' : 'var(--adm-muted)' }};">₦{{ number_format($p->revenue, 0) }}</td>
                    <td class="px-5 py-4 text-right" style="color:{{ $p->stock_quantity == 0 ? '#E4796B' : 'var(--adm-text)' }};">
                        {{ $p->stock_quantity == 0 ? 'Out' : number_format($p->stock_quantity) }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-16 text-center text-sm" style="color:var(--adm-muted);">No products yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="text-xs mt-4" style="color:var(--adm-muted);">
        Showing {{ $products->count() }} product{{ $products->count() === 1 ? '' : 's' }} · revenue &amp; units counted from paid orders{{ $period === 'all' ? ' (all time)' : ' in the last '.$period.' days' }}.
    </p>

</div>
@endsection
