@extends('layouts.app')
@section('title', 'Order Confirmed — Aurachell')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="w-20 h-20 bg-sage/10 rounded-full flex items-center justify-center mx-auto mb-8 animate-fade-in">
        <svg class="w-10 h-10 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h1 class="font-display text-4xl text-text-dark mb-3">Order Confirmed!</h1>
    <p class="font-sans text-text-muted text-lg mb-8">Thank you for your purchase. We're preparing your order with care.</p>

    <div class="bg-sage/5 border border-sage/20 p-8 mb-8">
        <p class="font-sans text-xs tracking-widest uppercase text-text-muted mb-3">Your Order Number</p>
        <p class="font-display text-2xl text-text-dark mb-5">{{ $order->order_number }}</p>

        @if($order->tracking_code)
        <p class="font-sans text-xs tracking-widest uppercase text-text-muted mb-3">Tracking Code</p>
        <p class="font-display text-3xl text-sage font-bold tracking-widest mb-3">{{ $order->tracking_code }}</p>
        <p class="text-xs text-text-muted font-sans">Save this code — you can use it to track your order at any time.</p>
        @endif
    </div>

    <p class="text-sm text-text-muted font-sans mb-8">
        A confirmation email has been sent to <strong>{{ $order->customer_email }}</strong> with your order details.
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('track-order') }}" class="btn-primary">Track Your Order</a>
        <a href="{{ route('shop') }}" class="btn-secondary">Continue Shopping</a>
    </div>

    <div class="mt-12 border-t border-sand/50 pt-8">
        <h3 class="font-display text-xl mb-5">Order Summary</h3>
        <div class="space-y-3 text-sm font-sans text-left">
            @foreach($order->items as $item)
            <div class="flex justify-between">
                <span class="text-text-dark">{{ $item->product_name }} × {{ $item->quantity }}</span>
                <span class="text-sage">₦{{ number_format($item->total_price, 0) }}</span>
            </div>
            @endforeach
            <div class="border-t border-sand/30 pt-3 flex justify-between font-medium">
                <span>Total</span>
                <span class="text-sage font-display">₦{{ number_format($order->total, 0) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
if (typeof fbq !== 'undefined') {
    fbq('track', 'Purchase', {
        value: {{ (float) $order->total }},
        currency: 'NGN',
        content_ids: [{{ $order->items->pluck('product_id')->map(fn($id) => "'$id'")->implode(', ') }}],
        content_type: 'product',
        num_items: {{ $order->items->sum('quantity') }}
    });
}
</script>
@endpush
