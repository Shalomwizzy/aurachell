@extends('layouts.admin')
@section('title', 'Coupons')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-white/90">Coupons</h1>
            <p class="text-sm text-white/40 mt-1">Manage discount codes</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}"
           class="px-5 py-2.5 text-xs tracking-[0.15em] uppercase font-medium transition-all hover:opacity-90"
           style="background:rgba(55,18,32,0.90);color:#FFFFFF;">
            + New Coupon
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(201,169,111,0.10);border:1px solid rgba(201,169,111,0.25);color:rgba(247,242,235,0.85);">
        {{ session('success') }}
    </div>
    @endif

    <div class="border border-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/10" style="background:rgba(255,255,255,0.03);">
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase text-white/40 font-normal">Code</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase text-white/40 font-normal hidden md:table-cell">Discount</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase text-white/40 font-normal hidden lg:table-cell">Usage</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase text-white/40 font-normal hidden lg:table-cell">Validity</th>
                    <th class="text-center px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase text-white/40 font-normal">Status</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-4">
                        <span class="font-mono text-[#371220]/80 tracking-wider">{{ $coupon->code }}</span>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="text-white/70">
                            @if($coupon->type === 'percentage')
                                {{ $coupon->value }}% off
                            @else
                                ₦{{ number_format($coupon->value, 2) }} off
                            @endif
                        </span>
                        @if($coupon->min_order_amount > 0)
                        <span class="text-white/25 text-xs block">Min ₦{{ number_format($coupon->min_order_amount) }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        <span class="text-white/60">{{ $coupon->used_count }}</span>
                        @if($coupon->max_uses)
                        <span class="text-white/25"> / {{ $coupon->max_uses }}</span>
                        @else
                        <span class="text-white/25"> / ∞</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs text-white/40">
                        @if($coupon->valid_from || $coupon->valid_until)
                            {{ $coupon->valid_from?->format('M j') ?? '∞' }} — {{ $coupon->valid_until?->format('M j, Y') ?? '∞' }}
                        @else
                            <span class="text-white/25">No expiry</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($coupon->isValid())
                        <span class="text-[10px] px-2.5 py-1 tracking-wider uppercase bg-mahogany/12 text-mahogany/80">Active</span>
                        @else
                        <span class="text-[10px] px-2.5 py-1 tracking-wider uppercase bg-mahogany/10 text-mahogany/80">
                            {{ !$coupon->is_active ? 'Disabled' : 'Expired' }}
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}"
                               class="text-xs text-white/40 hover:text-cream/70 transition-colors">Edit</a>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}"
                                  onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-mahogany/40 hover:text-mahogany/80 transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center text-white/25 text-sm">No coupons yet. <a href="{{ route('admin.coupons.create') }}" class="text-[#371220]/60 hover:text-[#371220]">Create one →</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($coupons->hasPages())
    <div class="mt-6">{{ $coupons->links() }}</div>
    @endif
</div>
@endsection
