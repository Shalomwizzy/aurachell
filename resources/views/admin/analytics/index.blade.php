@extends('layouts.admin')
@section('title', 'Site Analytics')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold" style="color:var(--adm-text);">Site Analytics</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">Real visitor data tracked by Aurachell — last 30 days</p>
        </div>
        <span class="flex items-center gap-1.5 text-xs" style="color:#C9A96F;">
            <span class="w-2 h-2 rounded-full inline-block" style="background:#C9A96F;"></span>
            Live Tracking
        </span>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
        $kpis = [
            ['label' => 'Pageviews (30d)',    'value' => number_format($totalPageviews),  'sub' => number_format($weekPageviews) . ' this week',  'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Unique Visitors (30d)', 'value' => number_format($uniqueVisitors), 'sub' => number_format($weekVisitors) . ' this week', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Top Page',            'value' => $topPages->first()?->url ?? '—', 'sub' => $topPages->first() ? number_format($topPages->first()->views) . ' views' : '', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1', 'truncate' => true],
            ['label' => 'Top Source',          'value' => $referrers->first()?->referrer ?? '(direct)', 'sub' => $referrers->first() ? number_format($referrers->first()->visits) . ' visits' : 'no referrers yet', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9', 'truncate' => true],
        ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="px-5 py-4" style="border:1px solid var(--adm-border);background:var(--adm-card);">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[10px] tracking-[0.18em] uppercase" style="color:var(--adm-muted);">{{ $kpi['label'] }}</p>
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" style="color:var(--adm-gold);" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $kpi['icon'] }}"/>
                </svg>
            </div>
            <p class="font-semibold {{ ($kpi['truncate'] ?? false) ? 'text-sm truncate' : 'text-2xl' }} mb-1" style="color:var(--adm-text);">{{ $kpi['value'] }}</p>
            @if(!empty($kpi['sub']))
            <p class="text-[11px]" style="color:var(--adm-muted);">{{ $kpi['sub'] }}</p>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Chart + Top Pages --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

        {{-- Line Chart --}}
        <div class="lg:col-span-2 px-5 py-5" style="border:1px solid var(--adm-border);background:var(--adm-card);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-4" style="color:var(--adm-gold);">Daily Traffic — Last 30 Days</p>
            <canvas id="analytics-chart" height="90"></canvas>
        </div>

        {{-- Top Pages --}}
        <div class="px-5 py-5" style="border:1px solid var(--adm-border);background:var(--adm-card);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-4" style="color:var(--adm-gold);">Top Pages</p>
            @if($topPages->isEmpty())
            <p class="text-xs" style="color:var(--adm-muted);">No data yet. Pages will appear as visitors browse the store.</p>
            @else
            <div class="space-y-2.5">
                @php $maxViews = $topPages->first()->views; @endphp
                @foreach($topPages as $page)
                @php $pct = $maxViews > 0 ? round(($page->views / $maxViews) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs mb-0.5">
                        <span class="truncate pr-2" style="color:var(--adm-text);" title="{{ $page->url }}">
                            {{ $page->url === '/' ? 'Homepage' : $page->url }}
                        </span>
                        <span class="shrink-0" style="color:var(--adm-muted);">{{ number_format($page->views) }}</span>
                    </div>
                    <div class="h-1 w-full" style="background:rgba(55,18,32,0.12);">
                        <div class="h-1" style="width:{{ $pct }}%;background:#371220;"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Referrers + Devices --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Traffic Sources --}}
        <div class="px-5 py-5" style="border:1px solid var(--adm-border);background:var(--adm-card);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-4" style="color:var(--adm-gold);">Traffic Sources</p>
            @if($referrers->isEmpty())
            <p class="text-xs" style="color:var(--adm-muted);">No external referrers recorded yet.</p>
            @else
            <div class="space-y-2.5">
                @php $maxVisits = $referrers->first()->visits; @endphp
                @foreach($referrers as $ref)
                @php $pct = $maxVisits > 0 ? round(($ref->visits / $maxVisits) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs mb-0.5">
                        <span style="color:var(--adm-text);">{{ $ref->referrer }}</span>
                        <span style="color:var(--adm-muted);">{{ number_format($ref->visits) }}</span>
                    </div>
                    <div class="h-1 w-full" style="background:rgba(55,18,32,0.15);">
                        <div class="h-1" style="width:{{ $pct }}%;background:var(--adm-gold);"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Devices --}}
        <div class="px-5 py-5" style="border:1px solid var(--adm-border);background:var(--adm-card);">
            <p class="text-[10px] tracking-[0.2em] uppercase mb-4" style="color:var(--adm-gold);">Devices</p>
            @if($devices->isEmpty())
            <p class="text-xs" style="color:var(--adm-muted);">No device data yet.</p>
            @else
            @php $totalDevices = $devices->sum('count'); @endphp
            <div class="space-y-4">
                @foreach($devices as $d)
                @php $pct = $totalDevices > 0 ? round(($d->count / $totalDevices) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <div class="flex items-center gap-2">
                            @if($d->device === 'mobile')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" style="color:var(--adm-gold);" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            @elseif($d->device === 'tablet')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" style="color:var(--adm-gold);" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" style="color:var(--adm-gold);" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @endif
                            <span class="capitalize" style="color:var(--adm-text);">{{ $d->device }}</span>
                        </div>
                        <span style="color:var(--adm-muted);">{{ $pct }}% · {{ number_format($d->count) }}</span>
                    </div>
                    <div class="h-2 w-full rounded-full" style="background:rgba(55,18,32,0.12);">
                        <div class="h-2 rounded-full" style="width:{{ $pct }}%;background:#371220;"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels   = {!! $chartLabels !!};
    const views    = {!! $chartViews !!};
    const visitors = {!! $chartVisitors !!};

    const isDark    = document.documentElement.classList.contains('dark') || !document.body.classList.contains('adm-light');
    const textColor = isDark ? 'rgba(247,242,235,0.50)' : 'rgba(55,18,32,0.55)';
    const gridColor = isDark ? 'rgba(55,18,32,0.08)' : 'rgba(55,18,32,0.07)';

    new Chart(document.getElementById('analytics-chart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Pageviews',
                    data: views,
                    borderColor: '#371220',
                    backgroundColor: 'rgba(55,18,32,0.08)',
                    borderWidth: 1.5,
                    pointRadius: 0,
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Unique Visitors',
                    data: visitors,
                    borderColor: '#371220',
                    backgroundColor: 'rgba(55,18,32,0.07)',
                    borderWidth: 1.5,
                    pointRadius: 0,
                    fill: true,
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: textColor, boxWidth: 10, font: { size: 11 } } },
                tooltip: { mode: 'index', intersect: false },
            },
            scales: {
                x: { ticks: { color: textColor, font: { size: 10 }, maxTicksLimit: 10 }, grid: { color: gridColor } },
                y: { ticks: { color: textColor, font: { size: 10 } }, grid: { color: gridColor }, beginAtZero: true },
            },
        },
    });
})();
</script>
@endpush
@endsection
