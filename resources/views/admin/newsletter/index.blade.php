@extends('layouts.admin')
@section('title', 'Newsletter')
@section('breadcrumb', 'Customers')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text);">Newsletter</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">{{ number_format($total) }} active subscribers</p>
        </div>
        <a href="{{ route('admin.newsletter.export') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-xs tracking-wider uppercase transition-opacity hover:opacity-80"
           style="background:var(--adm-accent);color:#FAF5ED;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export CSV
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-[10px] uppercase tracking-widest mb-1" style="color:var(--adm-muted);">Total</p>
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($total) }}</p>
        </div>
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-[10px] uppercase tracking-widest mb-1" style="color:var(--adm-muted);">This Month</p>
            @php $thisMonth = \App\Models\NewsletterSubscriber::whereMonth('created_at', now()->month)->count(); @endphp
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($thisMonth) }}</p>
        </div>
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-[10px] uppercase tracking-widest mb-1" style="color:var(--adm-muted);">This Week</p>
            @php $thisWeek = \App\Models\NewsletterSubscriber::where('created_at', '>=', now()->startOfWeek())->count(); @endphp
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($thisWeek) }}</p>
        </div>
    </div>

    {{-- Compose & Broadcast --}}
    <div class="mb-6 p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);"
         x-data="{ open: false, sending: false }">
        <button x-on:click="open = !open"
                class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-medium" style="color:var(--adm-gold);">Compose &amp; Broadcast Email</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" style="color:var(--adm-muted);"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-transition class="mt-5 pt-5" style="border-top:1px solid var(--adm-border);">
            <p class="text-xs mb-4" style="color:var(--adm-muted);">
                This will send the email to all <strong style="color:var(--adm-text);">{{ number_format($total) }}</strong> subscribers immediately.
            </p>
            <form action="{{ route('admin.newsletter.broadcast') }}" method="POST"
                  x-on:submit="sending = true">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="admin-label">Subject Line *</label>
                        <input type="text" name="subject" required maxlength="200"
                               value="{{ old('subject') }}"
                               placeholder="e.g. Exclusive: New Arrivals Just Dropped ✨"
                               class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Email Body *</label>
                        <textarea name="body" required rows="8" maxlength="10000"
                                  placeholder="Write your email message here…&#10;&#10;Plain text is fine — line breaks will be preserved."
                                  class="admin-input resize-y">{{ old('body') }}</textarea>
                        <p class="text-xs mt-1" style="color:var(--adm-muted);">Plain text only. Line breaks are preserved.</p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                                :disabled="sending"
                                :class="sending ? 'opacity-60 cursor-not-allowed' : ''"
                                class="flex items-center gap-2 px-5 py-2.5 text-xs tracking-wider uppercase font-medium transition-opacity hover:opacity-90"
                                style="background:#371220;color:#FAF5ED;"
                                x-on:click="return confirm('Send this newsletter to all {{ number_format($total) }} subscribers?')">
                            <svg x-show="!sending" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <svg x-show="sending" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span x-text="sending ? 'Sending…' : 'Send to All Subscribers'"></span>
                        </button>
                        <button type="button" x-on:click="open = false"
                                class="px-4 py-2.5 text-xs tracking-wider uppercase transition-opacity hover:opacity-70"
                                style="border:1px solid var(--adm-border);color:var(--adm-muted);">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-4 flex gap-3">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Search email or name…"
               class="flex-1 px-3 py-2 text-sm focus:outline-none"
               style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
        <button type="submit" class="px-4 py-2 text-sm transition-opacity hover:opacity-70"
                style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);color:var(--adm-muted);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
        @if(request('q'))
        <a href="{{ route('admin.newsletter.index') }}" class="px-4 py-2 text-xs tracking-wider uppercase"
           style="border:1px solid var(--adm-border);color:var(--adm-muted);">Clear</a>
        @endif
    </form>

    {{-- Subscribers table --}}
    <div style="border:1px solid var(--adm-border);overflow:hidden;">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:var(--adm-surface-alt);border-bottom:1px solid var(--adm-border);">
                    <th class="text-left px-5 py-3 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Email</th>
                    <th class="text-left px-5 py-3 text-[10px] tracking-[0.2em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Name</th>
                    <th class="text-left px-5 py-3 text-[10px] tracking-[0.2em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Subscribed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                <tr style="border-top:1px solid var(--adm-border);" class="transition-colors"
                    onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background='transparent'">
                    <td class="px-5 py-3.5" style="color:var(--adm-text);">{{ $sub->email }}</td>
                    <td class="px-5 py-3.5 hidden md:table-cell" style="color:var(--adm-muted);">{{ $sub->name ?? '—' }}</td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-xs" style="color:var(--adm-muted);">
                        {{ $sub->created_at->format('d M Y') }}
                        <span class="ml-1 opacity-60">{{ $sub->created_at->diffForHumans() }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-5 py-16 text-center text-sm" style="color:var(--adm-muted);">
                        No subscribers yet. Newsletter sign-ups from the footer will appear here.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subscribers->hasPages())
    <div class="mt-4">{{ $subscribers->links() }}</div>
    @endif

</div>
@endsection
