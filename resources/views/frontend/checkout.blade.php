@extends('layouts.app')
@section('title', 'Checkout — Aurachell')
@section('content')

@php
    $user = auth()->user();
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
     x-data="{
        step: 1,
        name: @js(old('name', $user?->name ?? '')),
        email: @js(old('email', $user?->email ?? '')),
        phone: @js(old('phone', $user?->phone ?? '')),
        address1: @js(old('address_line_1', $defaultAddress?->address_line_1 ?? '')),
        address2: @js(old('address_line_2', $defaultAddress?->address_line_2 ?? '')),
        city: @js(old('city', $defaultAddress?->city ?? '')),
        state: @js(old('state', $defaultAddress?->state ?? '')),
        country: 'Nigeria',
        shippingMethod: 'standard',
        paymentMethod: @js(count($enabledGateways ?? []) > 0 ? $enabledGateways[0] : 'paystack'),
        enabledGateways: @js($enabledGateways ?? ['paystack']),
        couponCode: '',
        couponMessage: '',
        couponValid: false,
        couponDiscount: 0,
        errors: {},
        rates: @js($shipping),
        ratesLoading: false,

        get shippingFee() {
            const r = this.rates[this.shippingMethod];
            return r ? r.price : 0;
        },
        get total() {
            return Math.max(0, {{ $subtotal }} - this.couponDiscount + this.shippingFee);
        },
        fmt(n) { return '₦' + Math.round(n).toLocaleString('en-NG'); },
        csrf() { return document.querySelector('meta[name=csrf-token]').content; },

        async fetchRates() {
            const s = this.state.trim();
            if (!s) return;
            this.ratesLoading = true;
            try {
                const res = await fetch(`/checkout/shipping-rate?state=${encodeURIComponent(s)}&subtotal={{ $subtotal }}`);
                if (res.ok) this.rates = await res.json();
            } catch(e) {}
            this.ratesLoading = false;
        },

        validateStep1() {
            this.errors = {};
            if (!this.name.trim()) this.errors.name = 'Full name is required';
            if (!this.email.trim() || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(this.email.trim()))
                this.errors.email = 'A valid email address is required';
            if (!this.phone.trim()) this.errors.phone = 'Phone number is required';
            return Object.keys(this.errors).length === 0;
        },
        validateStep2() {
            this.errors = {};
            if (!this.address1.trim()) this.errors.address1 = 'Address is required';
            if (!this.city.trim()) this.errors.city = 'City is required';
            if (!this.state.trim()) this.errors.state = 'State is required';
            return Object.keys(this.errors).length === 0;
        },
        nextStep() {
            if (this.step === 1 && !this.validateStep1()) return;
            if (this.step === 2 && !this.validateStep2()) return;
            this.step++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
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
                this.couponDiscount = data.success ? data.discount : 0;
            } catch(e) {
                this.couponMessage = 'Failed to apply coupon. Please try again.';
                this.couponValid = false;
            }
        }
     }">

    <div class="mb-10">
        <a href="{{ route('shop') }}" class="flex items-center gap-2 text-xs tracking-widest uppercase text-text-muted hover:text-sage transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Shop
        </a>
        <h1 class="font-display text-3xl mt-4">Checkout</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

        {{-- Steps --}}
        <div class="lg:col-span-3">

            {{-- Step indicator --}}
            <div class="flex items-center gap-0 mb-10">
                @foreach(['Contact', 'Shipping', 'Payment'] as $i => $label)
                <div class="flex items-center {{ $i > 0 ? 'flex-1' : '' }}">
                    @if($i > 0)
                    <div class="flex-1 h-px transition-colors" :class="{{ $i }} < step ? 'bg-sage' : 'bg-sand'"></div>
                    @endif
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium font-sans transition-colors"
                             :class="{{ $i + 1 }} < step ? 'bg-sage text-cream' : (step === {{ $i + 1 }} ? 'bg-sage text-cream' : 'bg-sand/30 text-text-muted')">
                            <template x-if="{{ $i + 1 }} < step">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="{{ $i + 1 }} >= step">
                                <span>{{ $i + 1 }}</span>
                            </template>
                        </div>
                        <span class="text-[10px] font-sans tracking-wider uppercase transition-colors"
                              :class="step === {{ $i + 1 }} ? 'text-sage' : 'text-text-muted'">{{ $label }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Step 1: Contact --}}
            <div x-show="step === 1">
                <h2 class="font-display text-2xl mb-6">Contact Information</h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Full Name *</label>
                        <input type="text" x-model="name" class="input-luxury" placeholder="Your full name">
                        <p class="text-mahogany text-xs mt-1" x-show="errors.name" x-text="errors.name"></p>
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Email Address *</label>
                        <input type="email" x-model="email" class="input-luxury" placeholder="your@email.com">
                        <p class="text-mahogany text-xs mt-1" x-show="errors.email" x-text="errors.email"></p>
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Phone Number *</label>
                        <input type="tel" x-model="phone" class="input-luxury" placeholder="+234 800 000 0000">
                        <p class="text-mahogany text-xs mt-1" x-show="errors.phone" x-text="errors.phone"></p>
                    </div>
                    @guest
                    <p class="text-xs text-text-muted font-sans">
                        Already have an account? <a href="{{ route('login') }}" class="text-sage underline">Sign in</a> for a faster checkout.
                    </p>
                    @endguest
                </div>
                <button @click="nextStep()" class="btn-primary mt-8">Continue to Shipping</button>
            </div>

            {{-- Step 2: Shipping --}}
            <div x-show="step === 2" style="display:none;">
                <h2 class="font-display text-2xl mb-6">Shipping Address</h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Address Line 1 *</label>
                        <input type="text" x-model="address1" class="input-luxury" placeholder="Street address, apartment, etc.">
                        <p class="text-mahogany text-xs mt-1" x-show="errors.address1" x-text="errors.address1"></p>
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Address Line 2</label>
                        <input type="text" x-model="address2" class="input-luxury" placeholder="Estate, landmark (optional)">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">City *</label>
                            <input type="text" x-model="city" class="input-luxury" placeholder="Lagos">
                            <p class="text-mahogany text-xs mt-1" x-show="errors.city" x-text="errors.city"></p>
                        </div>
                        <div>
                            <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">State *</label>
                            <input type="text" x-model="state"
                                   @blur="fetchRates()"
                                   @keydown.enter.prevent="fetchRates()"
                                   class="input-luxury" placeholder="e.g. Lagos">
                            <p class="text-mahogany text-xs mt-1" x-show="errors.state" x-text="errors.state"></p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Shipping Method</label>
                        <div class="space-y-3 mt-2" :class="ratesLoading ? 'opacity-60 pointer-events-none' : ''">
                            <label class="flex items-center gap-3 p-4 border cursor-pointer transition-colors"
                                   :class="shippingMethod === 'standard' ? 'border-sage' : 'border-sand'">
                                <input type="radio" x-model="shippingMethod" value="standard" class="accent-sage">
                                <div class="flex-1">
                                    <span class="font-sans text-sm font-medium">Standard Delivery</span>
                                    <p class="text-xs text-text-muted" x-text="rates.standard ? rates.standard.delivery + (rates.standard.zone !== 'Default' ? ' · ' + rates.standard.zone : '') : '3–5 days'"></p>
                                </div>
                                <span class="text-sm font-medium text-sage" x-text="rates.standard?.free ? 'Free' : fmt(rates.standard?.price ?? 0)"></span>
                            </label>
                            <label class="flex items-center gap-3 p-4 border cursor-pointer transition-colors"
                                   :class="shippingMethod === 'express' ? 'border-sage' : 'border-sand'">
                                <input type="radio" x-model="shippingMethod" value="express" class="accent-sage">
                                <div class="flex-1">
                                    <span class="font-sans text-sm font-medium">Express Delivery</span>
                                    <p class="text-xs text-text-muted" x-text="rates.express ? rates.express.delivery + (rates.express.zone !== 'Default' ? ' · ' + rates.express.zone : '') : '1–2 days'"></p>
                                </div>
                                <span class="text-sm font-medium text-sage" x-text="rates.express?.free ? 'Free' : fmt(rates.express?.price ?? 0)"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 mt-8">
                    <button @click="step = 1" class="btn-secondary">Back</button>
                    <button @click="nextStep()" class="btn-primary">Continue to Payment</button>
                </div>
            </div>

            {{-- Step 3: Review & Pay --}}
            <div x-show="step === 3" style="display:none;">
                <h2 class="font-display text-2xl mb-6">Review & Pay</h2>
                <div class="bg-sand/10 p-5 mb-6 space-y-2 text-sm font-sans">
                    <div><span class="text-text-muted">Name:</span> <span x-text="name"></span></div>
                    <div><span class="text-text-muted">Email:</span> <span x-text="email"></span></div>
                    <div><span class="text-text-muted">Phone:</span> <span x-text="phone"></span></div>
                    <div><span class="text-text-muted">Ship to:</span>
                        <span x-text="address1 + (address2 ? ', ' + address2 : '') + ', ' + city + ', ' + state"></span>
                    </div>
                    <div><span class="text-text-muted">Method:</span>
                        <span x-text="shippingMethod === 'express' ? 'Express delivery' : 'Standard delivery'"></span>
                    </div>
                </div>

                {{-- Coupon --}}
                <div class="mb-6">
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Have a Coupon?</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="couponCode" @keydown.enter.prevent="applyCoupon()" placeholder="Enter code" class="input-luxury flex-1">
                        <button type="button" @click="applyCoupon()" class="btn-secondary text-xs px-5 flex-shrink-0">Apply</button>
                    </div>
                    <p class="text-xs mt-2"
                       x-show="couponMessage"
                       :class="couponValid ? 'text-mahogany' : 'text-mahogany'"
                       x-text="couponMessage"></p>
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

                {{-- Payment Method Selection --}}
                @if(count($enabledGateways ?? []) > 1)
                <div class="mb-8">
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-4">Payment Method</label>
                    <div class="space-y-3">
                        @if(in_array('paystack', $enabledGateways))
                        <label class="flex items-center gap-4 p-4 border cursor-pointer transition-colors"
                               :class="paymentMethod === 'paystack' ? 'border-mahogany bg-mahogany/5' : 'border-sand/40 hover:border-sand'"
                               @click="paymentMethod = 'paystack'">
                            <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 transition-colors"
                                 :class="paymentMethod === 'paystack' ? 'border-mahogany bg-mahogany' : 'border-sand'"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-text-dark">Pay with Card / Bank</p>
                                <p class="text-xs text-text-muted mt-0.5">Secured by Paystack — Visa, Mastercard, USSD, Bank Transfer</p>
                            </div>
                            <svg class="w-8 h-5 opacity-60" viewBox="0 0 120 40" fill="none"><rect width="120" height="40" rx="4" fill="#00C3F7" fill-opacity=".15"/><text x="10" y="27" font-family="sans-serif" font-size="14" font-weight="700" fill="#00B0EB">Paystack</text></svg>
                        </label>
                        @endif

                        @if(in_array('flutterwave', $enabledGateways))
                        <label class="flex items-center gap-4 p-4 border cursor-pointer transition-colors"
                               :class="paymentMethod === 'flutterwave' ? 'border-mahogany bg-mahogany/5' : 'border-sand/40 hover:border-sand'"
                               @click="paymentMethod = 'flutterwave'">
                            <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 transition-colors"
                                 :class="paymentMethod === 'flutterwave' ? 'border-mahogany bg-mahogany' : 'border-sand'"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-text-dark">Pay with Flutterwave</p>
                                <p class="text-xs text-text-muted mt-0.5">Card, Bank Transfer, USSD, Mobile Money via Flutterwave</p>
                            </div>
                        </label>
                        @endif

                        @if(in_array('bank_transfer', $enabledGateways))
                        <label class="flex items-center gap-4 p-4 border cursor-pointer transition-colors"
                               :class="paymentMethod === 'bank_transfer' ? 'border-mahogany bg-mahogany/5' : 'border-sand/40 hover:border-sand'"
                               @click="paymentMethod = 'bank_transfer'">
                            <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 transition-colors"
                                 :class="paymentMethod === 'bank_transfer' ? 'border-mahogany bg-mahogany' : 'border-sand'"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-text-dark">Direct Bank Transfer</p>
                                <p class="text-xs text-text-muted mt-0.5">Transfer directly to our account and upload your receipt — order confirmed within 24h</p>
                            </div>
                            <svg class="w-5 h-5 text-text-muted opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </label>
                        @endif
                    </div>
                </div>
                @else
                <p class="text-sm text-text-muted font-sans mb-6"
                   x-show="paymentMethod !== 'bank_transfer'">You'll be securely redirected to complete payment.</p>
                <p class="text-sm text-text-muted font-sans mb-6"
                   x-show="paymentMethod === 'bank_transfer'">Transfer to our account and upload your payment receipt. Your order will be confirmed within 24 hours.</p>
                @endif

                <div class="flex gap-4">
                    <button type="button" @click="step = 2" class="btn-secondary">Back</button>

                    <form action="{{ route('checkout.place') }}" method="POST"
                          @submit="
                              $refs.fName.value     = name.trim();
                              $refs.fEmail.value    = email.trim();
                              $refs.fPhone.value    = phone.trim();
                              $refs.fAddr1.value    = address1.trim();
                              $refs.fAddr2.value    = address2.trim();
                              $refs.fCity.value     = city.trim();
                              $refs.fState.value    = state.trim();
                              $refs.fCountry.value  = country;
                              $refs.fShipping.value = shippingMethod;
                              $refs.fPayment.value  = paymentMethod;
                              $refs.fCoupon.value   = couponValid ? couponCode.trim() : '';
                          ">
                        @csrf
                        <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="hidden" name="name"            x-ref="fName">
                        <input type="hidden" name="email"           x-ref="fEmail">
                        <input type="hidden" name="phone"           x-ref="fPhone">
                        <input type="hidden" name="address_line_1"  x-ref="fAddr1">
                        <input type="hidden" name="address_line_2"  x-ref="fAddr2">
                        <input type="hidden" name="city"            x-ref="fCity">
                        <input type="hidden" name="state"           x-ref="fState">
                        <input type="hidden" name="country"         x-ref="fCountry">
                        <input type="hidden" name="shipping_method" x-ref="fShipping">
                        <input type="hidden" name="coupon_code"     x-ref="fCoupon">
                        <input type="hidden" name="payment_method"  x-ref="fPayment">

                        <button type="submit" class="btn-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span x-text="paymentMethod === 'bank_transfer' ? 'Place Order' : 'Pay ' + fmt(total) + ' Securely'"></span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-2">
            <div class="bg-sand/10 p-6 sticky top-24">
                <h3 class="font-display text-lg mb-5">Order Summary</h3>
                <div class="space-y-3 mb-5">
                    @foreach($items as $item)
                    <div class="flex gap-3 items-center">
                        <div class="relative">
                            <div class="w-14 h-14 bg-sand/30 overflow-hidden flex-shrink-0">
                                @if($item->product->primaryImage)
                                <img src="{{ asset('images/products/' . basename($item->product->primaryImage->image_path)) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-sage text-cream text-[10px] rounded-full flex items-center justify-center font-bold">{{ $item->quantity }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-sans text-text-dark truncate">{{ $item->product->name }}</p>
                            @if($item->variant)<p class="text-[10px] text-text-muted">{{ $item->variant->name }}</p>@endif
                        </div>
                        <span class="text-sm font-sans text-sage flex-shrink-0">₦{{ number_format($item->price_at_add * $item->quantity, 0) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-sand/50 pt-4 space-y-2 text-sm font-sans">
                    <div class="flex justify-between text-text-muted">
                        <span>Subtotal</span><span>₦{{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-mahogany" x-show="couponDiscount > 0">
                        <span>Discount</span><span x-text="'−' + fmt(couponDiscount)"></span>
                    </div>
                    <div class="flex justify-between text-text-muted">
                        <span>Shipping</span>
                        <span :class="shippingFee === 0 ? 'text-mahogany' : ''"
                              x-text="shippingFee > 0 ? fmt(shippingFee) : 'Free'"></span>
                    </div>
                    <div class="flex justify-between font-medium text-text-dark border-t border-sand/50 pt-2">
                        <span class="font-display">Total</span>
                        <span class="font-display text-sage" x-text="fmt(total)"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
