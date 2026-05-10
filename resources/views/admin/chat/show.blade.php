@extends('layouts.admin')
@section('title', 'Chat Session')
@section('breadcrumb', 'Chat Logs')

@section('content')
<div class="p-6 lg:p-8 max-w-3xl">

    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('admin.chat.index') }}" class="transition-opacity hover:opacity-60" style="color:var(--adm-muted);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-semibold" style="color:var(--adm-text);">
                {{ $session->user?->name ?? 'Guest' }} — Chat Session
            </h1>
            <p class="text-xs mt-0.5 font-mono" style="color:var(--adm-muted);">{{ $session->session_id }}</p>
        </div>
        <form action="{{ route('admin.chat.destroy', $session) }}" method="POST"
              onsubmit="return confirm('Delete this chat session?')">
            @csrf @method('DELETE')
            <button type="submit" class="px-3 py-1.5 text-xs tracking-wider uppercase transition-opacity hover:opacity-80"
                    style="border:1px solid var(--adm-danger-fg);color:var(--adm-danger-fg);">
                Delete
            </button>
        </form>
    </div>

    {{-- Session meta --}}
    <div class="p-4 mb-6 flex flex-wrap gap-6 text-sm" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
        <div>
            <p class="text-[10px] tracking-[0.2em] uppercase mb-1" style="color:var(--adm-muted);">User</p>
            <p style="color:var(--adm-text);">
                @if($session->user)
                <a href="{{ route('admin.customers.show', $session->user) }}" style="color:var(--adm-gold);" class="hover:opacity-80">
                    {{ $session->user->name }}
                </a> &mdash; {{ $session->user->email }}
                @else
                Guest (not logged in)
                @endif
            </p>
        </div>
        <div>
            <p class="text-[10px] tracking-[0.2em] uppercase mb-1" style="color:var(--adm-muted);">Started</p>
            <p style="color:var(--adm-text);">{{ $session->created_at->format('M j, Y · g:i a') }}</p>
        </div>
        <div>
            <p class="text-[10px] tracking-[0.2em] uppercase mb-1" style="color:var(--adm-muted);">Messages</p>
            <p style="color:var(--adm-text);">{{ $session->messages->count() }}</p>
        </div>
    </div>

    {{-- Messages --}}
    <div class="space-y-4">
        @forelse($session->messages as $msg)
        <div class="{{ $msg->role === 'user' ? 'flex justify-end' : 'flex justify-start' }}">
            <div class="max-w-[80%]">
                <p class="text-[10px] tracking-wider uppercase mb-1.5 {{ $msg->role === 'user' ? 'text-right' : '' }}"
                   style="color:var(--adm-muted);">
                    {{ $msg->role === 'user' ? 'Customer' : 'Aurachell AI' }}
                    &nbsp;·&nbsp;{{ $msg->created_at->format('g:i a') }}
                </p>
                <div class="px-4 py-3 text-sm leading-relaxed"
                     style="{{ $msg->role === 'user'
                         ? 'background:#6B2016;color:#F5EDE4;'
                         : 'background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);' }}">
                    {{ $msg->content }}
                </div>
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-sm" style="color:var(--adm-muted);">No messages in this session.</div>
        @endforelse
    </div>

</div>
@endsection
