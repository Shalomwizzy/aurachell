@extends('layouts.admin')
@section('title', $zone->exists ? 'Edit Zone' : 'New Zone')
@section('breadcrumb', 'System')

@section('content')
<div class="max-w-2xl">
    <h1 class="font-display text-2xl text-white mb-8">
        {{ $zone->exists ? 'Edit: ' . $zone->name : 'New Shipping Zone' }}
    </h1>

    @if($errors->any())
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(55,18,32,0.10);color:var(--adm-text);border:1px solid rgba(55,18,32,0.20);">
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
                    <label class="adm-label">Cities (comma-separated)</label>
                    <input type="text" name="cities"
                           value="{{ old('cities', is_array($zone->cities) ? implode(', ', $zone->cities) : '') }}"
                           class="admin-input"
                           placeholder="Lekki, Ikate, Chevron, VI, Ikoyi, Orchid" required>
                    <div class="mt-2 p-3 text-xs leading-relaxed" style="background:rgba(201,169,111,0.08);border:1px solid rgba(201,169,111,0.25);color:var(--adm-text);">
                        <p class="mb-1" style="color:var(--adm-gold);font-weight:600;">How city matching works</p>
                        <p style="color:var(--adm-muted);">Shipping fees are matched by the <strong>city</strong> a customer types at checkout. Type each city exactly as customers will enter it — capital letters and spacing don't matter, but the spelling must match.</p>
                        <p class="mt-1" style="color:var(--adm-muted);">If customers use different spellings for the same place, add <strong>every version</strong> to this zone, separated by commas — e.g. <span style="color:var(--adm-gold);">Victoria Island, VI, V.I</span>. Any city not listed in a zone gets the default fallback shipping rate.</p>
                    </div>
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
                    style="background:#371220;color:#FFFFFF;">
                {{ $zone->exists ? 'Update Zone' : 'Create Zone' }}
            </button>
            <a href="{{ route('admin.shipping.index') }}"
               class="px-6 py-2.5 text-xs tracking-widest uppercase"
               style="background:var(--adm-surface-alt);color:var(--adm-muted);">Cancel</a>
        </div>
    </form>
</div>
@endsection
