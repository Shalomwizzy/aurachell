@extends('layouts.admin')
@section('title', 'Shipping Zones')
@section('breadcrumb', 'System')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Shipping Zones</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">Configure delivery rates by Nigerian region</p>
    </div>
    <a href="{{ route('admin.shipping.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 text-xs tracking-widest uppercase font-medium"
       style="background:#371220;color:#FFFFFF;">
        + Add Zone
    </a>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 text-sm" style="background:rgba(201,169,111,0.10);color:rgba(247,242,235,0.85);border:1px solid rgba(201,169,111,0.25);">{{ session('success') }}</div>
@endif

<div class="space-y-4">
    @forelse($zones as $zone)
    <div class="p-5 transition-colors" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-white font-medium">{{ $zone->name }}</h2>
                    @if($zone->is_active)
                    <span class="text-[10px] px-2 py-0.5 tracking-widest uppercase" style="background:rgba(201,169,111,0.12);color:#C9A96F;">Active</span>
                    @else
                    <span class="text-[10px] px-2 py-0.5 tracking-widest uppercase" style="background:rgba(156,163,175,0.1);color:rgba(55,18,32,0.55);">Inactive</span>
                    @endif
                </div>
                <p class="text-xs mb-3" style="color:var(--adm-muted);">
                    {{ implode(', ', $zone->states) }}
                </p>
                <div class="flex flex-wrap gap-4">
                    @foreach($zone->rates->sortBy('method') as $rate)
                    <div class="text-xs" style="color:var(--adm-muted);">
                        <span class="uppercase tracking-wider">{{ $rate->method }}</span>:
                        <span class="text-white">
                            @if($rate->free_shipping_threshold > 0)
                                ₦{{ number_format($rate->price, 0) }}
                                <span style="color:var(--adm-gold);">(free over ₦{{ number_format($rate->free_shipping_threshold, 0) }})</span>
                            @else
                                ₦{{ number_format($rate->price, 0) }}
                            @endif
                        </span>
                        · {{ $rate->deliveryLabel() }}
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <form method="POST" action="{{ route('admin.shipping.toggle', $zone) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-3 py-1.5 text-xs transition-colors"
                            style="background:var(--adm-surface-alt);color:var(--adm-muted);">
                        {{ $zone->is_active ? 'Disable' : 'Enable' }}
                    </button>
                </form>
                <a href="{{ route('admin.shipping.edit', $zone) }}"
                   class="px-3 py-1.5 text-xs transition-colors"
                   style="background:var(--adm-surface-alt);color:var(--adm-text);">Edit</a>
                <form method="POST" action="{{ route('admin.shipping.destroy', $zone) }}"
                      onsubmit="return confirm('Delete {{ $zone->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 text-xs" style="background:rgba(55,18,32,0.10);color:rgba(55,18,32,0.80);">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <p class="text-center py-12 text-sm" style="color:var(--adm-muted);">No shipping zones yet. <a href="{{ route('admin.shipping.create') }}" style="color:var(--adm-gold);">Add one</a>.</p>
    @endforelse
</div>
@endsection
