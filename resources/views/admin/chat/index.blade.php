@extends('layouts.admin')
@section('title', 'Chat Logs')
@section('breadcrumb', 'AI Studio')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text);">Chat Logs</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">Customer chatbot conversation history</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($total) }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">Total Sessions</p>
        </div>
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($totalMessages) }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">Total Messages</p>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-6 flex gap-3">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Search by user name, email or session ID…"
               class="flex-1 px-4 py-2.5 text-sm focus:outline-none"
               style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
        <button type="submit" class="px-5 py-2.5 text-xs tracking-wider uppercase transition-colors"
                style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
            Search
        </button>
        @if(request('q'))
        <a href="{{ route('admin.chat.index') }}" class="px-5 py-2.5 text-xs tracking-wider uppercase"
           style="border:1px solid var(--adm-border);color:var(--adm-muted);">Clear</a>
        @endif
    </form>

    {{-- Sessions table --}}
    <div style="border:1px solid var(--adm-border);overflow:hidden;">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:var(--adm-surface-alt);border-bottom:1px solid var(--adm-border);">
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">User</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Session ID</th>
                    <th class="text-center px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Messages</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden lg:table-cell" style="color:var(--adm-muted);">Started</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr style="border-top:1px solid var(--adm-border);" class="transition-colors"
                    onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background='transparent'">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold shrink-0"
                                 style="background:rgba(107,32,22,0.25);color:var(--adm-gold);">
                                {{ strtoupper(substr($session->user?->name ?? 'G', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-sm" style="color:var(--adm-text);">
                                    {{ $session->user?->name ?? 'Guest' }}
                                </p>
                                @if($session->user)
                                <p class="text-xs" style="color:var(--adm-muted);">{{ $session->user->email }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="text-xs font-mono" style="color:var(--adm-muted);">{{ Str::limit($session->session_id, 20) }}</span>
                    </td>
                    <td class="px-5 py-4 text-center" style="color:var(--adm-text);">
                        <span class="text-sm font-medium">{{ $session->messages_count }}</span>
                    </td>
                    <td class="px-5 py-4 text-xs hidden lg:table-cell" style="color:var(--adm-muted);">
                        {{ $session->created_at->format('M j, Y · g:i a') }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.chat.show', $session) }}"
                           class="text-xs transition-opacity hover:opacity-70" style="color:var(--adm-gold);">View →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center text-sm" style="color:var(--adm-muted);">
                        No chat sessions yet. Conversations appear here once customers use the chatbot.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if($sessions->hasPages())
    <div class="mt-6">{{ $sessions->links() }}</div>
    @endif

</div>
@endsection
