@extends('layouts.admin')
@section('title', 'Backups')
@section('breadcrumb', 'System')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Database Backups</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">Stored locally · automatic daily at 2 AM</p>
    </div>
    <form method="POST" action="{{ route('admin.backups.run') }}">
        @csrf
        <button type="submit"
                onclick="this.disabled=true; this.textContent='Running…'; this.form.submit();"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs tracking-widest uppercase font-medium transition-opacity hover:opacity-80"
                style="background:#371220;color:#FFFFFF;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Run Backup Now
        </button>
    </form>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 text-sm" style="background:rgba(201,169,111,0.10);color:rgba(247,242,235,0.85);border:1px solid rgba(201,169,111,0.25);">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-6 px-4 py-3 text-sm" style="background:rgba(201,169,111,0.08);color:rgba(247,242,235,0.85);border:1px solid rgba(201,169,111,0.20);">{{ session('error') }}</div>
@endif

@if(empty($backups))
<div class="text-center py-20" style="color:var(--adm-muted);">
    <svg class="w-12 h-12 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
    </svg>
    <p class="text-sm">No backups yet. Click "Run Backup Now" to create your first one.</p>
</div>
@else
<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr style="border-bottom:1px solid var(--adm-border);">
                <th class="px-5 py-3 text-left text-xs tracking-widest uppercase font-medium" style="color:var(--adm-muted);">File</th>
                <th class="px-5 py-3 text-center text-xs tracking-widest uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Size</th>
                <th class="px-5 py-3 text-center text-xs tracking-widest uppercase font-medium" style="color:var(--adm-muted);">Created</th>
                <th class="px-5 py-3 text-right text-xs tracking-widest uppercase font-medium" style="color:var(--adm-muted);">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y" style="border-color:var(--adm-border);">
            @foreach($backups as $backup)
            <tr class="transition-colors hover:bg-white/[0.02]">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/>
                        </svg>
                        <span class="text-white font-mono text-xs">{{ $backup['filename'] }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 text-center hidden md:table-cell" style="color:var(--adm-muted);">
                    {{ $backup['size'] }}
                </td>
                <td class="px-5 py-4 text-center" style="color:var(--adm-muted);">
                    <span title="{{ $backup['date']->format('Y-m-d H:i:s') }}">
                        {{ $backup['date']->diffForHumans() }}
                    </span>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.backups.download', ['file' => $backup['filename']]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs transition-colors"
                           style="background:rgba(201,169,111,0.10);color:#C9A96F;">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                        <form method="POST" action="{{ route('admin.backups.destroy', ['file' => $backup['filename']]) }}"
                              onsubmit="return confirm('Delete this backup?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs" style="background:rgba(55,18,32,0.10);color:rgba(250,245,237,0.80);">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<p class="mt-6 text-xs" style="color:var(--adm-muted);">
    Backups are stored in <code>storage/app/{{ config('app.name') }}/</code>.
    Retention: 3 daily · 4 weekly · 3 monthly.
    Only DB is backed up (not files).
</p>
@endif
@endsection
