@extends('layouts.admin')
@section('title', 'Return Requests')
@section('breadcrumb', 'Orders / Returns')

@section('content')
<div class="p-6 lg:p-8">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">Return Requests</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">Review and process customer return & refund requests.</p>
    </div>

    {{-- Stat tabs --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach([
            ''         => ['All',      $counts['all'],      null],
            'pending'  => ['Pending',  $counts['pending'],  'rgba(201,169,111,0.15)'],
            'approved' => ['Approved', $counts['approved'], 'rgba(201,169,111,0.12)'],
            'rejected' => ['Rejected', $counts['rejected'], 'rgba(55,18,32,0.10)'],
            'refunded' => ['Refunded', $counts['refunded'], 'rgba(212,185,154,0.12)'],
        ] as $val => [$label, $count, $bg])
        @php $active = request('status', '') === $val; @endphp
        <a href="{{ route('admin.returns.index', $val ? ['status' => $val] : []) }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded text-xs font-medium transition-all"
           style="{{ $active ? 'background:rgba(201,169,111,0.18);color:var(--adm-gold);border:1px solid rgba(201,169,111,0.30);' : 'background:var(--adm-surface-alt);color:var(--adm-muted);border:1px solid var(--adm-border);' }}">
            {{ $label }}
            <span class="px-1.5 py-0.5 rounded-full text-[10px]"
                  style="{{ $bg ? "background:{$bg};" : 'background:rgba(212,185,154,0.10);' }}color:{{ $active ? 'var(--adm-gold)' : 'var(--adm-muted)' }}">
                {{ $count }}
            </span>
        </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="rounded-lg overflow-hidden" style="border:1px solid var(--adm-border);">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:var(--adm-surface-alt);border-bottom:1px solid var(--adm-border);">
                    <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Order</th>
                    <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Customer</th>
                    <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Reason</th>
                    <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Status</th>
                    <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium hidden lg:table-cell" style="color:var(--adm-muted);">Submitted</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $rr)
                <tr class="border-b transition-colors" style="border-color:var(--adm-border);"
                    onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background=''">
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.orders.show', $rr->order->id) }}"
                           class="font-mono text-xs hover:underline" style="color:var(--adm-gold);">
                            {{ $rr->order->order_number }}
                        </a>
                        <p class="text-[11px] mt-0.5" style="color:var(--adm-muted);">
                            ₦{{ number_format($rr->order->total, 0) }}
                        </p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-medium" style="color:var(--adm-text);">{{ $rr->user->name }}</p>
                        <p class="text-[11px] mt-0.5" style="color:var(--adm-muted);">{{ $rr->user->email }}</p>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell" style="color:var(--adm-text);">
                        <p class="text-xs">{{ $rr->reason }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $colors = match($rr->status) {
                                'approved' => 'background:rgba(201,169,111,0.12);color:#C9A96F;',
                                'refunded' => 'background:rgba(212,185,154,0.12);color:#C9A96F;',
                                'rejected' => 'background:rgba(55,18,32,0.10);color:rgba(212,185,154,0.80);',
                                default    => 'background:rgba(201,169,111,0.12);color:#C9A96F;',
                            };
                        @endphp
                        <span class="text-[10px] px-2.5 py-1 rounded-full font-medium" style="{{ $colors }}">
                            {{ $rr->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-xs" style="color:var(--adm-muted);">
                        {{ $rr->created_at->format('d M Y') }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.returns.show', $rr->id) }}"
                           class="text-xs px-3 py-1.5 rounded transition-colors"
                           style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                           onmouseover="this.style.color='var(--adm-text)';this.style.borderColor='rgba(201,169,111,0.4)'"
                           onmouseout="this.style.color='var(--adm-muted)';this.style.borderColor='var(--adm-border)'">
                            Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-sm" style="color:var(--adm-muted);">
                        No return requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($returns->hasPages())
    <div class="mt-4">{{ $returns->links() }}</div>
    @endif

</div>
@endsection
