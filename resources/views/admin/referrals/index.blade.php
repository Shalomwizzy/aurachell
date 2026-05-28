@extends('layouts.admin')
@section('title', 'Referral Program')
@section('breadcrumb', 'Referrals')

@section('content')
@php
    $s = $stats['settings'];
    $rewardPct    = $s['referral_reward_percent']       ?? 10;
    $triggerMin   = $s['referral_trigger_min_order']    ?? 50000;
    $couponMin    = $s['referral_coupon_min_order']      ?? 5000;
    $validDays    = $s['referral_coupon_validity_days']  ?? 90;
    $convRate     = $stats['total'] > 0 ? round($stats['rewarded'] / $stats['total'] * 100) : 0;
@endphp

<div class="p-6 space-y-6 max-w-7xl">

    {{-- Page header --}}
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text-strong);">Referral Program</h1>
            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Track who's referring and who's converting.</p>
        </div>
        <a href="{{ route('admin.referrals.settings') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs tracking-[0.12em] uppercase font-medium rounded-sm transition-all"
           style="background:rgba(55,18,32,0.10);color:var(--adm-gold);border:1px solid rgba(55,18,32,0.25);"
           onmouseover="this.style.background='rgba(55,18,32,0.18)'" onmouseout="this.style.background='rgba(55,18,32,0.10)'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Program Settings
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Sign-ups',   'value' => $stats['total'],    'sub' => 'via referral code',      'color' => 'rgba(55,18,32,0.15)', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Rewarded',         'value' => $stats['rewarded'], 'sub' => 'friends made purchase',  'color' => 'rgba(55,18,32,0.12)',    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Pending',          'value' => $stats['pending'],  'sub' => 'awaiting first purchase', 'color' => 'rgba(55,18,32,0.15)',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Conversion Rate',  'value' => $convRate . '%',    'sub' => 'sign-ups → purchases',   'color' => 'rgba(55,18,32,0.12)',    'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
        ] as $card)
        <div class="rounded-sm p-4" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[10px] tracking-[0.18em] uppercase font-medium" style="color:var(--adm-muted);">{{ $card['label'] }}</p>
                    <p class="text-2xl font-semibold mt-1.5 leading-none" style="color:var(--adm-text-strong);">{{ $card['value'] }}</p>
                    <p class="text-[11px] mt-1.5" style="color:var(--adm-muted);">{{ $card['sub'] }}</p>
                </div>
                <div class="w-9 h-9 rounded-sm flex items-center justify-center flex-shrink-0 mt-0.5" style="background:{{ $card['color'] }};">
                    <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $card['icon'] }}"/></svg>
                </div>
            </div>
            {{-- Progress bar for conversion rate --}}
            @if($card['label'] === 'Conversion Rate' && $stats['total'] > 0)
            <div class="mt-3 h-1 rounded-full overflow-hidden" style="background:rgba(55,18,32,0.15);">
                <div class="h-full rounded-full transition-all" style="width:{{ min($convRate, 100) }}%;background:var(--adm-gold);"></div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Active program rules banner --}}
    <div class="rounded-sm px-5 py-4 flex flex-wrap items-center gap-6" style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.18);">
        <p class="text-[10px] tracking-[0.18em] uppercase font-medium flex-shrink-0" style="color:var(--adm-gold);">Active Rules</p>
        <div class="flex flex-wrap gap-6">
            @foreach([
                ['Reward',        $rewardPct . '% off coupon'],
                ['Trigger Min',   '₦' . number_format($triggerMin, 0) . ' order'],
                ['Coupon Min',    '₦' . number_format($couponMin, 0) . ' to use'],
                ['Validity',      $validDays . ' days'],
            ] as [$k, $v])
            <div>
                <p class="text-[10px] tracking-wider uppercase" style="color:var(--adm-muted);">{{ $k }}</p>
                <p class="text-sm font-semibold mt-0.5" style="color:var(--adm-text-strong);">{{ $v }}</p>
            </div>
            @endforeach
        </div>
        <a href="{{ route('admin.referrals.settings') }}" class="ml-auto text-[11px] tracking-wider" style="color:var(--adm-muted);">Edit →</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- ── Referrals table ── --}}
        <div class="lg:col-span-2 rounded-sm overflow-hidden" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
            <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:var(--adm-border);">
                <div>
                    <h2 class="text-sm font-semibold" style="color:var(--adm-text-strong);">All Referrals</h2>
                    <p class="text-[11px] mt-0.5" style="color:var(--adm-muted);">Most recent first</p>
                </div>
                <span class="text-[11px] px-2.5 py-1 rounded-full" style="background:rgba(55,18,32,0.12);color:var(--adm-muted);">
                    {{ $referrals->total() }} total
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom:1px solid var(--adm-border);">
                            <th class="px-4 py-3 text-left text-[10px] tracking-[0.18em] uppercase font-medium" style="color:var(--adm-muted);">Referrer</th>
                            <th class="px-4 py-3 text-left text-[10px] tracking-[0.18em] uppercase font-medium" style="color:var(--adm-muted);">Referred</th>
                            <th class="px-4 py-3 text-left text-[10px] tracking-[0.18em] uppercase font-medium" style="color:var(--adm-muted);">Status</th>
                            <th class="px-4 py-3 text-left text-[10px] tracking-[0.18em] uppercase font-medium" style="color:var(--adm-muted);">Reward Code</th>
                            <th class="px-4 py-3 text-left text-[10px] tracking-[0.18em] uppercase font-medium" style="color:var(--adm-muted);">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $referral)
                        <tr style="border-bottom:1px solid var(--adm-border);" class="transition-colors hover:bg-[rgba(55,18,32,0.03)]">

                            {{-- Referrer --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-semibold flex-shrink-0"
                                         style="background:rgba(55,18,32,0.2);color:var(--adm-gold);">
                                        {{ strtoupper(substr($referral->referrer->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-medium truncate max-w-[120px]" style="color:var(--adm-text-strong);">{{ $referral->referrer->name }}</p>
                                        <p class="text-[11px] truncate max-w-[120px]" style="color:var(--adm-muted);">{{ $referral->referrer->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Referred --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-semibold flex-shrink-0"
                                         style="background:rgba(55,18,32,0.15);color:var(--adm-muted);">
                                        {{ strtoupper(substr($referral->referred->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-medium truncate max-w-[120px]" style="color:var(--adm-text-strong);">{{ $referral->referred->name }}</p>
                                        <p class="text-[11px] truncate max-w-[120px]" style="color:var(--adm-muted);">{{ $referral->referred->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3.5">
                                @if($referral->status === 'rewarded')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium" style="background:rgba(55,18,32,0.12);color:#371220;">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#371220;"></span> Rewarded
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium" style="background:rgba(55,18,32,0.15);color:#371220;">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#371220;"></span> Pending
                                </span>
                                @endif
                            </td>

                            {{-- Reward code --}}
                            <td class="px-4 py-3.5">
                                @if($referral->reward_coupon_code)
                                <code class="text-[11px] px-2 py-1 rounded-sm font-mono tracking-wider" style="background:rgba(55,18,32,0.10);color:var(--adm-gold);">{{ $referral->reward_coupon_code }}</code>
                                @else
                                <span class="text-[12px]" style="color:var(--adm-muted);">—</span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td class="px-4 py-3.5 text-[12px] whitespace-nowrap" style="color:var(--adm-muted);">
                                {{ $referral->created_at->format('d M Y') }}
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-14 text-center" style="color:var(--adm-muted);">
                                <svg class="w-8 h-8 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                <p class="text-sm">No referrals yet.</p>
                                <p class="text-xs mt-1">Customers share their referral code at signup.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($referrals->hasPages())
            <div class="px-4 py-3 border-t" style="border-color:var(--adm-border);">
                {{ $referrals->links() }}
            </div>
            @endif
        </div>

        {{-- ── Right column ── --}}
        <div class="space-y-4">

            {{-- Top referrers --}}
            <div class="rounded-sm overflow-hidden" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
                <div class="px-5 py-4 border-b" style="border-color:var(--adm-border);">
                    <h2 class="text-sm font-semibold" style="color:var(--adm-text-strong);">Top Referrers</h2>
                    <p class="text-[11px] mt-0.5" style="color:var(--adm-muted);">Counted only after friend purchases</p>
                </div>

                @forelse($stats['top'] as $i => $user)
                @php $maxCount = $stats['top']->first()->rewarded_count ?? 1; @endphp
                <div class="px-5 py-3.5 {{ !$loop->last ? 'border-b' : '' }}" style="border-color:var(--adm-border);">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0"
                              style="{{ $i === 0 ? 'background:rgba(55,18,32,0.3);color:var(--adm-gold);' : 'background:rgba(55,18,32,0.1);color:var(--adm-muted);' }}">
                            {{ $i + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-medium truncate" style="color:var(--adm-text-strong);">{{ $user->name }}</p>
                        </div>
                        <span class="text-sm font-bold flex-shrink-0" style="color:{{ $i === 0 ? 'var(--adm-gold)' : 'var(--adm-text)' }};">
                            {{ $user->rewarded_count }}
                        </span>
                    </div>
                    <div class="h-1 rounded-full overflow-hidden" style="background:rgba(55,18,32,0.12);">
                        <div class="h-full rounded-full" style="width:{{ round($user->rewarded_count / $maxCount * 100) }}%;background:{{ $i === 0 ? 'var(--adm-gold)' : 'rgba(55,18,32,0.4)' }};transition:width .4s;"></div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center">
                    <p class="text-sm" style="color:var(--adm-muted);">No rewarded referrals yet.</p>
                </div>
                @endforelse
            </div>

            {{-- Program snapshot --}}
            <div class="rounded-sm overflow-hidden" style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);">
                <div class="px-5 py-4 border-b" style="border-color:var(--adm-border);">
                    <h2 class="text-sm font-semibold" style="color:var(--adm-text-strong);">How It Works</h2>
                </div>
                <div class="px-5 py-4 space-y-4">
                    @foreach([
                        ['1', 'Customer gets a unique code', 'Automatically assigned at registration'],
                        ['2', 'Friend signs up with code',   'Entered on the register form or via ?ref= link'],
                        ['3', 'Friend spends ₦' . number_format($triggerMin, 0) . '+', 'On their first order — admin-configurable'],
                        ['4', 'Referrer earns ' . $rewardPct . '% off', 'Coupon emailed automatically, valid ' . $validDays . ' days'],
                    ] as [$step, $title, $desc])
                    <div class="flex gap-3">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold flex-shrink-0 mt-0.5"
                             style="background:rgba(55,18,32,0.15);color:var(--adm-gold);">{{ $step }}</div>
                        <div>
                            <p class="text-[13px] font-medium" style="color:var(--adm-text-strong);">{{ $title }}</p>
                            <p class="text-[11px] mt-0.5" style="color:var(--adm-muted);">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
