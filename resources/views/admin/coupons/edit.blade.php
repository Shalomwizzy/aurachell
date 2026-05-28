@extends('layouts.admin')
@section('title', 'Edit Coupon')

@section('content')
<div class="p-6 lg:p-8 max-w-2xl">

    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('admin.coupons.index') }}" class="text-white/30 hover:text-cream/60 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-white/90">Edit Coupon</h1>
            <p class="text-sm text-white/35 font-mono tracking-widest">{{ $coupon->code }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="space-y-6">
        @csrf @method('PUT')

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-white/40 mb-2">Code *</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                       style="text-transform:uppercase"
                       class="w-full bg-white/5 border border-white/10 px-4 py-2.5 text-sm text-white/85 font-mono tracking-widest focus:outline-none focus:border-[#371220]/40 @error('code') border-mahogany/20 @enderror">
                @error('code')<p class="text-mahogany/80 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-white/40 mb-2">Type *</label>
                <select name="type" required
                        class="w-full bg-white/5 border border-white/10 px-4 py-2.5 text-sm text-white/85 focus:outline-none focus:border-[#371220]/40">
                    <option value="percentage" {{ old('type', $coupon->type)==='percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ old('type', $coupon->type)==='fixed' ? 'selected' : '' }}>Fixed Amount (₦)</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-white/40 mb-2">Discount Value *</label>
                <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $coupon->value) }}" required
                       class="w-full bg-white/5 border border-white/10 px-4 py-2.5 text-sm text-white/85 focus:outline-none focus:border-[#371220]/40">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-white/40 mb-2">Min Order Amount</label>
                <input type="number" step="0.01" min="0" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                       class="w-full bg-white/5 border border-white/10 px-4 py-2.5 text-sm text-white/85 focus:outline-none focus:border-[#371220]/40">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-white/40 mb-2">Max Uses</label>
                <input type="number" min="1" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}"
                       class="w-full bg-white/5 border border-white/10 px-4 py-2.5 text-sm text-white/85 focus:outline-none focus:border-[#371220]/40">
                <p class="text-white/25 text-xs mt-1">Used: {{ $coupon->used_count }}</p>
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-white/40 mb-2">Status</label>
                <label class="flex items-center gap-3 mt-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }}
                           class="w-4 h-4 bg-transparent border border-white/20 accent-sage cursor-pointer">
                    <span class="text-sm text-white/60">Active</span>
                </label>
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-white/40 mb-2">Valid From</label>
                <input type="datetime-local" name="valid_from"
                       value="{{ old('valid_from', $coupon->valid_from?->format('Y-m-d\TH:i')) }}"
                       class="w-full bg-white/5 border border-white/10 px-4 py-2.5 text-sm text-white/85 focus:outline-none focus:border-[#371220]/40">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-white/40 mb-2">Valid Until</label>
                <input type="datetime-local" name="valid_until"
                       value="{{ old('valid_until', $coupon->valid_until?->format('Y-m-d\TH:i')) }}"
                       class="w-full bg-white/5 border border-white/10 px-4 py-2.5 text-sm text-white/85 focus:outline-none focus:border-[#371220]/40">
            </div>
        </div>

        <div class="pt-4 flex gap-3">
            <button type="submit"
                    class="px-8 py-3 text-xs tracking-[0.2em] uppercase font-medium transition-all hover:opacity-90"
                    style="background:rgba(55,18,32,0.90);color:#FAF5ED;">
                Save Changes
            </button>
            <a href="{{ route('admin.coupons.index') }}"
               class="px-8 py-3 text-xs tracking-[0.2em] uppercase text-white/40 border border-white/10 hover:border-white/20 hover:text-cream/60 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
