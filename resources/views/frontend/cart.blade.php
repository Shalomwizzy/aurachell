@extends('layouts.app')
@section('title', 'Your Cart — Aurachell')

@section('content')

@php
    $shippingStandard = $shippingStandard ?? 0;
@endphp

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
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-7" style="background:rgba(201,169,111,0.12)">
            <svg class="w-10 h-10 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <h2 class="font-display text-3xl text-text-dark mb-3">Your cart is empty</h2>
        <p class="text-text-muted font-sans text-sm mb-10 max-w-xs mx-auto leading-relaxed">
            Discover our collection of luxury home diffusers, crafted for calm and elegant living.
        </p>
        <a href="{{ route('shop') }}" class="btn-primary">Shop the Collection</a>
    </div>
    @else

    <div id="cart-page-root" class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

        {{-- ═══ Cart Items ═══ --}}
        <div class="lg:col-span-7 space-y-0" id="cart-items-list">

            <div class="hidden sm:grid grid-cols-12 gap-4 pb-3 border-b border-sand/50 text-[10px] text-text-muted font-sans tracking-[0.2em] uppercase mb-2">
                <div class="col-span-6">Product</div>
                <div class="col-span-3 text-center">Quantity</div>
                <div class="col-span-3 text-right">Total</div>
            </div>

            @foreach($items as $item)
            @php
                $img = $item->product->primaryImage
                    ? basename($item->product->primaryImage->image_path)
                    : null;
                $lineTotal = $item->price_at_add * $item->quantity;
            @endphp
            <div class="grid grid-cols-12 gap-4 py-7 border-b border-sand/20 items-center cart-item-row" data-item-id="{{ $item->id }}">

                {{-- Product info --}}
                <div class="col-span-12 sm:col-span-6 flex gap-4">
                    <a href="{{ route('product.show', $item->product->slug) }}" class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 overflow-hidden block" style="background:var(--color-base)">
                        @if($img)
                        <img src="{{ asset('images/products/' . $img) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(to br,rgba(201,169,111,0.15),rgba(201,169,111,0.05))">
                            <svg class="w-8 h-8 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        @endif
                    </a>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-sage uppercase tracking-widest font-sans mb-1">{{ $item->product->category?->name }}</p>
                        <h3 class="font-display text-base sm:text-lg text-text-dark leading-tight mb-0.5 line-clamp-2">
                            <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-sage transition-colors">{{ $item->product->name }}</a>
                        </h3>
                        @if($item->variant)
                        <p class="text-xs text-text-muted font-sans mt-1">{{ $item->variant->name }}</p>
                        @endif
                        @if($item->scent_note)
                        <p class="text-xs text-text-muted font-sans mt-0.5">Scent: {{ $item->scent_note }}</p>
                        @endif
                        <p class="font-sans text-sm mt-1" style="color:var(--color-primary)">₦{{ number_format($item->price_at_add) }}</p>
                        {{-- Mobile remove --}}
                        <button type="button"
                                onclick="cartRemove({{ $item->id }}, this)"
                                class="sm:hidden flex items-center gap-1 text-xs text-text-muted hover:text-mahogany transition-colors mt-2 font-sans">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Remove
                        </button>
                    </div>
                </div>

                {{-- Qty stepper --}}
                <div class="col-span-7 sm:col-span-3 flex items-center sm:justify-center gap-3">
                    <div class="flex items-center border border-sand/60 bg-white">
                        <button type="button"
                                onclick="cartUpdateQty({{ $item->id }}, {{ $item->quantity - 1 }}, this)"
                                class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-text-dark hover:bg-sand/10 transition-colors">−</button>
                        <span class="w-9 text-center text-sm font-sans text-text-dark border-x border-sand/50 cart-qty-display" data-item-id="{{ $item->id }}">{{ $item->quantity }}</span>
                        <button type="button"
                                onclick="cartUpdateQty({{ $item->id }}, {{ $item->quantity + 1 }}, this)"
                                class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-text-dark hover:bg-sand/10 transition-colors">+</button>
                    </div>
                </div>

                {{-- Line total + desktop remove --}}
                <div class="col-span-5 sm:col-span-3 flex items-center justify-end gap-4">
                    <span class="font-display text-lg cart-line-total" style="color:var(--color-primary)" data-item-id="{{ $item->id }}" data-unit="{{ $item->price_at_add }}">
                        ₦{{ number_format($lineTotal) }}
                    </span>
                    <button type="button"
                            onclick="cartRemove({{ $item->id }}, this)"
                            class="hidden sm:flex w-7 h-7 items-center justify-center text-text-muted hover:text-mahogany hover:bg-mahogany/5 transition-colors rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

            </div>
            @endforeach

            <div class="pt-6">
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 text-xs text-text-muted hover:text-sage transition-colors font-sans tracking-widest uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Continue Shopping
                </a>
            </div>
        </div>

        {{-- ═══ Order Summary ═══ --}}
        <div class="lg:col-span-5">
            <div class="p-7 lg:p-8 sticky top-24" style="background:rgba(201,169,111,0.07)">
                <h2 class="font-display text-2xl text-text-dark mb-7">Order Summary</h2>

                {{-- Coupon --}}
                <div class="mb-7">
                    <label class="block text-[10px] tracking-[0.2em] uppercase text-text-muted mb-3">Promo Code</label>
                    <div class="flex gap-0">
                        <input type="text" id="cart-coupon-input"
                               placeholder="Enter code"
                               class="flex-1 border-b border-sand/60 bg-transparent py-2.5 text-sm focus:outline-none focus:border-sage transition-colors uppercase tracking-widest text-text-dark min-w-0"
                               onkeydown="if(event.key==='Enter'){event.preventDefault();cartApplyCoupon();}">
                        <button type="button" onclick="cartApplyCoupon()"
                                class="px-5 py-2.5 text-[10px] tracking-[0.15em] uppercase font-medium transition-colors flex-shrink-0"
                                style="background:#371220;color:#FFFFFF;">Apply</button>
                    </div>
                    <p id="cart-coupon-msg" class="text-xs mt-2 font-sans" style="display:none;color:var(--color-primary)"></p>
                </div>

                {{-- Totals --}}
                <div class="space-y-3 text-sm font-sans border-t border-sand/40 pt-6">
                    <div class="flex justify-between text-text-muted">
                        <span>Subtotal</span>
                        <span id="cart-subtotal-display">₦{{ number_format($subtotal) }}</span>
                    </div>
                    <div id="cart-discount-row" class="flex justify-between" style="display:none;color:var(--color-primary)">
                        <span>Promo discount</span>
                        <span id="cart-discount-amount"></span>
                    </div>
                    <div class="flex justify-between text-text-muted">
                        <span>Shipping</span>
                        <span id="cart-shipping-display">₦{{ number_format($shippingStandard) }}</span>
                    </div>
                    <p class="text-[10px] text-text-muted px-3 py-2 font-sans" style="background:rgba(201,169,111,0.12)">
                        Estimated shipping — your exact rate is calculated by city at checkout.
                    </p>
                    <div class="flex justify-between items-baseline pt-4 border-t border-sand/40">
                        <span class="font-display text-lg text-text-dark">Total</span>
                        <span class="font-display text-2xl" id="cart-total-display" style="color:var(--color-primary)">
                            ₦{{ number_format(max(0, $subtotal + $shippingStandard)) }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('checkout') }}" class="btn-primary w-full text-center mt-7 flex items-center justify-center py-4">
                    Proceed to Checkout
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
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
    @endif

</div>

@push('scripts')
<script>
(function() {
    var _csrf        = function() { return document.querySelector('meta[name=csrf-token]').content; };
    var _subtotal    = {{ $subtotal }};
    var _discount    = 0;
    var _stdShipping = {{ $shippingStandard }};
    var _loading     = false;

    function fmt(n) { return '₦' + Math.round(n).toLocaleString('en-NG'); }

    function updateSummaryDisplay() {
        var fee = _stdShipping;
        var total = Math.max(0, _subtotal - _discount + fee);

        var subEl   = document.getElementById('cart-subtotal-display');
        var shipEl  = document.getElementById('cart-shipping-display');
        var totalEl = document.getElementById('cart-total-display');
        var discRow = document.getElementById('cart-discount-row');
        var discAmt = document.getElementById('cart-discount-amount');

        if (subEl)   subEl.textContent   = fmt(_subtotal);
        if (shipEl)  shipEl.textContent  = fmt(fee);
        if (totalEl) totalEl.textContent = fmt(total);
        if (discRow) discRow.style.display = _discount > 0 ? 'flex' : 'none';
        if (discAmt) discAmt.textContent = '−' + fmt(_discount);
    }

    function setLoading(on) {
        _loading = on;
        var root = document.getElementById('cart-page-root');
        if (root) { root.style.opacity = on ? '0.6' : '1'; root.style.pointerEvents = on ? 'none' : ''; }
    }

    window.cartUpdateQty = function(itemId, newQty, btn) {
        if (_loading) return;
        if (newQty < 1) { cartRemove(itemId, btn); return; }
        setLoading(true);

        fetch('/cart/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': _csrf(), 'Accept': 'application/json' },
            body: JSON.stringify({ item_id: itemId, quantity: newQty }),
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.items || data.count === 0) { window.location.reload(); return; }
            // Update qty display
            var qtyEl = document.querySelector('.cart-qty-display[data-item-id="' + itemId + '"]');
            if (qtyEl) qtyEl.textContent = newQty;
            // Update the +/- button onclick values
            var row = document.querySelector('.cart-item-row[data-item-id="' + itemId + '"]');
            if (row) {
                var btns = row.querySelectorAll('button[onclick*="cartUpdateQty"]');
                btns[0] && (btns[0].setAttribute('onclick', 'cartUpdateQty(' + itemId + ',' + (newQty-1) + ',this)'));
                btns[1] && (btns[1].setAttribute('onclick', 'cartUpdateQty(' + itemId + ',' + (newQty+1) + ',this)'));
            }
            // Update line total
            var lineEl = document.querySelector('.cart-line-total[data-item-id="' + itemId + '"]');
            if (lineEl) {
                var unit = parseFloat(lineEl.dataset.unit || 0);
                lineEl.textContent = fmt(unit * newQty);
            }
            // Update subtotal
            _subtotal = data.subtotal || 0;
            updateSummaryDisplay();
            // Update cart badge
            if (window.updateCartBadge) window.updateCartBadge(data.count);
        })
        .catch(function() { window.showToast && window.showToast('Could not update cart.', 'error'); })
        .finally(function() { setLoading(false); });
    };

    window.cartRemove = function(itemId, btn) {
        if (_loading) return;
        setLoading(true);

        fetch('/cart/remove', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': _csrf(), 'Accept': 'application/json' },
            body: JSON.stringify({ item_id: itemId }),
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.count === 0) { window.location.reload(); return; }
            // Remove the row from DOM
            var row = document.querySelector('.cart-item-row[data-item-id="' + itemId + '"]');
            if (row) row.remove();
            _subtotal = data.subtotal || 0;
            updateSummaryDisplay();
            if (window.updateCartBadge) window.updateCartBadge(data.count);
        })
        .catch(function() { window.showToast && window.showToast('Could not remove item.', 'error'); })
        .finally(function() { setLoading(false); });
    };

    window.cartApplyCoupon = function() {
        var code  = ((document.getElementById('cart-coupon-input') || {}).value || '').trim();
        var msgEl = document.getElementById('cart-coupon-msg');
        if (!code) return;

        fetch('/cart/coupon', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': _csrf(), 'Accept': 'application/json' },
            body: JSON.stringify({ coupon_code: code }),
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (msgEl) { msgEl.textContent = data.message; msgEl.style.display = 'block'; }
            _discount = data.success ? (data.discount || 0) : 0;
            updateSummaryDisplay();
        })
        .catch(function() {
            if (msgEl) { msgEl.textContent = 'Failed to apply coupon.'; msgEl.style.display = 'block'; }
        });
    };
})();
</script>
@endpush

@endsection
