@extends('layouts.app')
@section('title', 'Your Cart — Aurachell')

@section('content')

<div class="bg-white border-b border-sand/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="flex items-center gap-2 text-[10px] text-text-muted mb-3 font-sans tracking-[0.2em] uppercase">
            <a href="{{ route('home') }}" class="hover:text-sage transition-colors">Home</a>
            <span class="text-sand">—</span>
            <span class="text-text-dark">Cart</span>
        </nav>
        <h1 class="font-display text-4xl text-text-dark tracking-tight">Your Cart</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if($items->isEmpty())
    <div class="text-center py-24">
        <div class="w-20 h-20 rounded-full bg-sand/20 flex items-center justify-center mx-auto mb-7">
            <svg class="w-10 h-10 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <h2 class="font-display text-3xl text-text-dark mb-3">Your cart is empty</h2>
        <p class="text-text-muted font-sans text-sm mb-10 max-w-xs mx-auto leading-relaxed">
            Discover our collection of luxury home diffusers, crafted for calm and elegant living.
        </p>
        <a href="{{ route('shop') }}" class="btn-primary">Shop the Collection</a>
    </div>
    @else

    <div
        x-cloak
        x-data="{
            items: @json($itemsData),
            subtotal: {{ $subtotal }},
            discount: 0,
            couponCode: '',
            couponMessage: '',
            couponValid: false,
            loading: false,

            get freeThreshold() { return {{ $freeThreshold }}; },
            get shippingFee() { return this.subtotal >= this.freeThreshold ? 0 : {{ $shippingStandard }}; },
            get total() { return Math.max(0, this.subtotal - this.discount + this.shippingFee); },
            get itemCount() { return this.items.reduce((s, i) => s + i.quantity, 0); },

            fmt(n) { return '₦' + Math.round(n).toLocaleString('en-NG'); },

            csrf() { return document.querySelector('meta[name=csrf-token]').content; },

            async updateQty(itemId, qty) {
                if (this.loading) return;
                this.loading = true;
                try {
                    const res = await fetch('/cart/update', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                        body: JSON.stringify({ item_id: itemId, quantity: qty }),
                    });
                    const data = await res.json();
                    this.items = data.items;
                    this.subtotal = data.subtotal;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                    if (data.count === 0) window.location.reload();
                } catch(e) {}
                this.loading = false;
            },

            async removeItem(itemId) {
                if (this.loading) return;
                this.loading = true;
                try {
                    const res = await fetch('/cart/remove', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                        body: JSON.stringify({ item_id: itemId }),
                    });
                    const data = await res.json();
                    this.items = data.items;
                    this.subtotal = data.subtotal;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                    if (data.count === 0) window.location.reload();
                } catch(e) {}
                this.loading = false;
            },

            async applyCoupon() {
                if (!this.couponCode.trim()) return;
                try {
                    const res = await fetch('/cart/coupon', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                        body: JSON.stringify({ coupon_code: this.couponCode }),
                    });
                    const data = await res.json();
                    this.couponMessage = data.message;
                    this.couponValid = data.success;
                    this.discount = data.success ? data.discount : 0;
                } catch(e) {
                    this.couponMessage = 'Failed to apply coupon. Please try again.';
                    this.couponValid = false;
                }
            }
        }"
        :class="loading ? 'opacity-60 pointer-events-none' : ''"
    >

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

            {{-- Cart Items --}}
            <div class="lg:col-span-7 space-y-0">
                <div class="hidden sm:grid grid-cols-12 gap-4 pb-3 border-b border-sand/50 text-[10px] text-text-muted font-sans tracking-[0.2em] uppercase mb-2">
                    <div class="col-span-6">Product</div>
                    <div class="col-span-3 text-center">Quantity</div>
                    <div class="col-span-3 text-right">Total</div>
                </div>

                <template x-for="item in items" :key="item.id">
                    <div class="grid grid-cols-12 gap-4 py-7 border-b border-sand/20 items-center">
                        <div class="col-span-12 sm:col-span-6 flex gap-4">
                            <a :href="'/products/' + item.product.slug" class="w-20 h-20 sm:w-24 sm:h-24 bg-sand/20 flex-shrink-0 overflow-hidden block">
                                <template x-if="item.product.image">
                                    <img :src="'/images/products/' + item.product.image" :alt="item.product.name" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                </template>
                                <template x-if="!item.product.image">
                                    <div class="w-full h-full bg-gradient-to-br from-sand/30 to-sand/10 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                </template>
                            </a>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] text-sage uppercase tracking-widest font-sans mb-1" x-text="item.product.category"></p>
                                <h3 class="font-display text-base sm:text-lg text-text-dark leading-tight mb-0.5 line-clamp-2">
                                    <a :href="'/products/' + item.product.slug" class="hover:text-sage transition-colors" x-text="item.product.name"></a>
                                </h3>
                                <p class="text-xs text-text-muted font-sans mt-1" x-text="item.variant ? item.variant.name : ''" x-show="item.variant"></p>
                                <p class="font-sans text-sm text-sage mt-1" x-text="fmt(item.price_at_add)"></p>
                                <button @click="removeItem(item.id)"
                                        class="sm:hidden flex items-center gap-1 text-xs text-text-muted hover:text-red-500 transition-colors mt-2 font-sans">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Remove
                                </button>
                            </div>
                        </div>

                        <div class="col-span-7 sm:col-span-3 flex items-center sm:justify-center gap-3">
                            <div class="flex items-center border border-sand/60 bg-white">
                                <button @click="updateQty(item.id, item.quantity - 1)"
                                        class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-text-dark hover:bg-sand/10 transition-colors">−</button>
                                <span class="w-9 text-center text-sm font-sans text-text-dark border-x border-sand/50" x-text="item.quantity"></span>
                                <button @click="updateQty(item.id, item.quantity + 1)"
                                        class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-text-dark hover:bg-sand/10 transition-colors">+</button>
                            </div>
                        </div>

                        <div class="col-span-5 sm:col-span-3 flex items-center justify-end gap-4">
                            <span class="font-display text-lg text-sage" x-text="fmt(item.price_at_add * item.quantity)"></span>
                            <button @click="removeItem(item.id)"
                                    class="hidden sm:flex w-7 h-7 items-center justify-center text-text-muted hover:text-red-500 hover:bg-red-50 transition-colors rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </template>

                <div class="pt-6">
                    <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 text-xs text-text-muted hover:text-sage transition-colors font-sans tracking-widest uppercase">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Continue Shopping
                    </a>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-5">
                <div class="bg-sand/10 p-7 lg:p-8 sticky top-24">
                    <h2 class="font-display text-2xl text-text-dark mb-7">Order Summary</h2>

                    {{-- Coupon --}}
                    <div class="mb-7">
                        <label class="block text-[10px] tracking-[0.2em] uppercase text-text-muted mb-3">Promo Code</label>
                        <div class="flex gap-0">
                            <input type="text"
                                   x-model="couponCode"
                                   @keydown.enter.prevent="applyCoupon()"
                                   placeholder="Enter code"
                                   class="flex-1 border-b border-sand/60 bg-transparent py-2.5 text-sm focus:outline-none focus:border-sage transition-colors uppercase tracking-widest text-text-dark placeholder-text-muted/50 min-w-0">
                            <button @click="applyCoupon()"
                                    class="px-5 py-2.5 text-[10px] tracking-[0.15em] uppercase font-medium transition-colors flex-shrink-0"
                                    style="background:#371220;color:#FAF5ED;">
                                Apply
                            </button>
                        </div>
                        <p class="text-xs mt-2 font-sans"
                           x-show="couponMessage"
                           :class="couponValid ? 'text-green-600' : 'text-red-500'"
                           x-text="couponMessage"></p>
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-3 text-sm font-sans border-t border-sand/40 pt-6">
                        <div class="flex justify-between text-text-muted">
                            <span>Subtotal</span>
                            <span x-text="fmt(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-green-600" x-show="discount > 0">
                            <span>Promo discount</span>
                            <span x-text="'−' + fmt(discount)"></span>
                        </div>
                        <div class="flex justify-between text-text-muted">
                            <span>Shipping</span>
                            <span :class="shippingFee === 0 ? 'text-green-600 font-medium' : ''"
                                  x-text="shippingFee > 0 ? fmt(shippingFee) : 'Free'"></span>
                        </div>
                        <p class="text-[10px] text-text-muted bg-sand/30 px-3 py-2 font-sans"
                           x-show="subtotal < freeThreshold && subtotal > 0"
                           x-text="'Add ' + fmt(freeThreshold - subtotal) + ' more for free shipping'"></p>
                        <div class="flex justify-between items-baseline pt-4 border-t border-sand/40">
                            <span class="font-display text-lg text-text-dark">Total</span>
                            <span class="font-display text-2xl text-sage" x-text="fmt(total)"></span>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" class="btn-primary w-full text-center mt-7 flex items-center justify-center py-4">
                        Proceed to Checkout
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>

                    <div class="mt-6 pt-6 border-t border-sand/30 flex items-center justify-center gap-6">
                        <div class="flex items-center gap-1.5 text-text-muted">
                            <svg class="w-3.5 h-3.5 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span class="text-[10px] uppercase tracking-widest font-sans">Secure Checkout</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-text-muted">
                            <svg class="w-3.5 h-3.5 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="text-[10px] uppercase tracking-widest font-sans">Safe Payments</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @endif

</div>
@endsection
