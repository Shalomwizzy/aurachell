@extends('layouts.admin')
@section('title', 'New Coupon')
@section('breadcrumb', 'Finance')

@section('content')
<div class="p-6 lg:p-8 max-w-3xl mx-auto">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.coupons.index') }}"
           class="w-9 h-9 rounded flex items-center justify-center transition-colors"
           style="color:var(--adm-muted);background:var(--adm-surface-alt);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text-strong);">New Coupon</h1>
            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Create a discount code for promotions</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.coupons.store') }}" x-data="{ type: '{{ old('type', 'percentage') }}' }">
        @csrf

        {{-- Code & Type --}}
        <div class="adm-card p-6 space-y-5 mb-5">
            <div class="flex items-center gap-2 pb-3 border-b" style="border-color:var(--adm-border);">
                <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <h2 class="text-xs font-semibold tracking-[0.15em] uppercase" style="color:var(--adm-text);">Coupon Details</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="adm-label">Code <span style="color:var(--adm-danger-fg);">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                           placeholder="SUMMER25" style="text-transform:uppercase"
                           class="adm-input font-mono tracking-widest">
                    <p class="text-[10px] mt-1" style="color:var(--adm-muted);">Customer-facing — uppercase letters & numbers</p>
                    @error('code')<p class="text-xs mt-1" style="color:var(--adm-danger-fg);">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="adm-label">Discount Type <span style="color:var(--adm-danger-fg);">*</span></label>
                    <select name="type" x-model="type" required class="adm-input">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (₦)</option>
                    </select>
                </div>
                <div>
                    <label class="adm-label">Discount Value <span style="color:var(--adm-danger-fg);">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" name="value" value="{{ old('value') }}" required
                               :placeholder="type==='percentage' ? '25' : '5000'"
                               class="adm-input pr-10">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm font-medium pointer-events-none" style="color:var(--adm-muted);" x-text="type==='percentage' ? '%' : '₦'"></span>
                    </div>
                    @error('value')<p class="text-xs mt-1" style="color:var(--adm-danger-fg);">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="adm-label">Minimum Order Amount (₦)</label>
                    <input type="number" step="100" min="0" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" placeholder="0" class="adm-input">
                    <p class="text-[10px] mt-1" style="color:var(--adm-muted);">0 = no minimum</p>
                </div>
            </div>
        </div>

        {{-- Limits & Schedule --}}
        <div class="adm-card p-6 space-y-5 mb-5">
            <div class="flex items-center gap-2 pb-3 border-b" style="border-color:var(--adm-border);">
                <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h2 class="text-xs font-semibold tracking-[0.15em] uppercase" style="color:var(--adm-text);">Limits & Schedule</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="adm-label">Max Total Uses</label>
                    <input type="number" min="1" name="max_uses" value="{{ old('max_uses') }}" placeholder="e.g. 100" class="adm-input">
                    <p class="text-[10px] mt-1" style="color:var(--adm-muted);">Blank = unlimited</p>
                </div>
                <div>
                    <label class="adm-label">Max Uses Per Customer</label>
                    <input type="number" min="1" name="max_uses_per_user" value="{{ old('max_uses_per_user', 1) }}" placeholder="1" class="adm-input">
                </div>
                <div>
                    <label class="adm-label">Valid From</label>
                    <input type="datetime-local" name="valid_from" value="{{ old('valid_from') }}" class="adm-input">
                </div>
                <div>
                    <label class="adm-label">Valid Until</label>
                    <input type="datetime-local" name="valid_until" value="{{ old('valid_until') }}" class="adm-input">
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="adm-card p-6 mb-6">
            <label class="flex items-center justify-between gap-3 cursor-pointer">
                <div>
                    <p class="text-sm" style="color:var(--adm-text);">Active</p>
                    <p class="text-[10px]" style="color:var(--adm-muted);">Inactive coupons won't be accepted at checkout</p>
                </div>
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="w-4 h-4" style="accent-color:var(--adm-accent);">
            </label>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-end">
            <a href="{{ route('admin.coupons.index') }}"
               class="px-6 py-3 text-xs tracking-[0.2em] uppercase font-medium text-center rounded transition-colors"
               style="background:var(--adm-surface-alt);color:var(--adm-muted);">Cancel</a>
            <button type="submit"
                    class="px-8 py-3 text-xs tracking-[0.2em] uppercase font-medium rounded transition-opacity"
                    style="background:var(--adm-accent);color:#F5EDE4;"
                    onmouseover="this.style.opacity='0.92'" onmouseout="this.style.opacity='1'">
                Create Coupon
            </button>
        </div>
    </form>
</div>

<style>
.adm-label { display:block; font-size:10px; letter-spacing:0.2em; text-transform:uppercase; color:var(--adm-muted); margin-bottom:8px; font-weight:500; }
.adm-input { width:100%; background:var(--adm-surface-alt); border:1px solid var(--adm-border); padding:10px 14px; font-size:13px; color:var(--adm-text); border-radius:4px; transition:border-color .15s, box-shadow .15s; }
.adm-input::placeholder { color:var(--adm-muted); opacity:0.55; }
.adm-input:focus { outline:none; border-color:var(--adm-gold); box-shadow:0 0 0 1px var(--adm-gold); }
</style>
@endsection
