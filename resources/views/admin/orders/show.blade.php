@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.orders.index') }}" class="text-gray-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="font-display text-2xl text-white">{{ $order->order_number }}</h1>
        <p class="text-gray-400 text-sm mt-0.5">Placed {{ $order->created_at->format('d M Y, g:ia') }}</p>
    </div>
    <div class="ml-auto flex gap-3">
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
           class="px-4 py-2 bg-[#2A2A2A] text-gray-300 hover:text-white text-xs tracking-widest uppercase transition-colors">
            View Invoice
        </a>
    </div>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 bg-green-500/10 border border-green-500/30 text-green-400 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Order Items --}}
    <div class="lg:col-span-2 space-y-6">

        <div class="bg-[#1E1E1E] border border-[#2A2A2A] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#2A2A2A]">
                <h2 class="text-sm font-medium text-white tracking-wide">Order Items</h2>
            </div>
            <div class="divide-y divide-[#2A2A2A]">
                @foreach($order->items as $item)
                <div class="px-6 py-4 flex gap-4 items-center">
                    <div class="w-14 h-14 bg-[#2A2A2A] flex-shrink-0 overflow-hidden">
                        <img src="{{ $item->product?->primary_image_url ?? '' }}"
                             alt="{{ $item->product_name }}"
                             class="w-full h-full object-cover"
                             onerror="this.src='https://placehold.co/56x56/2A2A2A/666?text=?'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-medium">{{ $item->product_name }}</p>
                        @if($item->variant_name)
                        <p class="text-gray-500 text-xs">{{ $item->variant_name }}</p>
                        @endif
                        @if($item->scent_note)
                        <p class="text-amber-500/70 text-xs">Scent: {{ $item->scent_note }}</p>
                        @endif
                        <p class="text-gray-400 text-xs mt-0.5">Qty: {{ $item->quantity }} × ₦{{ number_format($item->unit_price, 0) }}</p>
                    </div>
                    <p class="text-white text-sm font-medium">₦{{ number_format($item->total_price, 0) }}</p>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-[#2A2A2A] space-y-2 text-sm">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal</span>
                    <span>₦{{ number_format($order->subtotal, 0) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-between text-green-400">
                    <span>Discount</span>
                    <span>−₦{{ number_format($order->discount, 0) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-gray-400">
                    <span>Shipping</span>
                    <span>{{ $order->shipping_fee > 0 ? '₦'.number_format($order->shipping_fee, 0) : 'Free' }}</span>
                </div>
                <div class="flex justify-between text-white font-medium text-base pt-2 border-t border-[#2A2A2A]">
                    <span class="font-display">Total</span>
                    <span class="font-display text-sage">₦{{ number_format($order->total, 0) }}</span>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6">
            <h2 class="text-sm font-medium text-white tracking-wide mb-5">Update Order Status</h2>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">New Status</label>
                        <select name="status" required class="admin-input">
                            @foreach(['pending','processing','packed','shipped','out_for_delivery','delivered','cancelled','refunded'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ',$s)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Internal Note</label>
                        <input type="text" name="note" class="admin-input" placeholder="Optional note…">
                    </div>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-sage text-cream text-xs tracking-widest uppercase font-medium hover:bg-sage-800 transition-colors">
                    Update Status
                </button>
            </form>
        </div>

        {{-- Notes --}}
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6">
            <h2 class="text-sm font-medium text-white tracking-wide mb-5">Admin Notes</h2>
            @if($order->tracking_code)
            <div class="mb-5 p-3 rounded" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
                <p class="admin-label">Tracking Code (auto-generated)</p>
                <p class="font-mono text-sm" style="color:var(--adm-gold);">{{ $order->tracking_code }}</p>
            </div>
            @endif
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="admin-label">Internal Note <span class="normal-case tracking-normal" style="color:var(--adm-muted);opacity:0.7;">(not visible to customer)</span></label>
                    <textarea name="notes" rows="3" class="admin-input resize-none" placeholder="Internal notes…">{{ $order->notes }}</textarea>
                </div>
                <button type="submit" class="px-6 py-2.5 text-xs tracking-widest uppercase transition-colors"
                        style="background:var(--adm-accent);color:#F5EDE4;">
                    Save Note
                </button>
            </form>
        </div>

        {{-- Status History --}}
        @if($order->statusHistory->count())
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6">
            <h2 class="text-sm font-medium text-white tracking-wide mb-5">Status History</h2>
            <div class="space-y-4">
                @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                <div class="flex gap-4">
                    <div class="w-2 h-2 rounded-full bg-sage mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-white text-sm font-medium">{{ ucfirst(str_replace('_',' ',$history->status)) }}</p>
                        <p class="text-gray-500 text-xs">{{ $history->note }}</p>
                        <p class="text-gray-600 text-xs mt-0.5">{{ $history->created_at->format('d M Y, g:ia') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Customer --}}
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6">
            <h3 class="text-[11px] font-medium text-white tracking-widest uppercase mb-4">Customer</h3>
            @if($order->user)
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-sage/20 flex items-center justify-center text-sage font-display font-semibold">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-white text-sm font-medium">{{ $order->user->name }}</p>
                    <p class="text-gray-500 text-xs">{{ $order->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.customers.show', $order->user) }}" class="text-xs text-sage underline hover:text-sage-800 transition-colors">View Customer →</a>
            @else
            <p class="text-gray-500 text-sm">Guest order</p>
            @endif
        </div>

        {{-- Payment --}}
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6">
            <h3 class="text-[11px] font-medium text-white tracking-widest uppercase mb-4">Payment</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="{{ $order->payment_status === 'paid' ? 'text-green-400' : 'text-amber-400' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                @if($order->payment)
                <div class="flex justify-between">
                    <span class="text-gray-500">Reference</span>
                    <span class="text-gray-300 text-xs font-mono">{{ $order->payment->gateway_reference }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] p-6">
            <h3 class="text-[11px] font-medium text-white tracking-widest uppercase mb-4">Shipping Address</h3>
            @php $addr = $order->shipping_address; @endphp
            <div class="text-sm text-gray-400 space-y-0.5">
                <p class="text-white">{{ $addr['full_name'] ?? $order->user?->name }}</p>
                <p>{{ $addr['address_line_1'] ?? '' }}</p>
                @if(!empty($addr['address_line_2']))<p>{{ $addr['address_line_2'] }}</p>@endif
                <p>{{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }}</p>
                @if($order->tracking_code)
                <div class="mt-3 pt-3 border-t border-[#2A2A2A]">
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Tracking Code</p>
                    <p class="text-white font-mono text-sm">{{ $order->tracking_code }}</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<style>
.admin-label { @apply block text-[10px] tracking-[0.2em] uppercase text-gray-500 mb-2; }
.admin-input { @apply w-full bg-[#2A2A2A] border border-[#3A3A3A] px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-sage transition-colors; }
</style>
@endsection
