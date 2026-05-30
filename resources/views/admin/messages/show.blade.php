@extends('layouts.admin')
@section('title', 'Message from ' . $message->name)
@section('breadcrumb', 'Customers')

@section('content')
<div class="p-6 lg:p-8 max-w-3xl">

    {{-- Back --}}
    <div class="flex items-center gap-3 mb-7">
        <a href="{{ route('admin.messages.index') }}"
           class="flex items-center gap-2 text-xs tracking-wider uppercase transition-colors"
           style="color:var(--adm-muted);"
           onmouseover="this.style.color='var(--adm-gold)'" onmouseout="this.style.color='var(--adm-muted)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Messages
        </a>
    </div>

    @if(session('success'))
    <div class="mb-5 px-4 py-3 text-sm flex items-center gap-3"
         style="background:var(--adm-success-bg);border:1px solid var(--adm-success-fg);color:var(--adm-success-fg);">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-5 px-4 py-3 text-sm" style="background:var(--adm-danger-bg);border:1px solid var(--adm-danger-fg);color:var(--adm-danger-fg);">
        {{ session('error') }}
    </div>
    @endif

    {{-- Message card --}}
    <div class="adm-card overflow-hidden mb-5">

        {{-- Header --}}
        <div class="px-6 py-5 flex items-start justify-between gap-4"
             style="border-bottom:1px solid var(--adm-border);background:var(--adm-surface-alt);">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-full flex items-center justify-center text-base font-semibold flex-shrink-0"
                     style="background:rgba(55,18,32,0.2);color:var(--adm-gold);">
                    {{ strtoupper(substr($message->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-base font-semibold" style="color:var(--adm-text-strong);">{{ $message->name }}</p>
                    <a href="mailto:{{ $message->email }}"
                       class="text-sm transition-colors"
                       style="color:var(--adm-gold);"
                       onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        {{ $message->email }}
                    </a>
                    @if($message->phone)
                    <p class="text-sm mt-0.5" style="color:var(--adm-muted);">{{ $message->phone }}</p>
                    @endif
                </div>
            </div>
            <div class="text-right shrink-0">
                <p class="text-xs" style="color:var(--adm-muted);">{{ $message->created_at->format('M j, Y \a\t g:i A') }}</p>
                <p class="text-[10px] mt-1" style="color:var(--adm-muted);opacity:0.6;">{{ $message->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Subject --}}
        @if($message->subject)
        <div class="px-6 py-3" style="border-bottom:1px solid var(--adm-border);">
            <p class="text-[10px] tracking-widest uppercase mb-1" style="color:var(--adm-muted);">Subject</p>
            <p class="text-sm font-semibold" style="color:var(--adm-text-strong);">{{ $message->subject }}</p>
        </div>
        @endif

        {{-- Body --}}
        <div class="px-6 py-6">
            <p class="text-[10px] tracking-widest uppercase mb-3" style="color:var(--adm-muted);">Message</p>
            <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--adm-text);">{{ $message->message }}</div>
        </div>
    </div>

    {{-- Reply section --}}
    <div class="adm-card overflow-hidden" x-data="{ open: false }">
        <div class="px-6 py-4 flex items-center justify-between cursor-pointer"
             style="border-bottom:1px solid var(--adm-border);background:var(--adm-surface-alt);"
             @click="open = !open">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                <p class="text-sm font-semibold" style="color:var(--adm-text-strong);">Reply to {{ $message->name }}</p>
            </div>
            <svg class="w-4 h-4 transition-transform duration-200"
                 :class="open ? 'rotate-180' : ''"
                 style="color:var(--adm-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <div x-show="open" x-transition style="display:none;">
            <form method="POST" action="{{ route('admin.messages.reply', $message) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] tracking-widest uppercase mb-2" style="color:var(--adm-muted);">Subject</label>
                    <input type="text" name="reply_subject"
                           value="Re: {{ $message->subject ?? 'Your enquiry' }}"
                           required
                           class="w-full px-4 py-2.5 text-sm"
                           style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);color:var(--adm-text);"
                           onfocus="this.style.borderColor='var(--adm-gold)'" onblur="this.style.borderColor='var(--adm-border)'">
                </div>
                <div>
                    <label class="block text-[10px] tracking-widest uppercase mb-2" style="color:var(--adm-muted);">Message</label>
                    <textarea name="reply_body" rows="6" required
                              placeholder="Write your reply here…"
                              class="w-full px-4 py-2.5 text-sm resize-none"
                              style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);color:var(--adm-text);"
                              onfocus="this.style.borderColor='var(--adm-gold)'" onblur="this.style.borderColor='var(--adm-border)'"></textarea>
                </div>
                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                            class="px-6 py-2.5 text-xs tracking-widest uppercase font-medium transition-opacity hover:opacity-85 flex items-center gap-2"
                            style="background:var(--adm-accent);color:#FFFFFF;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Reply
                    </button>
                    <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . ($message->subject ?? 'Your enquiry')) }}"
                       class="px-6 py-2.5 text-xs tracking-widest uppercase transition-colors"
                       style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                       onmouseover="this.style.borderColor='var(--adm-gold)';this.style.color='var(--adm-gold)'"
                       onmouseout="this.style.borderColor='var(--adm-border)';this.style.color='var(--adm-muted)'">
                        Open in Email Client
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
