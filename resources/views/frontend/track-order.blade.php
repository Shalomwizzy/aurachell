@extends('layouts.app')
@section('title', 'Track Your Order — Aurachell')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <p class="font-sans text-xs tracking-[0.3em] uppercase text-sage mb-3">Order Tracking</p>
        <h1 class="font-display text-4xl text-text-dark mb-3">Track Your Order</h1>
        <p class="text-text-muted font-sans">Enter your tracking code and email to see your order status.</p>
    </div>

    <form method="POST" action="{{ route('track-order.submit') }}" class="bg-sand/10 p-8 mb-10">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Tracking Code</label>
                <input type="text" name="tracking_code" value="{{ old('tracking_code', isset($order) ? $order->tracking_code : '') }}"
                    placeholder="e.g. ACH7K2X9" maxlength="8" class="input-luxury uppercase w-full" required>
                @error('tracking_code')<p class="text-mahogany text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="The email used at checkout"
                    class="input-luxury w-full" required>
                @error('email')<p class="text-mahogany text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-primary w-full">Track Order</button>
        </div>
    </form>

    @isset($order)
    <div class="bg-white p-8 shadow-luxury">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <p class="text-xs font-sans text-text-muted uppercase tracking-wider">Order</p>
                <p class="font-display text-xl">{{ $order->order_number }}</p>
            </div>
            <span class="badge {{ match($order->status) {
                'delivered' => 'bg-mahogany/15 text-mahogany',
                'shipped', 'out_for_delivery' => 'bg-sand/20 text-text-muted',
                'cancelled', 'refunded' => 'bg-mahogany/10 text-mahogany',
                default => 'bg-sand/30 text-sage'
            } }} text-xs">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
        </div>

        {{-- Timeline --}}
        <div class="relative">
            @php
            $statuses = [
                ['key' => 'pending', 'label' => 'Order Placed', 'icon' => '📋'],
                ['key' => 'paid', 'label' => 'Payment Confirmed', 'icon' => '✅'],
                ['key' => 'processing', 'label' => 'Processing', 'icon' => '📦'],
                ['key' => 'shipped', 'label' => 'Shipped', 'icon' => '🚚'],
                ['key' => 'out_for_delivery', 'label' => 'Out for Delivery', 'icon' => '🏠'],
                ['key' => 'delivered', 'label' => 'Delivered', 'icon' => '🎉'],
            ];
            $statusOrder = ['pending', 'paid', 'processing', 'shipped', 'out_for_delivery', 'delivered'];
            $currentIndex = array_search($order->status, $statusOrder);
            @endphp
            <div class="space-y-4">
                @foreach($statuses as $i => $s)
                @php $isDone = $currentIndex !== false && $i <= $currentIndex; @endphp
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg flex-shrink-0 {{ $isDone ? 'bg-sage text-cream' : 'bg-sand/30' }}">
                        {{ $isDone ? '✓' : ($i + 1) }}
                    </div>
                    <div class="{{ $isDone ? 'text-text-dark' : 'text-text-muted' }}">
                        <p class="font-sans text-sm font-medium">{{ $s['label'] }}</p>
                        @if($isDone)
                        @php $historyEntry = $order->statusHistory->firstWhere('status', $s['key']); @endphp
                        @if($historyEntry)
                        <p class="text-xs text-text-muted">{{ $historyEntry->created_at->format('d M Y, g:ia') }}</p>
                        @endif
                        @endif
                    </div>
                </div>
                @if(!$loop->last)
                <div class="ml-5 w-0 h-4 border-l-2 {{ $isDone ? 'border-sage/50' : 'border-sand' }}"></div>
                @endif
                @endforeach
            </div>
        </div>

        @if($order->tracking_number)
        <div class="mt-6 pt-6 border-t border-sand/50 text-sm font-sans text-text-muted">
            <span>Courier: <strong class="text-text-dark">{{ $order->courier ?? 'Standard Delivery' }}</strong></span>
            <span class="ml-4">Tracking #: <strong class="text-text-dark">{{ $order->tracking_number }}</strong></span>
        </div>
        @endif
    </div>
    @endisset
</div>
@endsection
