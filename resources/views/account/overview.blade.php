@extends('layouts.account')
@section('title', 'My Account')
@section('account-content')
<h1 class="font-display text-2xl text-text-dark mb-6">Welcome back, {{ explode(' ', $user->name)[0] }}.</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    @foreach([
        ['label' => 'Total Orders', 'value' => $user->orders()->count(), 'link' => route('account.orders')],
        ['label' => 'Wishlist Items', 'value' => $wishlistCount, 'link' => route('account.wishlist')],
        ['label' => 'Saved Addresses', 'value' => $user->addresses()->count(), 'link' => route('account.addresses')],
    ] as $stat)
    <a href="{{ $stat['link'] }}" class="bg-white p-5 shadow-luxury hover:shadow-luxury-lg transition-shadow block">
        <p class="text-xs font-sans tracking-widest uppercase text-text-muted mb-2">{{ $stat['label'] }}</p>
        <p class="font-display text-3xl text-sage">{{ $stat['value'] }}</p>
    </a>
    @endforeach
</div>

<div class="bg-white shadow-luxury p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-display text-xl">Recent Orders</h2>
        <a href="{{ route('account.orders') }}" class="text-xs text-sage hover:underline tracking-wider uppercase">View all</a>
    </div>
    @forelse($recentOrders as $order)
    <div class="py-4 border-b border-sand/30 last:border-0 flex items-center justify-between gap-4 flex-wrap">
        <div>
            <a href="{{ route('account.order.detail', $order->order_number) }}" class="font-sans font-medium text-text-dark hover:text-sage transition-colors">{{ $order->order_number }}</a>
            <p class="text-xs text-text-muted">{{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sage font-medium font-sans">₦{{ number_format($order->total, 0) }}</span>
            <span class="badge {{ match($order->status) {
                'delivered' => 'bg-caramel/15 text-bronze',
                'shipped','out_for_delivery' => 'bg-sand/20 text-text-muted',
                default => 'bg-sand/30 text-sage'
            } }} text-[10px]">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
        </div>
    </div>
    @empty
    <p class="text-text-muted text-sm py-4">You haven't placed any orders yet. <a href="{{ route('shop') }}" class="text-sage underline">Browse our collection</a>.</p>
    @endforelse
</div>

{{-- Referral card --}}
<div class="bg-white shadow-luxury p-6 mt-6">
    <h2 class="font-display text-xl mb-1">Refer a Friend</h2>
    <p class="text-sm text-text-muted max-w-md mb-5">Give friends your code at signup. When they make their first qualifying purchase, you earn a discount — automatically sent to your email.</p>

    <div class="flex items-center gap-3" x-data="{ copied: false }">
        <div class="flex-1 bg-surface border border-sand/40 px-5 py-3 flex items-center justify-center">
            <span class="font-mono tracking-[0.25em] text-xl font-semibold text-mahogany">{{ auth()->user()->referral_code }}</span>
        </div>
        <button @click="navigator.clipboard.writeText('{{ auth()->user()->referral_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                class="px-5 py-3 text-xs tracking-widest uppercase font-medium transition-colors flex-shrink-0"
                :class="copied ? 'bg-mahogany text-white' : 'bg-mahogany text-sand hover:bg-mahogany/90'">
            <span x-show="!copied">Copy</span>
            <span x-show="copied">Copied!</span>
        </button>
    </div>

    <p class="text-[11px] text-text-muted mt-3">
        {{ auth()->user()->referrals()->count() }} {{ Str::plural('referral', auth()->user()->referrals()->count()) }} so far
    </p>
</div>
@endsection
