@extends('layouts.account')
@section('title', 'Track Order')
@section('account-content')
<h1 class="font-display text-2xl text-text-dark mb-6">Track My Orders</h1>

@forelse($orders as $order)
<div class="bg-white shadow-luxury p-5 mb-4">
    <div class="flex items-start justify-between flex-wrap gap-3 mb-4">
        <div>
            <a href="{{ route('account.order.detail', $order->order_number) }}" class="font-display text-lg text-text-dark hover:text-sage transition-colors">{{ $order->order_number }}</a>
            <p class="text-xs text-text-muted font-sans mt-0.5">Placed {{ $order->created_at->format('d M Y') }}</p>
        </div>
        <span class="badge {{ match($order->status) {
            'delivered' => 'bg-caramel/15 text-bronze',
            'shipped','out_for_delivery' => 'bg-sand/20 text-text-muted',
            'cancelled','refunded' => 'bg-mahogany/10 text-mahogany',
            default => 'bg-sand/30 text-sage'
        } }} text-[10px]">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
    </div>

    @if($order->tracking_code)
    <div class="mb-4 p-3 bg-sand/20 border border-sand/40">
        <p class="text-[10px] font-sans tracking-[0.15em] uppercase text-text-muted mb-1">Tracking Code</p>
        <p class="font-sans font-semibold text-text-dark">{{ $order->tracking_code }}</p>
    </div>
    @endif

    {{-- Progress bar --}}
    @php
        $steps = ['pending' => 0, 'paid' => 1, 'processing' => 2, 'shipped' => 3, 'out_for_delivery' => 4, 'delivered' => 5];
        $currentStep = $steps[$order->status] ?? 0;
        if (in_array($order->status, ['cancelled', 'refunded'])) $currentStep = -1;
    @endphp

    @if($currentStep >= 0)
    <div class="mt-2">
        <div class="flex items-center justify-between text-[9px] tracking-wider uppercase text-text-muted font-sans mb-2">
            @foreach(['Placed', 'Paid', 'Processing', 'Shipped', 'Out for Delivery', 'Delivered'] as $i => $label)
            <span class="{{ $currentStep >= $i ? 'text-sage font-semibold' : '' }}">{{ $label }}</span>
            @endforeach
        </div>
        <div class="h-1.5 bg-sand/40 rounded-full overflow-hidden">
            <div class="h-full bg-sage transition-all duration-500" style="width: {{ max(0, ($currentStep / 5) * 100) }}%"></div>
        </div>
    </div>
    @else
    <p class="text-xs text-mahogany font-sans">This order has been {{ $order->status }}.</p>
    @endif
</div>
@empty
<div class="bg-white shadow-luxury p-10 text-center">
    <p class="font-display text-xl text-text-dark mb-3">No orders to track</p>
    <p class="text-text-muted text-sm mb-6 font-sans">Confirmed orders with tracking will appear here.</p>
    <a href="{{ route('shop') }}" class="btn-primary">Browse Collection</a>
</div>
@endforelse
@endsection
