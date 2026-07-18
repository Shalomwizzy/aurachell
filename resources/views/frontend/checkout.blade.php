@extends('layouts.app')
@section('title', 'Checkout — Aurachell')
@section('content')

@php
    $user = auth()->user();
    $defaultGateway = count($enabledGateways ?? []) > 0 ? $enabledGateways[0] : 'paystack';
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="checkout-root">

    <div class="mb-10">
        <a href="{{ route('shop') }}" class="flex items-center gap-2 text-xs tracking-widest uppercase text-text-muted hover:text-sage transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Shop
        </a>
        <h1 class="font-display text-3xl mt-4">Checkout</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

        {{-- Steps left column --}}
        <div class="lg:col-span-3">

            {{-- Step indicator --}}
            <div class="flex items-center mb-10">
                {{-- Step 1 --}}
                <div class="flex flex-col items-center gap-1">
                    <div id="step-dot-1" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium font-sans">
                        <span id="step-icon-1">1</span>
                    </div>
                    <span id="step-lbl-1" class="text-[10px] font-sans tracking-wider uppercase">Contact</span>
                </div>
                <div class="flex-1 h-px" id="step-line-1"></div>
                {{-- Step 2 --}}
                <div class="flex flex-col items-center gap-1">
                    <div id="step-dot-2" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium font-sans">
                        <span id="step-icon-2">2</span>
                    </div>
                    <span id="step-lbl-2" class="text-[10px] font-sans tracking-wider uppercase">Shipping</span>
                </div>
                <div class="flex-1 h-px" id="step-line-2"></div>
                {{-- Step 3 --}}
                <div class="flex flex-col items-center gap-1">
                    <div id="step-dot-3" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium font-sans">
                        <span id="step-icon-3">3</span>
                    </div>
                    <span id="step-lbl-3" class="text-[10px] font-sans tracking-wider uppercase">Payment</span>
                </div>
            </div>

            {{-- ═══ Step 1: Contact ═══ --}}
            <div id="ck-step-1">
                <h2 class="font-display text-2xl mb-6">Contact Information</h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Full Name *</label>
                        <input type="text" id="ck-name" class="input-luxury" placeholder="Your full name"
                               value="{{ old('name', $user?->name ?? '') }}">
                        <p id="err-name" class="text-mahogany text-xs mt-1" style="display:none;"></p>
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Email Address *</label>
                        <input type="email" id="ck-email" class="input-luxury" placeholder="your@email.com"
                               value="{{ old('email', $user?->email ?? '') }}">
                        <p id="err-email" class="text-mahogany text-xs mt-1" style="display:none;"></p>
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Phone Number *</label>
                        <input type="tel" id="ck-phone" class="input-luxury" placeholder="+234 800 000 0000"
                               value="{{ old('phone', $user?->phone ?? '') }}">
                        <p id="err-phone" class="text-mahogany text-xs mt-1" style="display:none;"></p>
                    </div>
                    @guest
                    <p class="text-xs text-text-muted font-sans">
                        Already have an account? <a href="{{ route('login') }}" class="text-sage underline">Sign in</a> for a faster checkout.
                    </p>
                    @endguest
                </div>
                <button type="button" onclick="ckNextStep()" class="btn-primary mt-8">Continue to Shipping</button>
            </div>

            {{-- ═══ Step 2: Shipping ═══ --}}
            <div id="ck-step-2" style="display:none;">
                <h2 class="font-display text-2xl mb-6">Shipping Address</h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Address Line 1 *</label>
                        <input type="text" id="ck-addr1" class="input-luxury" placeholder="Street address, apartment, etc."
                               value="{{ old('address_line_1', $defaultAddress?->address_line_1 ?? '') }}">
                        <p id="err-addr1" class="text-mahogany text-xs mt-1" style="display:none;"></p>
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Address Line 2</label>
                        <input type="text" id="ck-addr2" class="input-luxury" placeholder="Estate, landmark (optional)"
                               value="{{ old('address_line_2', $defaultAddress?->address_line_2 ?? '') }}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">City *</label>
                            <input type="text" id="ck-city" class="input-luxury" placeholder="Lagos"
                                   value="{{ old('city', $defaultAddress?->city ?? '') }}">
                            <p id="err-city" class="text-mahogany text-xs mt-1" style="display:none;"></p>
                        </div>
                        <div>
                            <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">State *</label>
                            <input type="text" id="ck-state" class="input-luxury" placeholder="e.g. Lagos"
                                   value="{{ old('state', $defaultAddress?->state ?? '') }}">
                            <p id="err-state" class="text-mahogany text-xs mt-1" style="display:none;"></p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Shipping Method</label>
                        <div id="shipping-options" class="space-y-3 mt-2">
                            <label id="ship-standard-wrap" class="flex items-center gap-3 p-4 border cursor-pointer transition-colors" onclick="ckSelectShipping('standard')">
                                <input type="radio" name="ck_ship_vis" value="standard" checked class="accent-sage flex-shrink-0">
                                <div class="flex-1">
                                    <span class="font-sans text-sm font-medium">Standard Delivery</span>
                                    <p id="ship-standard-note" class="text-xs text-text-muted">{{ $shipping['standard']['delivery'] ?? '3–5 days' }}</p>
                                </div>
                                <span id="ship-standard-price" class="text-sm font-medium text-sage flex-shrink-0">
                                    ₦{{ number_format($shipping['standard']['price'] ?? 0) }}
                                </span>
                            </label>
                            <label id="ship-express-wrap" class="flex items-center gap-3 p-4 border cursor-pointer transition-colors" onclick="ckSelectShipping('express')">
                                <input type="radio" name="ck_ship_vis" value="express" class="accent-sage flex-shrink-0">
                                <div class="flex-1">
                                    <span class="font-sans text-sm font-medium">Express Delivery</span>
                                    <p id="ship-express-note" class="text-xs text-text-muted">{{ $shipping['express']['delivery'] ?? '1–2 days' }}</p>
                                </div>
                                <span id="ship-express-price" class="text-sm font-medium text-sage flex-shrink-0">
                                    ₦{{ number_format($shipping['express']['price'] ?? 0) }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 mt-8">
                    <button type="button" onclick="ckGoToStep(1)" class="btn-secondary">Back</button>
                    <button type="button" onclick="ckNextStep()" class="btn-primary">Continue to Payment</button>
                </div>
            </div>

            {{-- ═══ Step 3: Review & Pay ═══ --}}
            <div id="ck-step-3" style="display:none;">
                <h2 class="font-display text-2xl mb-6">Review & Pay</h2>

                {{-- Review block --}}
                <div class="bg-sand/10 p-5 mb-6 space-y-2 text-sm font-sans">
                    <div><span class="text-text-muted">Name:</span> <span id="rv-name"></span></div>
                    <div><span class="text-text-muted">Email:</span> <span id="rv-email"></span></div>
                    <div><span class="text-text-muted">Phone:</span> <span id="rv-phone"></span></div>
                    <div><span class="text-text-muted">Ship to:</span> <span id="rv-address"></span></div>
                    <div><span class="text-text-muted">Method:</span> <span id="rv-method"></span></div>
                </div>

                {{-- Coupon --}}
                <div class="mb-6">
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Have a Coupon?</label>
                    <div class="flex gap-2">
                        <input type="text" id="ck-coupon" class="input-luxury flex-1" placeholder="Enter code">
                        <button type="button" onclick="ckApplyCoupon()" class="btn-secondary text-xs px-5 flex-shrink-0">Apply</button>
                    </div>
                    <p id="coupon-msg" class="text-xs mt-2" style="display:none;color:var(--color-primary)"></p>
                </div>

                @if(session('error'))
                <div class="mb-6 px-4 py-3 text-sm border" style="background:rgba(55,18,32,0.10);border-color:rgba(201,169,111,0.25);color:#371220;">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 px-4 py-3 text-sm border" style="background:rgba(55,18,32,0.10);border-color:rgba(201,169,111,0.25);color:#371220;">
                    <p class="font-medium mb-1">Please correct the following:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-2 text-xs opacity-80">Click "Back" to update your information.</p>
                </div>
                @endif

                {{-- Payment Method --}}
                <div class="mb-8">
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-4">How would you like to pay?</label>
                    <div class="space-y-3">
                        @foreach($enabledGateways as $gateway)
                        @if($gateway === 'paystack')
                        <label class="flex items-center gap-4 p-4 border cursor-pointer transition-colors"
                               id="pm-paystack" onclick="ckSelectPayment('paystack')">
                            <div id="pm-dot-paystack" class="w-4 h-4 rounded-full border-2 flex-shrink-0"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-text-dark">Pay with Paystack</p>
                                <p class="text-xs text-text-muted mt-0.5">Visa, Mastercard, USSD &amp; more — secured by Paystack</p>
                            </div>
                        </label>
                        @elseif($gateway === 'flutterwave')
                        <label class="flex items-center gap-4 p-4 border cursor-pointer transition-colors"
                               id="pm-flutterwave" onclick="ckSelectPayment('flutterwave')">
                            <div id="pm-dot-flutterwave" class="w-4 h-4 rounded-full border-2 flex-shrink-0"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-text-dark">Pay with Flutterwave</p>
                                <p class="text-xs text-text-muted mt-0.5">Card, Bank Transfer, USSD, Mobile Money via Flutterwave</p>
                            </div>
                        </label>
                        @elseif($gateway === 'bank_transfer')
                        <label class="flex items-center gap-4 p-4 border cursor-pointer transition-colors"
                               id="pm-bank_transfer" onclick="ckSelectPayment('bank_transfer')">
                            <div id="pm-dot-bank_transfer" class="w-4 h-4 rounded-full border-2 flex-shrink-0"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-text-dark">Direct Bank Transfer</p>
                                <p class="text-xs text-text-muted mt-0.5">Transfer to our account and upload your receipt — confirmed within 24h</p>
                            </div>
                            <svg class="w-5 h-5 text-text-muted opacity-50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </label>
                        @endif
                        @endforeach
                    </div>
                </div>

                @if(in_array('bank_transfer', $enabledGateways))
                <div id="bank-details-panel" class="mb-6 p-5 border" style="display:none;background:rgba(201,169,111,0.05);border-color:rgba(201,169,111,0.30);">
                    <p class="text-xs tracking-widest uppercase font-sans mb-4" style="color:rgba(55,18,32,0.55);">Transfer to this account</p>
                    <div class="space-y-3 text-sm font-sans">
                        <div class="flex justify-between items-center border-b pb-3" style="border-color:rgba(55,18,32,0.10);">
                            <span class="text-text-muted">Bank</span>
                            <span class="font-medium text-text-dark">{{ $bankDetails['bank_name'] ?: 'See confirmation email' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b pb-3" style="border-color:rgba(55,18,32,0.10);">
                            <span class="text-text-muted">Account Name</span>
                            <span class="font-medium text-text-dark">{{ $bankDetails['account_name'] ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-text-muted">Account Number</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold tracking-widest text-base" style="color:#371220;">{{ $bankDetails['account_number'] ?: '—' }}</span>
                                @if($bankDetails['account_number'])
                                <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $bankDetails['account_number'] }}').then(function(){this.textContent='Copied!';setTimeout(function(){this.textContent='Copy';}.bind(this),2000);}.bind(this))"
                                        class="text-[10px] tracking-wider uppercase px-2 py-0.5 font-sans transition-colors"
                                        style="background:rgba(55,18,32,0.08);color:rgba(55,18,32,0.55);border:1px solid rgba(55,18,32,0.15);">Copy</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($bankDetails['instructions'])
                    <p class="mt-4 text-xs font-sans leading-relaxed" style="color:rgba(55,18,32,0.55);">{{ $bankDetails['instructions'] }}</p>
                    @endif
                    <p class="mt-4 text-xs font-sans" style="color:rgba(55,18,32,0.55);">After placing your order you'll get a reference number to include in your transfer narration. Then upload your receipt to confirm.</p>
                </div>
                @endif

                <div class="flex gap-4">
                    <button type="button" onclick="ckGoToStep(2)" class="btn-secondary">Back</button>

                    <form action="{{ route('checkout.place') }}" method="POST" id="ck-final-form" onsubmit="ckPopulateForm()">
                        @csrf
                        <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="hidden" name="name"            id="f-name">
                        <input type="hidden" name="email"           id="f-email">
                        <input type="hidden" name="phone"           id="f-phone">
                        <input type="hidden" name="address_line_1"  id="f-addr1">
                        <input type="hidden" name="address_line_2"  id="f-addr2">
                        <input type="hidden" name="city"            id="f-city">
                        <input type="hidden" name="state"           id="f-state">
                        <input type="hidden" name="country"         id="f-country" value="Nigeria">
                        <input type="hidden" name="shipping_method" id="f-shipping">
                        <input type="hidden" name="coupon_code"     id="f-coupon">
                        <input type="hidden" name="payment_method"  id="f-payment">

                        <button type="submit" class="btn-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span id="ck-pay-label">Pay Securely</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- Order Summary sidebar --}}
        <div class="lg:col-span-2">
            <div class="p-6 sticky top-24" style="background:var(--color-bg);border:1px solid rgba(201,169,111,0.15);">
                <h3 class="font-display text-lg mb-5">Order Summary</h3>
                <div class="space-y-3 mb-5">
                    @foreach($items as $item)
                    <div class="flex gap-3 items-center">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 overflow-hidden" style="background:var(--color-base)">
                                @if($item->product->primaryImage)
                                <img src="{{ asset('images/products/' . basename($item->product->primaryImage->image_path)) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 text-cream text-[10px] rounded-full flex items-center justify-center font-bold" style="background:var(--color-primary)">{{ $item->quantity }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-sans text-text-dark truncate">{{ $item->product->name }}</p>
                            @if($item->variant)<p class="text-[10px] text-text-muted">{{ $item->variant->name }}</p>@endif
                        </div>
                        <span class="text-sm font-sans flex-shrink-0" style="color:var(--color-primary)">₦{{ number_format($item->price_at_add * $item->quantity, 0) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="pt-4 space-y-2 text-sm font-sans" style="border-top:1px solid rgba(201,169,111,0.20);">
                    <div class="flex justify-between text-text-muted">
                        <span>Subtotal</span><span>₦{{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div id="summary-discount-row" class="flex justify-between" style="display:none;color:var(--color-primary)">
                        <span>Discount</span><span id="summary-discount-amount"></span>
                    </div>
                    <div class="flex justify-between text-text-muted">
                        <span>Shipping</span>
                        <span id="summary-shipping">
                            ₦{{ number_format($shipping['standard']['price'] ?? 0) }}
                        </span>
                    </div>
                    <div class="flex justify-between font-medium text-text-dark pt-2" style="border-top:1px solid rgba(201,169,111,0.15);">
                        <span class="font-display">Total</span>
                        <span class="font-display" id="summary-total" style="color:var(--color-primary)">
                            ₦{{ number_format($subtotal + ($shipping['standard']['price'] ?? 0)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
(function() {
    var _step           = 1;
    var _shippingMethod = 'standard';
    var _paymentMethod  = @js($defaultGateway ?? 'paystack');
    var _couponValid    = false;
    var _couponDiscount = 0;
    var _subtotal       = {{ $subtotal }};
    var _rates          = @js($shipping);
    var _gateways       = @js($enabledGateways ?? ['paystack']);

    var ACTIVE_BG   = '#371220';
    var ACTIVE_TEXT = '#F7F2EB';
    var MUTED_BG    = 'rgba(201,169,111,0.20)';
    var MUTED_TEXT  = 'var(--color-text-muted)';
    var GOLD        = '#C9A96F';

    function fmt(n) { return '₦' + Math.round(n).toLocaleString('en-NG'); }

    function shippingFee() {
        var r = _rates[_shippingMethod];
        if (!r) return 0;
        return r.price || 0;
    }

    function updateSummary() {
        var fee   = shippingFee();
        var total = Math.max(0, _subtotal - _couponDiscount + fee);

        var shipEl  = document.getElementById('summary-shipping');
        var totalEl = document.getElementById('summary-total');
        var discRow = document.getElementById('summary-discount-row');
        var discAmt = document.getElementById('summary-discount-amount');
        var payLbl  = document.getElementById('ck-pay-label');

        if (shipEl)  shipEl.textContent  = fmt(fee);
        if (totalEl) totalEl.textContent = fmt(total);
        if (discRow) discRow.style.display = _couponDiscount > 0 ? 'flex' : 'none';
        if (discAmt) discAmt.textContent   = '−' + fmt(_couponDiscount);
        if (payLbl)  payLbl.textContent    = _paymentMethod === 'bank_transfer' ? 'Place Order' : 'Pay ' + fmt(total) + ' Securely';
    }

    function updateStepIndicator() {
        [1, 2, 3].forEach(function(n) {
            var dot  = document.getElementById('step-dot-' + n);
            var icon = document.getElementById('step-icon-' + n);
            var lbl  = document.getElementById('step-lbl-' + n);
            var line = n < 3 ? document.getElementById('step-line-' + n) : null;
            if (!dot) return;

            if (n < _step) {
                dot.style.background = ACTIVE_BG;
                dot.style.color      = ACTIVE_TEXT;
                if (icon) icon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>';
                if (lbl)  lbl.style.color = GOLD;
                if (line) line.style.background = GOLD;
            } else if (n === _step) {
                dot.style.background = ACTIVE_BG;
                dot.style.color      = ACTIVE_TEXT;
                if (icon) icon.textContent = n;
                if (lbl)  lbl.style.color  = GOLD;
                if (line) line.style.background = n < _step ? GOLD : MUTED_BG;
            } else {
                dot.style.background = MUTED_BG;
                dot.style.color      = MUTED_TEXT;
                if (icon) icon.textContent = n;
                if (lbl)  lbl.style.color  = MUTED_TEXT;
                if (line) line.style.background = MUTED_BG;
            }
        });
        // Also set line 1 and 2 based on step
        var l1 = document.getElementById('step-line-1');
        var l2 = document.getElementById('step-line-2');
        if (l1) l1.style.background = _step > 1 ? GOLD : MUTED_BG;
        if (l2) l2.style.background = _step > 2 ? GOLD : MUTED_BG;
    }

    function showStep(n) {
        [1, 2, 3].forEach(function(i) {
            var el = document.getElementById('ck-step-' + i);
            if (el) el.style.display = i === n ? 'block' : 'none';
        });
        _step = n;
        updateStepIndicator();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function setErr(id, msg) {
        var el = document.getElementById(id);
        if (!el) return;
        el.textContent    = msg || '';
        el.style.display  = msg ? 'block' : 'none';
    }

    function validateStep1() {
        var name  = (document.getElementById('ck-name')  || {}).value || '';
        var email = (document.getElementById('ck-email') || {}).value || '';
        var phone = (document.getElementById('ck-phone') || {}).value || '';
        name = name.trim(); email = email.trim(); phone = phone.trim();

        setErr('err-name',  !name  ? 'Full name is required' : '');
        setErr('err-email', (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) ? 'A valid email address is required' : '');
        setErr('err-phone', !phone ? 'Phone number is required' : '');

        return name && phone && email && /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email);
    }

    function validateStep2() {
        var addr1 = ((document.getElementById('ck-addr1') || {}).value || '').trim();
        var city  = ((document.getElementById('ck-city')  || {}).value || '').trim();
        var state = ((document.getElementById('ck-state') || {}).value || '').trim();

        setErr('err-addr1', !addr1 ? 'Address is required' : '');
        setErr('err-city',  !city  ? 'City is required' : '');
        setErr('err-state', !state ? 'State is required' : '');

        return addr1 && city && state;
    }

    function populateReview() {
        var name  = ((document.getElementById('ck-name')  || {}).value || '').trim();
        var email = ((document.getElementById('ck-email') || {}).value || '').trim();
        var phone = ((document.getElementById('ck-phone') || {}).value || '').trim();
        var addr1 = ((document.getElementById('ck-addr1') || {}).value || '').trim();
        var addr2 = ((document.getElementById('ck-addr2') || {}).value || '').trim();
        var city  = ((document.getElementById('ck-city')  || {}).value || '').trim();
        var state = ((document.getElementById('ck-state') || {}).value || '').trim();

        var setTxt = function(id, v) { var el = document.getElementById(id); if (el) el.textContent = v; };
        setTxt('rv-name',    name);
        setTxt('rv-email',   email);
        setTxt('rv-phone',   phone);
        setTxt('rv-address', addr1 + (addr2 ? ', ' + addr2 : '') + ', ' + city + ', ' + state);
        setTxt('rv-method',  _shippingMethod === 'express' ? 'Express delivery' : 'Standard delivery');
    }

    // ── Global handlers ──

    window.ckNextStep = function() {
        if (_step === 1 && !validateStep1()) return;
        if (_step === 2 && !validateStep2()) return;
        if (_step === 2) populateReview();
        showStep(_step + 1);
    };

    window.ckGoToStep = function(n) { showStep(n); };

    window.ckSelectShipping = function(method) {
        _shippingMethod = method;
        ['standard', 'express'].forEach(function(m) {
            var wrap = document.getElementById('ship-' + m + '-wrap');
            if (!wrap) return;
            wrap.style.borderColor = m === method ? ACTIVE_BG : 'rgba(201,169,111,0.25)';
        });
        updateSummary();
    };

    window.ckSelectPayment = function(method) {
        _paymentMethod = method;
        _gateways.forEach(function(gw) {
            var wrap = document.getElementById('pm-' + gw);
            var dot  = document.getElementById('pm-dot-' + gw);
            if (!wrap) return;
            var active = gw === method;
            wrap.style.borderColor = active ? ACTIVE_BG : 'rgba(201,169,111,0.25)';
            wrap.style.background  = active ? 'rgba(55,18,32,0.05)' : '';
            if (dot) {
                dot.style.borderColor = active ? ACTIVE_BG : 'rgba(201,169,111,0.35)';
                dot.style.background  = active ? ACTIVE_BG : '';
            }
        });
        var bankPanel = document.getElementById('bank-details-panel');
        if (bankPanel) bankPanel.style.display = method === 'bank_transfer' ? 'block' : 'none';
        updateSummary();
    };

    window.ckApplyCoupon = function() {
        var code  = ((document.getElementById('ck-coupon') || {}).value || '').trim();
        var msgEl = document.getElementById('coupon-msg');
        if (!code) return;

        fetch('/cart/coupon', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ coupon_code: code }),
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (msgEl) { msgEl.textContent = data.message; msgEl.style.display = 'block'; }
            _couponValid    = data.success;
            _couponDiscount = data.success ? (data.discount || 0) : 0;
            updateSummary();
        })
        .catch(function() {
            if (msgEl) { msgEl.textContent = 'Failed to apply coupon. Please try again.'; msgEl.style.display = 'block'; }
        });
    };

    window.ckPopulateForm = function() {
        var g = function(id) { return ((document.getElementById(id) || {}).value || '').trim(); };
        var s = function(id, v) { var el = document.getElementById(id); if (el) el.value = v; };
        s('f-name',     g('ck-name'));
        s('f-email',    g('ck-email'));
        s('f-phone',    g('ck-phone'));
        s('f-addr1',    g('ck-addr1'));
        s('f-addr2',    g('ck-addr2'));
        s('f-city',     g('ck-city'));
        s('f-state',    g('ck-state'));
        s('f-shipping', _shippingMethod);
        s('f-payment',  _paymentMethod);
        s('f-coupon',   _couponValid ? g('ck-coupon') : '');
    };

    window.ckFetchRates = function() {
        var state = ((document.getElementById('ck-state') || {}).value || '').trim();
        if (!state) return;
        var opts = document.getElementById('shipping-options');
        if (opts) { opts.style.opacity = '0.5'; opts.style.pointerEvents = 'none'; }

        fetch('/checkout/shipping-rate?state=' + encodeURIComponent(state) + '&subtotal={{ $subtotal }}')
        .then(function(r) { return r.ok ? r.json() : null; })
        .then(function(data) {
            if (!data) return;
            _rates = data;
            ['standard', 'express'].forEach(function(m) {
                var r = data[m];
                if (!r) return;
                var priceEl = document.getElementById('ship-' + m + '-price');
                var noteEl  = document.getElementById('ship-' + m + '-note');
                if (priceEl) priceEl.textContent = fmt(r.price);
                if (noteEl)  noteEl.textContent  = r.delivery + (r.zone && r.zone !== 'Default' ? ' · ' + r.zone : '');
            });
            updateSummary();
        })
        .catch(function() {})
        .finally(function() {
            if (opts) { opts.style.opacity = '1'; opts.style.pointerEvents = ''; }
        });
    };

    // ── Init ──
    document.addEventListener('DOMContentLoaded', function() {
        var stateInput = document.getElementById('ck-state');
        if (stateInput) {
            stateInput.addEventListener('blur', ckFetchRates);
            stateInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); ckFetchRates(); } });
        }

        // Init step indicator
        updateStepIndicator();

        // Init payment method selection UI
        ckSelectShipping('standard');
        ckSelectPayment(_paymentMethod);
        updateSummary();

        // If server returned validation errors, jump to step 3
        @if($errors->any())
        showStep(3);
        @endif
    });
})();
</script>
@endpush

@endsection
