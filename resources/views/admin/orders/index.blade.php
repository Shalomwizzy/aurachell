@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Orders</h1>
        <p class="text-text-muted text-sm mt-1">Manage and fulfil customer orders</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label' => 'Pending', 'value' => $stats['pending'], 'color' => 'text-mahogany', 'bg' => 'bg-mahogany/12'],
        ['label' => 'Processing', 'value' => $stats['processing'], 'color' => 'text-mahogany', 'bg' => 'bg-sand/12'],
        ['label' => 'Shipped', 'value' => $stats['shipped'], 'color' => 'text-mahogany', 'bg' => 'bg-mahogany/12'],
        ['label' => "Today's Revenue", 'value' => '₦'.number_format($stats['today_revenue'], 0), 'color' => 'text-sage', 'bg' => 'bg-sage/10'],
    ] as $stat)
    <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-5">
        <p class="text-[10px] text-text-muted tracking-[0.2em] uppercase mb-2">{{ $stat['label'] }}</p>
        <p class="font-display text-2xl {{ $stat['color'] }}">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Order # or customer…"
                   class="w-full bg-[rgba(55,18,32,0.10)] border border-[rgba(55,18,32,0.15)] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors" style="color:rgba(250,245,237,0.85);">
        </div>
        <select name="status" class="bg-[rgba(55,18,32,0.10)] border border-[rgba(55,18,32,0.15)] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors">
            <option value="">All Statuses</option>
            @foreach(['pending','processing','packed','shipped','out_for_delivery','delivered','cancelled','refunded'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <select name="payment_status" class="bg-[rgba(55,18,32,0.10)] border border-[rgba(55,18,32,0.15)] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors">
            <option value="">All Payments</option>
            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-sage text-cream text-xs tracking-widest uppercase font-medium hover:bg-sage-800 transition-colors">Filter</button>
        @if(request()->hasAny(['q','status','payment_status']))
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 text-xs text-text-muted hover:text-cream transition-colors">Clear</a>
        @endif
    </form>
</div>

<div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-[rgba(55,18,32,0.10)]">
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Order</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium hidden md:table-cell">Customer</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Total</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium hidden sm:table-cell">Payment</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Status</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[rgba(55,18,32,0.10)]">
            @forelse($orders as $order)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4">
                    <p class="text-white text-sm font-medium">{{ $order->order_number }}</p>
                    <p class="text-text-muted text-xs mt-0.5">{{ $order->created_at->format('d M Y, g:ia') }}</p>
                    <p class="text-text-muted text-xs">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</p>
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <p class="text-warmSand-300 text-sm">{{ $order->user?->name ?? $order->guest_name ?? 'Guest' }}</p>
                    <p class="text-text-muted text-xs">{{ $order->user?->email ?? $order->guest_email }}</p>
                </td>
                <td class="px-5 py-4 text-right">
                    <span class="text-white text-sm font-medium">₦{{ number_format($order->total, 0) }}</span>
                </td>
                <td class="px-5 py-4 text-center hidden sm:table-cell">
                    @php
                    $pColors = ['paid' => 'text-mahogany bg-mahogany/12', 'pending' => 'text-mahogany bg-mahogany/12', 'failed' => 'text-mahogany bg-mahogany/10'];
                    $pColor = $pColors[$order->payment_status] ?? 'text-text-muted bg-sand/10';
                    @endphp
                    <span class="px-2 py-0.5 {{ $pColor }} text-[10px] tracking-widest uppercase">{{ $order->payment_status }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    @php
                    $sColors = [
                        'delivered' => 'text-mahogany bg-mahogany/12',
                        'shipped' => 'text-mahogany bg-sand/12',
                        'out_for_delivery' => 'text-mahogany bg-sand/12',
                        'cancelled' => 'text-mahogany bg-mahogany/10',
                        'refunded' => 'text-mahogany/70 bg-mahogany/5',
                        'processing' => 'text-mahogany bg-mahogany/12',
                        'packed' => 'text-mahogany bg-sand/12',
                        'pending' => 'text-mahogany bg-mahogany/12',
                    ];
                    $sColor = $sColors[$order->status] ?? 'text-text-muted bg-sand/10';
                    @endphp
                    <span class="px-2 py-0.5 {{ $sColor }} text-[10px] tracking-widest uppercase">{{ str_replace('_',' ',$order->status) }}</span>
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="px-3 py-1.5 bg-[rgba(55,18,32,0.10)] text-warmSand-300 hover:text-cream text-xs transition-colors">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center text-text-muted">No orders found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-[rgba(55,18,32,0.10)]">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
