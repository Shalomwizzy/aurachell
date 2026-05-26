@extends('layouts.admin')
@section('title', 'Return Request — ' . $return->order->order_number)
@section('breadcrumb', 'Returns / Review')

@section('content')
<div class="p-6 lg:p-8 max-w-5xl">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.returns.index') }}"
               class="text-xs mb-2 inline-flex items-center gap-1.5 transition-colors"
               style="color:var(--adm-muted);"
               onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                All Returns
            </a>
            <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">{{ $return->order->order_number }}</h1>
            <p class="text-sm mt-0.5" style="color:var(--adm-muted);">Submitted {{ $return->created_at->format('d M Y, g:ia') }} by {{ $return->user->name }}</p>
        </div>
        @php
            $colors = match($return->status) {
                'approved' => 'background:rgba(22,163,74,0.12);color:#4ade80;border:1px solid rgba(22,163,74,0.25);',
                'refunded' => 'background:rgba(59,130,246,0.12);color:#60a5fa;border:1px solid rgba(59,130,246,0.25);',
                'rejected' => 'background:rgba(239,68,68,0.12);color:#f87171;border:1px solid rgba(239,68,68,0.25);',
                default    => 'background:rgba(245,158,11,0.12);color:#fbbf24;border:1px solid rgba(245,158,11,0.25);',
            };
        @endphp
        <span class="text-xs px-3 py-1.5 rounded-full font-medium" style="{{ $colors }}">
            {{ $return->statusLabel() }}
        </span>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm rounded-sm flex items-center gap-3"
         style="background:rgba(22,163,74,0.10);border:1px solid rgba(22,163,74,0.25);color:#4ade80;">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Left: Details + order items --}}
        <div class="lg:col-span-3 space-y-5">

            {{-- Request details --}}
            <div class="rounded-lg p-5 space-y-4" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
                <p class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-gold);">Request Details</p>

                <div>
                    <p class="adm-label">Reason</p>
                    <p class="text-sm" style="color:var(--adm-text);">{{ $return->reason }}</p>
                </div>

                @if($return->details)
                <div>
                    <p class="adm-label">Customer's Description</p>
                    <p class="text-sm leading-relaxed" style="color:var(--adm-text);">{{ $return->details }}</p>
                </div>
                @endif

                @if($return->admin_notes)
                <div class="pt-3 border-t" style="border-color:var(--adm-border);">
                    <p class="adm-label">Your Previous Notes</p>
                    <p class="text-sm leading-relaxed" style="color:var(--adm-muted);">{{ $return->admin_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Order items --}}
            <div class="rounded-lg overflow-hidden" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
                <div class="px-5 py-3.5 border-b" style="border-color:var(--adm-border);">
                    <p class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-gold);">
                        Items in Order
                        <a href="{{ route('admin.orders.show', $return->order->id) }}" class="ml-2 normal-case tracking-normal underline" style="color:var(--adm-muted);">View full order →</a>
                    </p>
                </div>
                <div class="divide-y" style="border-color:var(--adm-border);">
                    @foreach($return->order->items as $item)
                    <div class="flex items-center gap-4 px-5 py-4">
                        @if($item->product)
                        <img src="{{ $item->product->primaryImageUrl }}" alt="{{ $item->product_name }}"
                             class="w-12 h-12 object-cover rounded flex-shrink-0">
                        @else
                        <div class="w-12 h-12 rounded flex-shrink-0" style="background:var(--adm-surface);"></div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium" style="color:var(--adm-text);">{{ $item->product_name }}</p>
                            @if($item->variant_name)
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">{{ $item->variant_name }}</p>
                            @endif
                            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Qty: {{ $item->quantity }} × ₦{{ number_format($item->unit_price, 0) }}</p>
                        </div>
                        <p class="text-sm font-medium flex-shrink-0" style="color:var(--adm-text);">₦{{ number_format($item->total_price, 0) }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 py-3.5 border-t flex justify-between text-sm" style="border-color:var(--adm-border);">
                    <span style="color:var(--adm-muted);">Order Total</span>
                    <span class="font-semibold" style="color:var(--adm-gold);">₦{{ number_format($return->order->total, 0) }}</span>
                </div>
            </div>

        </div>

        {{-- Right: Customer info + action form --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Customer --}}
            <div class="rounded-lg p-5" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
                <p class="text-[10px] tracking-[0.2em] uppercase font-medium mb-4" style="color:var(--adm-gold);">Customer</p>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0"
                         style="background:rgba(201,169,111,0.15);color:var(--adm-gold);">
                        {{ strtoupper(substr($return->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color:var(--adm-text);">{{ $return->user->name }}</p>
                        <p class="text-xs" style="color:var(--adm-muted);">{{ $return->user->email }}</p>
                    </div>
                </div>
                @if($return->user->phone)
                <p class="text-xs" style="color:var(--adm-muted);">{{ $return->user->phone }}</p>
                @endif
            </div>

            {{-- Action form --}}
            <div class="rounded-lg p-5" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
                <p class="text-[10px] tracking-[0.2em] uppercase font-medium mb-4" style="color:var(--adm-gold);">Update Request</p>

                <form method="POST" action="{{ route('admin.returns.update', $return->id) }}" class="space-y-4">
                    @csrf @method('PATCH')

                    <div>
                        <label class="adm-label">New Status</label>
                        <select name="status" class="adm-input">
                            @foreach(['pending' => 'Under Review', 'approved' => 'Approved', 'rejected' => 'Rejected', 'refunded' => 'Refunded'] as $val => $label)
                            <option value="{{ $val }}" {{ $return->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="adm-label">Notes to Customer <span class="normal-case tracking-normal text-[10px]">(optional)</span></label>
                        <textarea name="admin_notes" rows="4" maxlength="1000"
                                  placeholder="Explain the decision or give instructions…"
                                  class="adm-input resize-none">{{ old('admin_notes', $return->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="adm-btn-primary w-full">
                        Save & Notify Customer
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    .adm-label { display:block; font-size:10px; letter-spacing:0.2em; text-transform:uppercase; color:var(--adm-muted); margin-bottom:8px; font-weight:500; }
    .adm-input { width:100%; background:var(--adm-surface-alt); border:1px solid var(--adm-border); padding:10px 14px; font-size:13px; color:var(--adm-text); border-radius:4px; transition:border-color .15s, box-shadow .15s; }
    .adm-input:focus { outline:none; border-color:var(--adm-gold); box-shadow:0 0 0 1px var(--adm-gold); }
    .adm-btn-primary { padding:12px 32px; font-size:11px; letter-spacing:0.2em; text-transform:uppercase; font-weight:500; background:var(--adm-accent); color:#FAF5ED; border-radius:4px; transition:opacity .15s, transform .05s; }
    .adm-btn-primary:hover { opacity:0.92; }
    .adm-btn-primary:active { transform:scale(0.98); }
</style>
@endsection
