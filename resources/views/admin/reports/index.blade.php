@extends('layouts.admin')
@section('title', 'Reports & Analytics')
@section('breadcrumb', 'Finance')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text);">Reports & Analytics</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">Sales performance — paid orders only</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <form method="GET" class="flex items-center gap-2">
                <select name="period" onchange="this.form.submit()"
                        class="px-3 py-2 text-sm focus:outline-none"
                        style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
                    @foreach(['7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days', '365' => 'Last year'] as $val => $label)
                    <option value="{{ $val }}" {{ $period == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.reports.export', ['period' => $period]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs tracking-wider uppercase transition-opacity hover:opacity-80"
               style="border:1px solid var(--adm-border);color:var(--adm-muted);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Revenue',       'value' => '₦' . number_format($revenue, 0),       'tone' => 'success'],
            ['label' => 'Paid Orders',   'value' => number_format($ordersCount),              'tone' => 'info'],
            ['label' => 'Avg Order',     'value' => '₦' . number_format($avgOrderValue, 0),  'tone' => 'warn'],
            ['label' => 'New Customers', 'value' => number_format($newCustomers),             'tone' => 'accent'],
        ] as $kpi)
        @php
        $toneBg = match($kpi['tone']) {
            'success' => 'var(--adm-success-bg)', 'info' => 'var(--adm-info-bg)',
            'warn'    => 'var(--adm-warn-bg)',    default => 'rgba(107,32,22,0.18)',
        };
        $toneFg = match($kpi['tone']) {
            'success' => 'var(--adm-success-fg)', 'info' => 'var(--adm-info-fg)',
            'warn'    => 'var(--adm-warn-fg)',    default => 'var(--adm-gold)',
        };
        @endphp
        <div class="p-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-3" style="color:var(--adm-muted);">{{ $kpi['label'] }}</p>
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ $kpi['value'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 p-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-sm font-medium mb-5" style="color:var(--adm-text);">Revenue Over Time</p>
            @if($revenueByDay->count())
            @php $max = $revenueByDay->max('total') ?: 1; @endphp
            <div class="flex items-end gap-1 h-36">
                @foreach($revenueByDay as $day)
                <div class="flex-1 flex flex-col items-center gap-1 group relative"
                     title="{{ $day->date }}: ₦{{ number_format($day->total, 0) }}">
                    <div class="w-full rounded-t transition-all"
                         style="height:{{ max(4, ($day->total / $max) * 100) }}%;background:var(--adm-accent);opacity:0.8;"
                         onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'"></div>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-3 text-[9px]" style="color:var(--adm-muted);">
                <span>{{ $revenueByDay->first()?->date }}</span>
                <span>{{ $revenueByDay->last()?->date }}</span>
            </div>
            @else
            <div class="h-36 flex items-center justify-center text-sm" style="color:var(--adm-muted);">
                No revenue data for this period.
            </div>
            @endif
        </div>

        {{-- Orders by Status --}}
        <div class="p-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-sm font-medium mb-5" style="color:var(--adm-text);">Orders by Status</p>
            @forelse($ordersByStatus as $status => $count)
            @php
            $sbg = match($status) {
                'pending'    => 'var(--adm-warn-bg)',
                'processing' => 'var(--adm-info-bg)',
                'shipped', 'out_for_delivery' => 'rgba(168,85,247,0.12)',
                'delivered'  => 'var(--adm-success-bg)',
                'cancelled', 'refunded' => 'var(--adm-danger-bg)',
                default      => 'rgba(212,184,160,0.08)',
            };
            $sfg = match($status) {
                'pending'    => 'var(--adm-warn-fg)',
                'processing' => 'var(--adm-info-fg)',
                'shipped', 'out_for_delivery' => '#c4b5fd',
                'delivered'  => 'var(--adm-success-fg)',
                'cancelled', 'refunded' => 'var(--adm-danger-fg)',
                default      => 'var(--adm-muted)',
            };
            @endphp
            <div class="flex items-center justify-between py-2.5" style="border-bottom:1px solid var(--adm-border);">
                <span class="text-xs px-2.5 py-1 uppercase tracking-wider"
                      style="background:{{ $sbg }};color:{{ $sfg }};">{{ str_replace('_',' ',$status) }}</span>
                <span class="text-sm font-medium" style="color:var(--adm-text);">{{ $count }}</span>
            </div>
            @empty
            <p class="text-sm text-center py-8" style="color:var(--adm-muted);">No orders yet</p>
            @endforelse
        </div>

        {{-- Top Products by Revenue --}}
        <div class="lg:col-span-3" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <div class="px-6 py-4" style="border-bottom:1px solid var(--adm-border);">
                <p class="text-sm font-medium" style="color:var(--adm-text);">Top Products by Revenue</p>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:var(--adm-surface-alt);">
                        <th class="text-left px-6 py-3 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">#</th>
                        <th class="text-left px-6 py-3 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Product</th>
                        <th class="text-right px-6 py-3 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Units Sold</th>
                        <th class="text-right px-6 py-3 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $i => $product)
                    <tr style="border-top:1px solid var(--adm-border);" class="transition-colors"
                        onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background='transparent'">
                        <td class="px-6 py-3.5 text-xs" style="color:var(--adm-muted);">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5" style="color:var(--adm-text);">{{ $product->name }}</td>
                        <td class="px-6 py-3.5 text-right" style="color:var(--adm-muted);">{{ number_format($product->qty) }}</td>
                        <td class="px-6 py-3.5 text-right font-medium" style="color:var(--adm-gold);">₦{{ number_format($product->revenue, 0) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm" style="color:var(--adm-muted);">No product sales data yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

    </div>
</div>
@endsection
