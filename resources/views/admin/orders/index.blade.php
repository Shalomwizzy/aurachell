@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Orders</h1>
        <p class="text-gray-400 text-sm mt-1">Manage and fulfil customer orders</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label' => 'Pending', 'value' => $stats['pending'], 'color' => 'text-amber-400', 'bg' => 'bg-amber-400/10'],
        ['label' => 'Processing', 'value' => $stats['processing'], 'color' => 'text-blue-400', 'bg' => 'bg-blue-400/10'],
        ['label' => 'Shipped', 'value' => $stats['shipped'], 'color' => 'text-green-400', 'bg' => 'bg-green-400/10'],
        ['label' => "Today's Revenue", 'value' => '₦'.number_format($stats['today_revenue'], 0), 'color' => 'text-sage', 'bg' => 'bg-sage/10'],
    ] as $stat)
    <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-5">
        <p class="text-[10px] text-gray-500 tracking-[0.2em] uppercase mb-2">{{ $stat['label'] }}</p>
        <p class="font-display text-2xl {{ $stat['color'] }}">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="bg-[#1E1E1E] border border-[#2A2A2A] p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Order # or customer…"
                   class="w-full bg-[#2A2A2A] border border-[#3A3A3A] px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-sage transition-colors">
        </div>
        <select name="status" class="bg-[#2A2A2A] border border-[#3A3A3A] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors">
            <option value="">All Statuses</option>
            @foreach(['pending','processing','packed','shipped','out_for_delivery','delivered','cancelled','refunded'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <select name="payment_status" class="bg-[#2A2A2A] border border-[#3A3A3A] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors">
            <option value="">All Payments</option>
            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-sage text-cream text-xs tracking-widest uppercase font-medium hover:bg-sage-800 transition-colors">Filter</button>
        @if(request()->hasAny(['q','status','payment_status']))
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 text-xs text-gray-400 hover:text-white transition-colors">Clear</a>
        @endif
    </form>
</div>

<div class="bg-[#1E1E1E] border border-[#2A2A2A] overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-[#2A2A2A]">
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium">Order</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium hidden md:table-cell">Customer</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium">Total</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium hidden sm:table-cell">Payment</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium">Status</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#2A2A2A]">
            @forelse($orders as $order)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4">
                    <p class="text-white text-sm font-medium">{{ $order->order_number }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">{{ $order->created_at->format('d M Y, g:ia') }}</p>
                    <p class="text-gray-500 text-xs">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</p>
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <p class="text-gray-300 text-sm">{{ $order->user?->name ?? $order->guest_name ?? 'Guest' }}</p>
                    <p class="text-gray-500 text-xs">{{ $order->user?->email ?? $order->guest_email }}</p>
                </td>
                <td class="px-5 py-4 text-right">
                    <span class="text-white text-sm font-medium">₦{{ number_format($order->total, 0) }}</span>
                </td>
                <td class="px-5 py-4 text-center hidden sm:table-cell">
                    @php
                    $pColors = ['paid' => 'text-green-400 bg-green-400/10', 'pending' => 'text-amber-400 bg-amber-400/10', 'failed' => 'text-red-400 bg-red-400/10'];
                    $pColor = $pColors[$order->payment_status] ?? 'text-gray-400 bg-gray-400/10';
                    @endphp
                    <span class="px-2 py-0.5 {{ $pColor }} text-[10px] tracking-widest uppercase">{{ $order->payment_status }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    @php
                    $sColors = [
                        'delivered' => 'text-green-400 bg-green-400/10',
                        'shipped' => 'text-blue-400 bg-blue-400/10',
                        'out_for_delivery' => 'text-blue-300 bg-blue-300/10',
                        'cancelled' => 'text-red-400 bg-red-400/10',
                        'refunded' => 'text-red-300 bg-red-300/10',
                        'processing' => 'text-purple-400 bg-purple-400/10',
                        'packed' => 'text-indigo-400 bg-indigo-400/10',
                        'pending' => 'text-amber-400 bg-amber-400/10',
                    ];
                    $sColor = $sColors[$order->status] ?? 'text-gray-400 bg-gray-400/10';
                    @endphp
                    <span class="px-2 py-0.5 {{ $sColor }} text-[10px] tracking-widest uppercase">{{ str_replace('_',' ',$order->status) }}</span>
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="px-3 py-1.5 bg-[#2A2A2A] text-gray-300 hover:text-white text-xs transition-colors">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center text-gray-500">No orders found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-[#2A2A2A]">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
