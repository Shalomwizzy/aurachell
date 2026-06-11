@extends('layouts.admin')
@section('title', 'Dashboard')
@section('breadcrumb', 'Overview')

@section('content')
<div class="p-6 lg:p-8 space-y-6">

    <div class="flex items-end justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">
                Welcome back, {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">{{ now()->format('l, d F Y') }} · Aurachell store overview</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs tracking-[0.2em] uppercase font-medium rounded transition-opacity"
           style="background:var(--adm-accent);color:#FFFFFF;"
           onmouseover="this.style.opacity='0.92'" onmouseout="this.style.opacity='1'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Revenue',   'value' => '₦' . number_format($totalRevenue, 0),   'sub' => 'This month: ₦' . number_format($monthRevenue, 0), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'tone' => 'success'],
            ['label' => 'Paid Orders',     'value' => number_format($totalOrders),              'sub' => 'Today: ' . $todayOrders . ' · ₦' . number_format($todayRevenue, 0), 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'tone' => 'info'],
            ['label' => 'Total Customers', 'value' => number_format($totalCustomers),           'sub' => 'New today: ' . $newCustomers, 'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'tone' => 'accent'],
            ['label' => 'Pending Orders',  'value' => $pendingOrders,                          'sub' => 'Awaiting fulfilment', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'tone' => 'warn'],
        ] as $kpi)
        <div class="adm-card p-5">
            <div class="flex items-start justify-between mb-3">
                <span class="text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">{{ $kpi['label'] }}</span>
                @php
                $toneBg = match($kpi['tone']) {
                    'success' => 'var(--adm-success-bg)',
                    'info'    => 'var(--adm-info-bg)',
                    'warn'    => 'var(--adm-warn-bg)',
                    default   => 'rgba(55,18,32,0.18)',
                };
                $toneFg = match($kpi['tone']) {
                    'success' => 'var(--adm-success-fg)',
                    'info'    => 'var(--adm-info-fg)',
                    'warn'    => 'var(--adm-warn-fg)',
                    default   => 'var(--adm-gold)',
                };
                @endphp
                <div class="w-9 h-9 rounded flex items-center justify-center" style="background:{{ $toneBg }};">
                    <svg class="w-4 h-4" style="color:{{ $toneFg }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $kpi['icon'] }}"/></svg>
                </div>
            </div>
            <p class="text-2xl font-semibold" style="color:var(--adm-text-strong);">{{ $kpi['value'] }}</p>
            <p class="text-[11px] mt-1" style="color:var(--adm-muted);">{{ $kpi['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Sales chart (14 days) --}}
    <div class="adm-card p-6">
        <div class="flex items-start justify-between mb-4 gap-4 flex-wrap">
            <div>
                <h2 class="text-sm font-semibold" style="color:var(--adm-text);">Revenue — Last 14 Days</h2>
                <p class="text-xs mt-0.5" style="color:var(--adm-muted);">
                    ₦{{ number_format($chartDays->sum('revenue'), 0) }} total · {{ $chartDays->sum('orders') }} paid orders
                </p>
            </div>
            <p class="text-[10px]" style="color:var(--adm-muted);">
                {{ $chartDays->first()['label'] }} → {{ $chartDays->last()['label'] }}
            </p>
        </div>
        @php $maxRev = max(1, $chartDays->max('revenue')); @endphp
        <div class="flex items-end gap-1 h-28">
            @foreach($chartDays as $i => $d)
            <div class="flex-1 flex flex-col items-center gap-1 min-w-0">
                <div class="w-full rounded-t transition-opacity hover:opacity-70"
                     style="height:{{ max(2, ($d['revenue'] / $maxRev) * 100) }}%;background:var(--adm-accent);"
                     title="{{ $d['label'] }}: ₦{{ number_format($d['revenue'], 0) }} · {{ $d['orders'] }} orders"></div>
                {{-- Show label only on first, middle and last bar --}}
                @if($i === 0 || $i === 6 || $i === 13)
                <span class="text-[9px] whitespace-nowrap hidden sm:block" style="color:var(--adm-muted);">{{ $d['label'] }}</span>
                @else
                <span class="text-[9px]">&nbsp;</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Inventory health --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Out of Stock --}}
        <div class="adm-card overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:var(--adm-border);">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full" style="background:var(--adm-danger-fg);"></div>
                    <h2 class="text-sm font-semibold" style="color:var(--adm-text);">Out of Stock</h2>
                    <span class="text-[10px] px-2 py-0.5 rounded-full" style="background:var(--adm-danger-bg);color:var(--adm-danger-fg);">{{ $outOfStock->count() }}</span>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-[10px] tracking-[0.15em] uppercase" style="color:var(--adm-gold);">All products →</a>
            </div>
            @if($outOfStock->isEmpty())
            <div class="px-5 py-10 text-center text-sm" style="color:var(--adm-muted);">
                ✓ All active products are in stock.
            </div>
            @else
            <div>
                @foreach($outOfStock as $product)
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="block px-5 py-3 border-b transition-colors"
                   style="border-color:var(--adm-border);"
                   onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background='transparent'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded flex-shrink-0 overflow-hidden" style="background:var(--adm-surface-alt);">
                            @if($product->primary_image_url)
                            <img src="{{ $product->primary_image_url }}" alt="" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm truncate" style="color:var(--adm-text);">{{ $product->name }}</p>
                            <p class="text-[10px]" style="color:var(--adm-muted);">SKU: {{ $product->sku ?? '—' }} · ₦{{ number_format($product->price, 0) }}</p>
                        </div>
                        <span class="text-xs font-bold flex-shrink-0" style="color:var(--adm-danger-fg);">0 left</span>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Low Stock --}}
        <div class="adm-card overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:var(--adm-border);">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full" style="background:var(--adm-warn-fg);"></div>
                    <h2 class="text-sm font-semibold" style="color:var(--adm-text);">Low Stock</h2>
                    <span class="text-[10px] px-2 py-0.5 rounded-full" style="background:var(--adm-warn-bg);color:var(--adm-warn-fg);">{{ $lowStock->count() }}</span>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-[10px] tracking-[0.15em] uppercase" style="color:var(--adm-gold);">Manage →</a>
            </div>
            @if($lowStock->isEmpty())
            <div class="px-5 py-10 text-center text-sm" style="color:var(--adm-muted);">
                ✓ Stock levels are healthy.
            </div>
            @else
            <div>
                @foreach($lowStock as $product)
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="block px-5 py-3 border-b transition-colors"
                   style="border-color:var(--adm-border);"
                   onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background='transparent'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded flex-shrink-0 overflow-hidden" style="background:var(--adm-surface-alt);">
                            @if($product->primary_image_url)
                            <img src="{{ $product->primary_image_url }}" alt="" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm truncate" style="color:var(--adm-text);">{{ $product->name }}</p>
                            <p class="text-[10px]" style="color:var(--adm-muted);">SKU: {{ $product->sku ?? '—' }} · threshold {{ $product->low_stock_threshold }}</p>
                        </div>
                        <span class="text-xs font-bold flex-shrink-0" style="color:var(--adm-warn-fg);">{{ $product->stock_quantity }} left</span>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Recent orders + top products --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Orders --}}
        <div class="lg:col-span-2 adm-card overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:var(--adm-border);">
                <h2 class="text-sm font-semibold" style="color:var(--adm-text);">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] tracking-[0.15em] uppercase" style="color:var(--adm-gold);">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:var(--adm-surface-alt);">
                            <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Order</th>
                            <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Customer</th>
                            <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Amount</th>
                            <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        @php
                        $statusColors = match($order->status) {
                            'delivered'                       => ['var(--adm-success-bg)', 'var(--adm-success-fg)'],
                            'shipped','out_for_delivery'      => ['var(--adm-info-bg)',    'var(--adm-info-fg)'],
                            'paid','processing'               => ['rgba(55,18,32,0.18)', 'var(--adm-gold)'],
                            'cancelled','refunded'            => ['var(--adm-danger-bg)', 'var(--adm-danger-fg)'],
                            default                           => ['var(--adm-warn-bg)',   'var(--adm-warn-fg)'],
                        };
                        @endphp
                        <tr class="border-t" style="border-color:var(--adm-border);">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-medium" style="color:var(--adm-gold);">{{ $order->order_number }}</a>
                                <p class="text-[10px]" style="color:var(--adm-muted);">{{ $order->created_at->format('d M, g:ia') }}</p>
                            </td>
                            <td class="px-5 py-3" style="color:var(--adm-text);">{{ $order->customer_name ?? $order->guest_name ?? '—' }}</td>
                            <td class="px-5 py-3 font-medium" style="color:var(--adm-text-strong);">₦{{ number_format($order->total, 0) }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 text-[10px] tracking-wider uppercase rounded font-medium"
                                      style="background:{{ $statusColors[0] }};color:{{ $statusColors[1] }};">
                                    {{ ucfirst(str_replace('_',' ', $order->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-sm" style="color:var(--adm-muted);">
                                No orders yet — your customers will appear here.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="adm-card overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:var(--adm-border);">
                <h2 class="text-sm font-semibold" style="color:var(--adm-text);">Top Products</h2>
                <span class="text-[10px]" style="color:var(--adm-muted);">Last 30 days</span>
            </div>
            @if($topProducts->isEmpty())
            <div class="px-5 py-10 text-center text-sm" style="color:var(--adm-muted);">No sales data yet.</div>
            @else
            <div>
                @foreach($topProducts as $i => $product)
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="flex items-center gap-3 px-5 py-3 border-b transition-colors"
                   style="border-color:var(--adm-border);"
                   onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background='transparent'">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                          style="background:rgba(55,18,32,0.18);color:var(--adm-gold);">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm truncate" style="color:var(--adm-text);">{{ $product->name }}</p>
                        <p class="text-[10px]" style="color:var(--adm-muted);">{{ (int) $product->units_sold }} {{ Str::plural('unit', (int) $product->units_sold) }} sold</p>
                    </div>
                    <span class="text-xs font-medium flex-shrink-0" style="color:var(--adm-text);">₦{{ number_format($product->price, 0) }}</span>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
