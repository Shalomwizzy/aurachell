@extends('layouts.app')
@section('title', 'Contact Us — Aurachell')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <p class="font-sans text-xs tracking-[0.3em] uppercase text-sage mb-3">Get in Touch</p>
        <h1 class="font-display text-4xl text-text-dark mb-3">We'd Love to Hear from You</h1>
        <p class="text-text-muted font-sans">Questions about a product, an order, or just want to say hello? We're here.</p>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
        <div class="lg:col-span-3">
            <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                @csrf
                <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input-luxury" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-luxury" required>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="input-luxury">
                </div>
                <div>
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" class="input-luxury" required>
                </div>
                <div>
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Message *</label>
                    <textarea name="message" rows="5" class="w-full border-b border-sand bg-transparent py-3 text-text-dark placeholder-text-muted focus:outline-none focus:border-sage transition-colors resize-none" required>{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="btn-primary">Send Message</button>
            </form>
        </div>
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h3 class="font-display text-xl mb-3">Get in Touch</h3>
                <div class="space-y-3 text-sm font-sans text-text-muted">
                    <p><a href="mailto:hello@aurachell.com" class="text-sage hover:underline">hello@aurachell.com</a></p>
                    <p>{{ \App\Models\Setting::get('store_phone', '+234 800 000 0000') }}</p>
                    <p>{{ \App\Models\Setting::get('store_address', 'Lagos, Nigeria') }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-display text-xl mb-3">Business Hours</h3>
                <div class="space-y-2 text-sm font-sans text-text-muted">
                    <p>Monday – Friday: 9am – 6pm WAT</p>
                    <p>Saturday: 10am – 4pm WAT</p>
                    <p>Sunday: Closed</p>
                </div>
            </div>
            <div class="bg-sage/5 p-5">
                <p class="font-sans text-sm text-text-muted">For order tracking, use the <a href="{{ route('track-order') }}" class="text-sage underline">Track Order</a> page with your tracking code.</p>
            </div>
        </div>
    </div>
</div>
@endsection
