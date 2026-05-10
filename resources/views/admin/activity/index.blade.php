@extends('layouts.admin')
@section('title', 'Activity Log')
@section('breadcrumb', 'System')

@section('content')
<div class="p-6 lg:p-8">

    <div class="mb-6 flex items-end justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">Activity Log</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">Audit trail of admin and customer actions</p>
        </div>
        <form method="POST" action="{{ route('admin.activity.clear') }}"
              onsubmit="return confirm('Clear all activity logs? This cannot be undone.');">
            @csrf @method('DELETE')
            <button type="submit"
                    class="px-5 py-2.5 text-xs tracking-[0.15em] uppercase font-medium transition-all hover:opacity-90"
                    style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.25);color:#b91c1c;">
                Clear All Logs
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(107,32,22,0.10);border:1px solid rgba(107,32,22,0.25);color:#6B2016;">
        {{ session('success') }}
    </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Events', 'value' => number_format($totals['total']), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2'],
            ['label' => 'Today',        'value' => number_format($totals['today']), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'This Week',    'value' => number_format($totals['this_week']), 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['label' => 'This Month',   'value' => number_format($totals['this_month']), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ] as $kpi)
        <div class="adm-card p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] tracking-[0.15em] uppercase" style="color:var(--adm-muted);">{{ $kpi['label'] }}</span>
                <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $kpi['icon'] }}"/></svg>
            </div>
            <p class="text-xl font-semibold" style="color:var(--adm-text-strong);">{{ $kpi['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="adm-card p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-1.5" style="color:var(--adm-muted);">Action</label>
                <input type="text" name="action" value="{{ request('action') }}" placeholder="e.g. created" class="adm-input">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-1.5" style="color:var(--adm-muted);">User</label>
                <select name="user_id" class="adm-input">
                    <option value="">All users</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id')==$u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-1.5" style="color:var(--adm-muted);">From</label>
                <input type="date" name="from" value="{{ request('from') }}" class="adm-input">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-1.5" style="color:var(--adm-muted);">To</label>
                <input type="date" name="to" value="{{ request('to') }}" class="adm-input">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 text-xs tracking-[0.2em] uppercase font-medium" style="background:var(--adm-accent);color:#F5EDE4;">Filter</button>
                <a href="{{ route('admin.activity.index') }}"
                   class="flex-1 px-4 py-2.5 text-xs tracking-[0.2em] uppercase font-medium text-center transition-colors"
                   style="border:1px solid var(--adm-border);color:var(--adm-text);"
                   onmouseover="this.style.borderColor='var(--adm-gold)';this.style.color='var(--adm-gold)'"
                   onmouseout="this.style.borderColor='var(--adm-border)';this.style.color='var(--adm-text)'">
                    ✕ Reset
                </a>
            </div>
        </div>
    </form>

    {{-- Log table --}}
    <div class="adm-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:var(--adm-surface-alt);">
                        <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">When</th>
                        <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">User</th>
                        <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Action</th>
                        <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">Target</th>
                        <th class="text-left px-5 py-3 text-[10px] tracking-[0.15em] uppercase font-medium" style="color:var(--adm-muted);">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="border-t" style="border-color:var(--adm-border);">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span style="color:var(--adm-text);">{{ $log->created_at->diffForHumans() }}</span>
                            <p class="text-[10px]" style="color:var(--adm-muted);">{{ $log->created_at->format('d M Y · H:i') }}</p>
                        </td>
                        <td class="px-5 py-3">
                            @if($log->user)
                            <span style="color:var(--adm-text);">{{ $log->user->name }}</span>
                            <p class="text-[10px]" style="color:var(--adm-muted);">{{ $log->user->email }}</p>
                            @else
                            <span style="color:var(--adm-muted);">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] tracking-wider uppercase rounded"
                                  style="background:rgba(107,32,22,0.18);color:var(--adm-gold);">{{ $log->action }}</span>
                        </td>
                        <td class="px-5 py-3" style="color:var(--adm-text);">
                            @if($log->model_type)
                            {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            @else
                            <span style="color:var(--adm-muted);">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-[11px] font-mono" style="color:var(--adm-muted);">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm" style="color:var(--adm-muted);">
                            No activity recorded yet. Events will appear here as actions are taken.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($logs->hasPages())
    <div class="mt-5">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
