@extends('layouts.admin')
@section('title', 'Reviews')
@section('breadcrumb', 'Catalog')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">Reviews</h1>
            @if($pendingCount > 0)
            <p class="text-sm mt-1" style="color:var(--adm-warn-fg);">{{ $pendingCount }} pending approval</p>
            @else
            <p class="text-sm mt-1" style="color:var(--adm-muted);">All reviews moderated</p>
            @endif
        </div>
        <div class="flex gap-2">
            @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved'] as $val=>$label)
            @php
                $isActive = $val === 'all'
                    ? !request('status')
                    : request('status') === $val;
            @endphp
            <a href="{{ route('admin.reviews.index', $val !== 'all' ? ['status'=>$val] : []) }}"
               class="px-4 py-2 text-xs tracking-wider uppercase transition-colors"
               style="{{ $isActive ? 'border:1px solid var(--adm-gold);color:var(--adm-gold);' : 'border:1px solid var(--adm-border);color:var(--adm-muted);' }}">
                {{ $label }}
                @if($val === 'pending' && $pendingCount > 0)
                <span class="ml-1 px-1.5 py-0.5 text-[9px] rounded-full"
                      style="background:var(--adm-warn-bg);color:var(--adm-warn-fg);">{{ $pendingCount }}</span>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-6 flex gap-3">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by customer name or product…"
               class="flex-1 px-4 py-2.5 text-sm"
               style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);color:var(--adm-text);"
               onfocus="this.style.borderColor='var(--adm-gold)'" onblur="this.style.borderColor='var(--adm-border)'">
        <button class="px-5 py-2.5 text-xs tracking-wider uppercase transition-colors"
                style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                onmouseover="this.style.borderColor='var(--adm-gold)';this.style.color='var(--adm-gold)'"
                onmouseout="this.style.borderColor='var(--adm-border)';this.style.color='var(--adm-muted)'">
            Search
        </button>
    </form>

    <div class="space-y-3">
        @forelse($reviews as $review)
        <div class="adm-card p-5 overflow-hidden"
             style="{{ !$review->is_approved ? 'border-left:3px solid var(--adm-warn-fg);' : '' }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4 flex-1 min-w-0">

                    {{-- Avatar --}}
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5"
                         style="background:rgba(107,32,22,0.2);color:var(--adm-gold);">
                        {{ strtoupper(substr($review->user?->name ?? 'G', 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        {{-- Meta row --}}
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-2">
                            <p class="text-sm font-semibold" style="color:var(--adm-text-strong);">{{ $review->user?->name ?? 'Guest' }}</p>
                            <span style="color:var(--adm-border);">·</span>
                            <p class="text-xs truncate max-w-[200px]" style="color:var(--adm-muted);">{{ $review->product?->name }}</p>
                            <span style="color:var(--adm-border);">·</span>
                            {{-- Stars --}}
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5" fill="{{ $i <= $review->rating ? 'var(--adm-gold)' : 'none' }}"
                                     stroke="var(--adm-gold)" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                @endfor
                            </div>
                            <span class="text-xs font-semibold" style="color:var(--adm-gold);">{{ $review->rating }}/5</span>
                        </div>

                        {{-- Review content --}}
                        @if($review->title)
                        <p class="text-sm font-semibold mb-1" style="color:var(--adm-text-strong);">{{ $review->title }}</p>
                        @endif
                        @if($review->body)
                        <p class="text-sm leading-relaxed" style="color:var(--adm-text);">{{ $review->body }}</p>
                        @endif

                        {{-- Date + status --}}
                        <div class="flex items-center gap-3 mt-3">
                            <p class="text-xs" style="color:var(--adm-muted);">{{ $review->created_at->format('M j, Y') }}</p>
                            @if(!$review->is_approved)
                            <span class="text-[9px] px-2 py-0.5 tracking-wider uppercase"
                                  style="background:var(--adm-warn-bg);color:var(--adm-warn-fg);">Pending</span>
                            @else
                            <span class="text-[9px] px-2 py-0.5 tracking-wider uppercase"
                                  style="background:var(--adm-success-bg);color:var(--adm-success-fg);">Approved</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1.5 text-xs tracking-wider uppercase transition-all"
                                style="{{ $review->is_approved
                                    ? 'border:1px solid var(--adm-border);color:var(--adm-muted);'
                                    : 'border:1px solid var(--adm-success-fg);color:var(--adm-success-fg);' }}"
                                onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                            {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                          onsubmit="return confirm('Delete this review permanently?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 text-xs tracking-wider uppercase transition-all"
                                style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                                onmouseover="this.style.borderColor='var(--adm-danger-fg)';this.style.color='var(--adm-danger-fg)'"
                                onmouseout="this.style.borderColor='var(--adm-border)';this.style.color='var(--adm-muted)'">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="adm-card p-16 text-center">
            <svg class="w-12 h-12 mx-auto mb-4" style="color:var(--adm-border);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <p class="text-sm" style="color:var(--adm-muted);">No reviews found.</p>
        </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
    <div class="mt-6">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
