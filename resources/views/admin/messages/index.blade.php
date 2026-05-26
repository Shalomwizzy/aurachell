@extends('layouts.admin')
@section('title', 'Messages')
@section('breadcrumb', 'Customers')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">Messages</h1>
            @if($unreadCount > 0)
            <p class="text-sm mt-1" style="color:var(--adm-warn-fg);">{{ $unreadCount }} unread {{ Str::plural('message', $unreadCount) }}</p>
            @else
            <p class="text-sm mt-1" style="color:var(--adm-muted);">All caught up</p>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.messages.index') }}"
               class="px-4 py-2 text-xs tracking-wider uppercase transition-colors"
               style="{{ !request('unread') ? 'border:1px solid var(--adm-gold);color:var(--adm-gold);' : 'border:1px solid var(--adm-border);color:var(--adm-muted);' }}">
                All
            </a>
            <a href="{{ route('admin.messages.index', ['unread'=>'1']) }}"
               class="px-4 py-2 text-xs tracking-wider uppercase transition-colors"
               style="{{ request('unread') ? 'border:1px solid var(--adm-gold);color:var(--adm-gold);' : 'border:1px solid var(--adm-border);color:var(--adm-muted);' }}">
                Unread @if($unreadCount > 0)<span class="ml-1 px-1.5 py-0.5 text-[9px] rounded-full" style="background:rgba(55,18,32,0.3);color:var(--adm-gold);">{{ $unreadCount }}</span>@endif
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-5 px-4 py-3 text-sm flex items-center gap-3"
         style="background:var(--adm-success-bg);border:1px solid var(--adm-success-fg);color:var(--adm-success-fg);">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="space-y-2">
        @forelse($messages as $message)
        <div class="adm-card overflow-hidden transition-all hover:border-[var(--adm-gold)]"
             style="{{ !$message->is_read ? 'border-left:3px solid var(--adm-gold);' : '' }}">
            <a href="{{ route('admin.messages.show', $message) }}" class="flex items-center gap-5 px-5 py-4">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-semibold shrink-0"
                     style="background:rgba(55,18,32,0.2);color:var(--adm-gold);">
                    {{ strtoupper(substr($message->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-sm font-semibold" style="color:{{ !$message->is_read ? 'var(--adm-text-strong)' : 'var(--adm-text)' }};">
                            {{ $message->name }}
                        </p>
                        @if(!$message->is_read)
                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:var(--adm-gold);"></span>
                        @endif
                    </div>
                    <p class="text-xs truncate" style="color:var(--adm-muted);">
                        {{ $message->email }}
                        @if($message->subject) <span class="mx-1">·</span> {{ $message->subject }} @endif
                    </p>
                    @if($message->message)
                    <p class="text-xs mt-1 truncate" style="color:var(--adm-muted);opacity:0.7;">
                        {{ Str::limit($message->message, 80) }}
                    </p>
                    @endif
                </div>
                <div class="text-right shrink-0 space-y-1">
                    <p class="text-xs" style="color:var(--adm-muted);">{{ $message->created_at->diffForHumans() }}</p>
                    @if(!$message->is_read)
                    <span class="text-[9px] px-2 py-0.5 tracking-wider uppercase"
                          style="background:var(--adm-warn-bg);color:var(--adm-warn-fg);">New</span>
                    @else
                    <span class="text-[9px] px-2 py-0.5 tracking-wider uppercase"
                          style="background:var(--adm-success-bg);color:var(--adm-success-fg);">Read</span>
                    @endif
                </div>
            </a>
        </div>
        @empty
        <div class="adm-card p-16 text-center">
            <svg class="w-12 h-12 mx-auto mb-4" style="color:var(--adm-border);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm" style="color:var(--adm-muted);">No messages yet.</p>
        </div>
        @endforelse
    </div>

    @if($messages->hasPages())
    <div class="mt-6">{{ $messages->links() }}</div>
    @endif
</div>
@endsection
