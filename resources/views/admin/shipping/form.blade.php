@extends('layouts.admin')
@section('title', $zone->exists ? 'Edit Zone' : 'New Zone')
@section('breadcrumb', 'System')

@section('content')
<div class="max-w-2xl">
    <h1 class="font-display text-2xl text-white mb-8">
        {{ $zone->exists ? 'Edit: ' . $zone->name : 'New Shipping Zone' }}
    </h1>

    @if($errors->any())
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(55,18,32,0.10);color:rgba(55,18,32,0.80);border:1px solid rgba(55,18,32,0.20);">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST"
          action="{{ $zone->exists ? route('admin.shipping.update', $zone) : route('admin.shipping.store') }}">
        @csrf
        @if($zone->exists) @method('PUT') @endif

        {{-- Zone basics --}}
        <div class="p-6 mb-4" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <h2 class="text-sm tracking-widest uppercase mb-4" style="color:var(--adm-gold);">Zone Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="adm-label">Zone Name</label>
                    <input type="text" name="name" value="{{ old('name', $zone->name) }}"
                           class="admin-input" placeholder="e.g. Lagos, South-West" required>
                </div>
                <div>
                    <label class="adm-label">States (comma-separated)</label>
                    <input type="text" name="states"
                           value="{{ old('states', is_array($zone->states) ? implode(', ', $zone->states) : '') }}"
                           class="admin-input"
                           placeholder="Lagos, Ogun, Oyo" required>
                    <p class="text-xs mt-1" style="color:var(--adm-muted);">Type state names exactly as customers will enter them at checkout.</p>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', $zone->is_active ?? true) ? 'checked' : '' }}
                           class="accent-[#371220]">
                    <label for="is_active" class="text-sm" style="color:var(--adm-text);">Zone is active</label>
                </div>
                <div>
                    <label class="adm-label">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $zone->sort_order ?? 0) }}"
                           class="admin-input w-24" min="0">
                </div>
            </div>
        </div>

        {{-- Standard rate --}}
        @php
            $std = $zone->exists ? $zone->rates->firstWhere('method', 'standard') : null;
            $exp = $zone->exists ? $zone->rates->firstWhere('method', 'express') : null;
        @endphp
        <div class="p-6 mb-4" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <h2 class="text-sm tracking-widest uppercase mb-4" style="color:var(--adm-gold);">Standard Delivery</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="adm-label">Price (₦)</label>
                    <input type="number" name="standard_price" value="{{ old('standard_price', $std?->price ?? 2500) }}"
                           class="admin-input" min="0" step="0.01" required>
                </div>
                <div>
                    <label class="adm-label">Free Shipping From (₦, 0 = disabled)</label>
                    <input type="number" name="standard_free_threshold"
                           value="{{ old('standard_free_threshold', $std?->free_shipping_threshold ?? 50000) }}"
                           class="admin-input" min="0" step="0.01">
                </div>
                <div>
                    <label class="adm-label">Min Days</label>
                    <input type="number" name="standard_min_days" value="{{ old('standard_min_days', $std?->min_days ?? 2) }}"
                           class="admin-input" min="1" required>
                </div>
                <div>
                    <label class="adm-label">Max Days</label>
                    <input type="number" name="standard_max_days" value="{{ old('standard_max_days', $std?->max_days ?? 4) }}"
                           class="admin-input" min="1" required>
                </div>
            </div>
        </div>

        {{-- Express rate --}}
        <div class="p-6 mb-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <h2 class="text-sm tracking-widest uppercase mb-4" style="color:var(--adm-gold);">Express Delivery</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="adm-label">Price (₦)</label>
                    <input type="number" name="express_price" value="{{ old('express_price', $exp?->price ?? 5000) }}"
                           class="admin-input" min="0" step="0.01" required>
                </div>
                <div>
                    <label class="adm-label">Free Shipping From (₦, 0 = disabled)</label>
                    <input type="number" name="express_free_threshold"
                           value="{{ old('express_free_threshold', $exp?->free_shipping_threshold ?? 0) }}"
                           class="admin-input" min="0" step="0.01">
                </div>
                <div>
                    <label class="adm-label">Min Days</label>
                    <input type="number" name="express_min_days" value="{{ old('express_min_days', $exp?->min_days ?? 1) }}"
                           class="admin-input" min="1" required>
                </div>
                <div>
                    <label class="adm-label">Max Days</label>
                    <input type="number" name="express_max_days" value="{{ old('express_max_days', $exp?->max_days ?? 2) }}"
                           class="admin-input" min="1" required>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 text-xs tracking-widest uppercase font-medium"
                    style="background:#371220;color:#FAF5ED;">
                {{ $zone->exists ? 'Update Zone' : 'Create Zone' }}
            </button>
            <a href="{{ route('admin.shipping.index') }}"
               class="px-6 py-2.5 text-xs tracking-widest uppercase"
               style="background:var(--adm-surface-alt);color:var(--adm-muted);">Cancel</a>
        </div>
    </form>
</div>
@endsection
