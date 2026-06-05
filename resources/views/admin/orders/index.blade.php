@extends('layouts.admin')
@section('title', 'Orders')

@section('content')

{{-- Page header --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl" style="color:var(--adm-text);">Orders</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">Manage and fulfil customer orders</p>
    </div>
    <a href="{{ route('admin.orders.index') }}"
       class="text-xs tracking-widest uppercase transition-colors"
       style="color:var(--adm-muted);">
        ↺ Refresh
    </a>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mb-8">
    @php
    $statCards = [
        ['label' => 'Pending',        'value' => $stats['pending'],                   'dot' => 'rgba(201,169,111,0.80)', 'route' => route('admin.orders.index', ['status'=>'pending'])],
        ['label' => 'Awaiting Transfer','value' => $stats['pending_bank_confirmation'],'dot' => 'rgba(240,180,60,0.90)', 'route' => route('admin.orders.index', ['status'=>'pending_bank_confirmation'])],
        ['label' => 'Processing',     'value' => $stats['processing'],                'dot' => 'rgba(100,160,230,0.80)', 'route' => route('admin.orders.index', ['status'=>'processing'])],
        ['label' => 'Shipped',        'value' => $stats['shipped'],                   'dot' => 'rgba(80,200,160,0.80)',  'route' => route('admin.orders.index', ['status'=>'shipped'])],
        ['label' => "Today's Revenue",'value' => '₦'.number_format($stats['today_revenue'],0), 'dot' => 'rgba(201,169,111,0.90)', 'route' => null],
        ['label' => 'Month Revenue',  'value' => '₦'.number_format($stats['month_revenue'],0),  'dot' => 'rgba(100,200,120,0.80)', 'route' => null],
    ];
    @endphp
    @foreach($statCards as $card)
    @if($card['route'])
    <a href="{{ $card['route'] }}" class="adm-card p-4 block hover:border-[rgba(201,169,111,0.25)] transition-colors group">
    @else
    <div class="adm-card p-4">
    @endif
        <div class="flex items-center gap-2 mb-3">
            <div class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:{{ $card['dot'] }};"></div>
            <p class="text-[10px] tracking-[0.18em] uppercase" style="color:var(--adm-muted);">{{ $card['label'] }}</p>
        </div>
        <p class="font-display text-2xl" style="color:var(--adm-text);">{{ $card['value'] }}</p>
    @if($card['route'])
    </a>
    @else
    </div>
    @endif
    @endforeach
</div>

{{-- Filters --}}
<div class="adm-card p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none" style="color:var(--adm-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Order number, customer, email…"
                   class="adm-input w-full pl-9 text-sm">
        </div>

        <select name="status" class="adm-input text-sm">
            <option value="">All Statuses</option>
            @foreach([
                'pending'                   => 'Pending',
                'pending_bank_confirmation' => 'Awaiting Bank Transfer',
                'paid'                      => 'Paid',
                'processing'                => 'Processing',
                'packed'                    => 'Packed',
                'shipped'                   => 'Shipped',
                'out_for_delivery'          => 'Out for Delivery',
                'delivered'                 => 'Delivered',
                'cancelled'                 => 'Cancelled',
                'refunded'                  => 'Refunded',
            ] as $val => $lbl)
            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
        </select>

        <select name="payment_status" class="adm-input text-sm">
            <option value="">All Payments</option>
            <option value="paid"    {{ request('payment_status') === 'paid'    ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed"  {{ request('payment_status') === 'failed'  ? 'selected' : '' }}>Failed</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 text-xs tracking-widest uppercase font-medium transition-colors"
                    style="background:#C9A96F;color:#2C0F0A;">Filter</button>
            @if(request()->hasAny(['q','status','payment_status']))
            <a href="{{ route('admin.orders.index') }}"
               class="px-4 py-2.5 text-xs transition-colors"
               style="color:var(--adm-muted);border:1px solid var(--adm-border);">Clear</a>
            @endif
        </div>
    </form>
</div>

{{-- Active filter tag --}}
@if(request()->hasAny(['q','status','payment_status']))
<div class="flex flex-wrap gap-2 mb-4">
    @if(request('q'))
    <span class="flex items-center gap-1.5 px-3 py-1 text-xs rounded-full" style="background:rgba(201,169,111,0.12);color:#C9A96F;border:1px solid rgba(201,169,111,0.20);">
        Search: "{{ request('q') }}"
    </span>
    @endif
    @if(request('status'))
    <span class="flex items-center gap-1.5 px-3 py-1 text-xs rounded-full" style="background:rgba(201,169,111,0.12);color:#C9A96F;border:1px solid rgba(201,169,111,0.20);">
        Status: {{ ucfirst(str_replace('_',' ',request('status'))) }}
    </span>
    @endif
    @if(request('payment_status'))
    <span class="flex items-center gap-1.5 px-3 py-1 text-xs rounded-full" style="background:rgba(201,169,111,0.12);color:#C9A96F;border:1px solid rgba(201,169,111,0.20);">
        Payment: {{ ucfirst(request('payment_status')) }}
    </span>
    @endif
</div>
@endif

{{-- Orders table --}}
<div class="adm-card overflow-hidden">
    <table class="w-full">
        <thead>
            <tr style="border-bottom:1px solid var(--adm-border);">
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Order</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Customer</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase font-medium hidden lg:table-cell" style="color:var(--adm-muted);">Items</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Total</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase font-medium hidden sm:table-cell" style="color:var(--adm-muted);">Payment</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Status</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
            // Status badge styles
            $statusStyle = match($order->status) {
                'delivered'                => 'background:rgba(100,200,120,0.15);color:rgba(22,120,60,0.95);',
                'shipped','out_for_delivery'=> 'background:rgba(80,200,160,0.15);color:rgba(8,130,160,0.95);',
                'processing','packed'      => 'background:rgba(100,160,230,0.15);color:rgba(37,90,200,0.95);',
                'paid'                     => 'background:rgba(100,200,120,0.12);color:rgba(22,120,60,0.95);',
                'pending_bank_confirmation'=> 'background:rgba(240,180,60,0.15);color:rgba(160,100,0,0.95);',
                'cancelled','refunded'     => 'background:rgba(220,80,80,0.12);color:rgba(185,40,40,0.90);',
                default                    => 'background:rgba(201,169,111,0.12);color:rgba(201,169,111,0.90);',
            };
            $statusLabel = match($order->status) {
                'pending_bank_confirmation' => 'Awaiting Transfer',
                'out_for_delivery'          => 'Out for Delivery',
                default                     => ucfirst(str_replace('_',' ',$order->status)),
            };
            // Payment badge styles
            $payStyle = match($order->payment_status) {
                'paid'   => 'background:rgba(100,200,120,0.12);color:rgba(22,120,60,0.95);',
                'failed' => 'background:rgba(220,80,80,0.12);color:rgba(185,40,40,0.90);',
                default  => 'background:rgba(201,169,111,0.10);color:rgba(201,169,111,0.80);',
            };
            @endphp
            <tr style="border-bottom:1px solid var(--adm-border);" class="group transition-colors hover:bg-white/[0.025]">

                {{-- Order number + date --}}
                <td class="px-5 py-4">
                    <p class="text-sm font-semibold font-mono" style="color:var(--adm-text);">{{ $order->order_number }}</p>
                    <p class="text-[11px] mt-0.5" style="color:var(--adm-muted);">{{ $order->created_at->format('d M Y · g:ia') }}</p>
                    @if($order->tracking_code)
                    <p class="text-[10px] mt-0.5 tracking-widest" style="color:rgba(201,169,111,0.55);">{{ $order->tracking_code }}</p>
                    @endif
                </td>

                {{-- Customer --}}
                <td class="px-5 py-4 hidden md:table-cell">
                    <p class="text-sm" style="color:var(--adm-text);">{{ $order->user?->name ?? $order->guest_name ?? 'Guest' }}</p>
                    <p class="text-xs mt-0.5" style="color:var(--adm-muted);">{{ $order->user?->email ?? $order->guest_email }}</p>
                </td>

                {{-- Items --}}
                <td class="px-5 py-4 hidden lg:table-cell">
                    <p class="text-xs" style="color:var(--adm-muted);">
                        {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                    </p>
                </td>

                {{-- Total --}}
                <td class="px-5 py-4 text-right">
                    <span class="text-sm font-semibold" style="color:#C9A96F;">₦{{ number_format($order->total, 0) }}</span>
                </td>

                {{-- Payment status --}}
                <td class="px-5 py-4 text-center hidden sm:table-cell">
                    <span class="px-2.5 py-1 text-[10px] tracking-widest uppercase rounded-sm" style="{{ $payStyle }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </td>

                {{-- Order status --}}
                <td class="px-5 py-4 text-center">
                    <span class="px-2.5 py-1 text-[10px] tracking-widest uppercase rounded-sm whitespace-nowrap" style="{{ $statusStyle }}">
                        {{ $statusLabel }}
                    </span>
                </td>

                {{-- Actions --}}
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium transition-all"
                       style="background:rgba(201,169,111,0.12);color:var(--adm-text);border:1px solid rgba(201,169,111,0.20);">
                        View
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-20 text-center">
                    <svg class="w-10 h-10 mx-auto mb-3" style="color:var(--adm-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="text-sm" style="color:var(--adm-muted);">No orders match your filters</p>
                    @if(request()->hasAny(['q','status','payment_status']))
                    <a href="{{ route('admin.orders.index') }}" class="text-xs mt-2 inline-block" style="color:#C9A96F;">Clear filters</a>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination + result count --}}
    @if($orders->total() > 0)
    <div class="px-5 py-4 flex items-center justify-between gap-4" style="border-top:1px solid var(--adm-border);">
        <p class="text-xs" style="color:var(--adm-muted);">
            Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ number_format($orders->total()) }} orders
        </p>
        <div>{{ $orders->links() }}</div>
    </div>
    @endif
</div>

@endsection
