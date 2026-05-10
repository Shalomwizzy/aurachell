@extends('layouts.admin')
@section('title', 'Referral Program Settings')
@section('breadcrumb', 'Referrals / Program Settings')

@section('content')
@php
    $pct   = $settings['referral_reward_percent']       ?? 10;
    $trig  = $settings['referral_trigger_min_order']    ?? 50000;
    $min   = $settings['referral_coupon_min_order']     ?? 5000;
    $days  = $settings['referral_coupon_validity_days'] ?? 90;
@endphp

<div class="p-6 lg:p-8 max-w-4xl">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">Program Settings</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">Configure how the referral reward is calculated and distributed.</p>
        </div>
        <a href="{{ route('admin.referrals.index') }}"
           class="inline-flex items-center gap-2 text-xs px-3 py-2 rounded transition-colors"
           style="border:1px solid var(--adm-border);color:var(--adm-muted);"
           onmouseover="this.style.color='var(--adm-text)';this.style.borderColor='rgba(196,164,140,0.4)'"
           onmouseout="this.style.color='var(--adm-muted)';this.style.borderColor='var(--adm-border)'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Overview
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm rounded-sm flex items-center gap-3"
         style="background:rgba(22,163,74,0.10);border:1px solid rgba(22,163,74,0.25);color:#4ade80;">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.referrals.settings.update') }}"
          x-data="{
              pct:  {{ $pct }},
              trig: {{ $trig }},
              min:  {{ $min }},
              days: {{ $days }},
              fmt(n) { return '₦' + Number(n).toLocaleString('en-NG'); }
          }">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Left: Form --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Reward % --}}
                <div class="adm-card p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0" style="background:rgba(107,32,22,0.25);">
                            <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text);">Reward Discount</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Percentage coupon given to the referrer</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="number" name="referral_reward_percent" min="1" max="50"
                               x-model.number="pct"
                               value="{{ old('referral_reward_percent', $pct) }}"
                               class="adm-input pr-10">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium pointer-events-none" style="color:var(--adm-gold);">%</span>
                    </div>
                    @error('referral_reward_percent')
                        <p class="text-xs mt-1.5" style="color:#f87171;">{{ $message }}</p>
                    @else
                        <p class="text-xs mt-1.5" style="color:var(--adm-muted);">Between 1% and 50%</p>
                    @enderror
                </div>

                {{-- Trigger --}}
                <div class="adm-card p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0" style="background:rgba(107,32,22,0.25);">
                            <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text);">Reward Trigger</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Minimum amount the referred friend must spend</p>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs pointer-events-none" style="color:var(--adm-muted);">₦</span>
                        <input type="number" name="referral_trigger_min_order" min="0" step="500"
                               x-model.number="trig"
                               value="{{ old('referral_trigger_min_order', $trig) }}"
                               class="adm-input"
                               style="padding-left:1.75rem;">
                    </div>
                    @error('referral_trigger_min_order')
                        <p class="text-xs mt-1.5" style="color:#f87171;">{{ $message }}</p>
                    @else
                        <p class="text-xs mt-1.5" style="color:var(--adm-muted);">No reward fires until the friend's first order reaches this amount</p>
                    @enderror
                </div>

                {{-- Coupon rules --}}
                <div class="adm-card p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0" style="background:rgba(107,32,22,0.25);">
                            <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:var(--adm-text);">Reward Coupon Rules</p>
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Conditions for using the earned coupon</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="adm-label">Min Order to Use Coupon</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs pointer-events-none" style="color:var(--adm-muted);">₦</span>
                                <input type="number" name="referral_coupon_min_order" min="0" step="500"
                                       x-model.number="min"
                                       value="{{ old('referral_coupon_min_order', $min) }}"
                                       class="adm-input"
                                       style="padding-left:1.75rem;">
                            </div>
                            @error('referral_coupon_min_order')
                                <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
                            @else
                                <p class="text-xs mt-1" style="color:var(--adm-muted);">Basket size required to redeem</p>
                            @enderror
                        </div>
                        <div>
                            <label class="adm-label">Coupon Validity</label>
                            <div class="relative">
                                <input type="number" name="referral_coupon_validity_days" min="7" max="365"
                                       x-model.number="days"
                                       value="{{ old('referral_coupon_validity_days', $days) }}"
                                       class="adm-input"
                                       style="padding-right:3.5rem;">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs pointer-events-none" style="color:var(--adm-gold);">days</span>
                            </div>
                            @error('referral_coupon_validity_days')
                                <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
                            @else
                                <p class="text-xs mt-1" style="color:var(--adm-muted);">7–365 days before expiry</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Save --}}
                <div class="pt-2 border-t flex justify-end" style="border-color:var(--adm-border);">
                    <button type="submit" class="adm-btn-primary">Save Settings</button>
                </div>
            </div>

            {{-- Right: Live preview + notes --}}
            <div class="lg:col-span-2 space-y-5">

                <div class="adm-card p-5">
                    <p class="text-[10px] tracking-[0.2em] uppercase font-medium mb-4" style="color:var(--adm-gold);">Live Preview</p>
                    <p class="text-xs leading-relaxed" style="color:var(--adm-text);">
                        When a referred friend spends at least
                        <span x-text="fmt(trig)" class="font-semibold" style="color:var(--adm-gold);"></span>,
                        the referrer earns a
                        <span x-text="pct + '% off'" class="font-semibold" style="color:var(--adm-gold);"></span>
                        coupon — valid for
                        <span x-text="days + ' days'" class="font-semibold" style="color:var(--adm-gold);"></span>
                        on orders over
                        <span x-text="fmt(min)" class="font-semibold" style="color:var(--adm-gold);"></span>.
                    </p>

                    <div class="mt-5 pt-4 border-t space-y-3" style="border-color:var(--adm-border);">
                        @foreach([
                            'Customer shares referral code',
                            'Friend registers using the code',
                            'Friend\'s first order meets trigger',
                            'Referrer receives reward coupon email',
                        ] as $step)
                        <div class="flex items-start gap-2.5">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-bold"
                                 style="{{ $loop->index >= 2 ? 'background:rgba(196,164,140,0.15);color:var(--adm-gold);border:1px solid rgba(196,164,140,0.30);' : 'background:var(--adm-surface);color:var(--adm-muted);border:1px solid var(--adm-border);' }}">
                                {{ $loop->iteration }}
                            </div>
                            <p class="text-xs leading-relaxed pt-0.5" style="color:{{ $loop->index >= 2 ? 'var(--adm-text)' : 'var(--adm-muted)' }};">{{ $step }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-4 rounded" style="background:rgba(196,164,140,0.06);border:1px solid rgba(196,164,140,0.12);">
                    <p class="text-xs leading-relaxed" style="color:var(--adm-muted);">
                        <strong style="color:var(--adm-text);">Note:</strong>
                        Each customer can only be referred once. The reward fires only on the friend's first paid order. Existing rewarded referrals are not affected by changes here.
                    </p>
                </div>

            </div>
        </div>
    </form>
</div>

<style>
    .adm-label {
        display: block;
        font-size: 10px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--adm-muted);
        margin-bottom: 8px;
        font-weight: 500;
    }
    .adm-input {
        width: 100%;
        background: var(--adm-surface-alt);
        border: 1px solid var(--adm-border);
        padding: 10px 14px;
        font-size: 13px;
        color: var(--adm-text);
        border-radius: 4px;
        transition: border-color .15s, box-shadow .15s;
    }
    .adm-input:focus {
        outline: none;
        border-color: var(--adm-gold);
        box-shadow: 0 0 0 1px var(--adm-gold);
    }
    .adm-btn-primary {
        padding: 12px 32px;
        font-size: 11px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        font-weight: 500;
        background: var(--adm-accent);
        color: #F5EDE4;
        border-radius: 4px;
        transition: opacity .15s, transform .05s;
    }
    .adm-btn-primary:hover { opacity: 0.92; }
    .adm-btn-primary:active { transform: scale(0.98); }
</style>
@endsection
