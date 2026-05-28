@extends('layouts.account')
@section('title', 'My Orders')
@section('account-content')
<h1 class="font-display text-2xl text-text-dark mb-6">My Orders</h1>

@forelse($orders as $order)
<div class="bg-white shadow-luxury p-5 mb-4">
    <div class="flex items-center justify-between mb-3 flex-wrap gap-3">
        <div>
            <a href="{{ route('account.order.detail', $order->order_number) }}" class="font-display text-lg text-text-dark hover:text-sage transition-colors">{{ $order->order_number }}</a>
            <p class="text-xs text-text-muted font-sans">{{ $order->created_at->format('d M Y, g:ia') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-display text-lg text-sage">₦{{ number_format($order->total, 0) }}</span>
            <span class="badge {{ match($order->status) {
                'delivered' => 'bg-mahogany/15 text-mahogany',
                'shipped','out_for_delivery' => 'bg-sand/20 text-text-muted',
                'cancelled','refunded' => 'bg-mahogany/10 text-mahogany',
                default => 'bg-sand/30 text-sage'
            } }} text-[10px]">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
        </div>
    </div>
    <div class="flex items-center justify-between flex-wrap gap-2">
        <p class="text-sm text-text-muted font-sans">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</p>
        <div class="flex gap-3">
            @if($order->tracking_code)
            <a href="{{ route('track-order') }}" class="btn-ghost text-xs">Track</a>
            @endif
            <a href="{{ route('account.order.detail', $order->order_number) }}" class="btn-secondary text-xs py-1.5 px-4">View Details</a>
        </div>
    </div>
</div>
@empty
<div class="bg-white shadow-luxury p-10 text-center">
    <p class="font-display text-xl text-text-dark mb-3">No orders yet</p>
    <p class="text-text-muted text-sm mb-6">When you place an order, it'll appear here.</p>
    <a href="{{ route('shop') }}" class="btn-primary">Start Shopping</a>
</div>
@endforelse
{{ $orders->links() }}
@endsection
