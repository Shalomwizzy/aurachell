@extends('layouts.admin')
@section('title', $user->name)
@section('breadcrumb', 'Customers')

@section('content')
<div class="p-6 lg:p-8 max-w-5xl">

    <div class="flex items-center justify-between gap-3 mb-8 flex-wrap">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="transition-opacity hover:opacity-60" style="color:var(--adm-muted);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold" style="color:var(--adm-text);">{{ $user->name }}</h1>
                <p class="text-sm mt-0.5" style="color:var(--adm-muted);">Customer since {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>
        <button onclick="document.getElementById('email-modal').style.display='flex'"
                class="flex items-center gap-2 px-4 py-2 text-xs tracking-[0.15em] uppercase font-medium transition-all hover:opacity-90"
                style="background:#6B2016;color:#F5EDE4;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Send Email
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(107,32,22,0.10);border:1px solid rgba(107,32,22,0.25);color:#6B2016;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#b91c1c;">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Profile --}}
        <div class="space-y-4">
            <div class="p-6" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <div class="flex flex-col items-center text-center mb-5">
                    @if($user->avatar)
                    <img src="{{ $user->avatar_url }}" alt="" class="w-16 h-16 rounded-full object-cover mb-3">
                    @else
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-xl font-semibold mb-3"
                         style="background:rgba(107,32,22,0.25);color:var(--adm-gold);">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    @endif
                    <p class="font-medium" style="color:var(--adm-text);">{{ $user->name }}</p>
                    <p class="text-xs mt-0.5" style="color:var(--adm-muted);">{{ $user->email }}</p>
                </div>
                <div class="space-y-3 text-sm">
                    @if($user->phone)
                    <div class="flex justify-between">
                        <span style="color:var(--adm-muted);">Phone</span>
                        <span style="color:var(--adm-text);">{{ $user->phone }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span style="color:var(--adm-muted);">Verified</span>
                        <span style="color:{{ $user->email_verified_at ? 'var(--adm-success-fg)' : 'var(--adm-danger-fg)' }}">
                            {{ $user->email_verified_at ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($user->is_blocked ?? false)
                    <div class="flex justify-between">
                        <span style="color:var(--adm-muted);">Status</span>
                        <span style="color:var(--adm-danger-fg);">Blocked</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="p-4 text-center" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                    <p class="text-xl font-semibold" style="color:var(--adm-text);">{{ $user->orders_count }}</p>
                    <p class="text-[10px] tracking-wider uppercase mt-1" style="color:var(--adm-muted);">Orders</p>
                </div>
                <div class="p-4 text-center" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                    <p class="text-xl font-semibold" style="color:var(--adm-text);">₦{{ number_format($user->orders_sum_total ?? 0) }}</p>
                    <p class="text-[10px] tracking-wider uppercase mt-1" style="color:var(--adm-muted);">Spent</p>
                </div>
            </div>

            {{-- Addresses --}}
            @if($user->addresses->count())
            <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <p class="text-[10px] tracking-[0.2em] uppercase mb-4" style="color:var(--adm-muted);">Saved Addresses</p>
                @foreach($user->addresses as $address)
                <div class="text-sm leading-relaxed {{ !$loop->last ? 'mb-3 pb-3' : '' }}"
                     style="{{ !$loop->last ? 'border-bottom:1px solid var(--adm-border);' : '' }}color:var(--adm-text);">
                    @if($address->is_default)
                    <span class="text-[10px] tracking-wider" style="color:var(--adm-gold);">Default</span><br>
                    @endif
                    {{ $address->line1 }}, {{ $address->city }}, {{ $address->state }}
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Right: Orders + Reviews --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Orders --}}
            <div style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <div class="px-5 py-4" style="border-bottom:1px solid var(--adm-border);">
                    <p class="text-sm font-medium" style="color:var(--adm-text);">Order History</p>
                </div>
                @forelse($user->orders as $order)
                <div class="px-5 py-4 flex items-center justify-between transition-colors"
                     style="border-bottom:1px solid var(--adm-border);"
                     onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background='transparent'">
                    <div>
                        <p class="text-sm font-medium" style="color:var(--adm-text);">{{ $order->order_number }}</p>
                        <p class="text-xs mt-0.5" style="color:var(--adm-muted);">
                            {{ $order->placed_at?->format('M j, Y') }} · {{ $order->items->count() }} item{{ $order->items->count() != 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm" style="color:var(--adm-text);">₦{{ number_format($order->total, 2) }}</p>
                        @php
                            $statusStyles = [
                                'delivered'  => 'background:var(--adm-success-bg);color:var(--adm-success-fg)',
                                'shipped'    => 'background:var(--adm-info-bg);color:var(--adm-info-fg)',
                                'cancelled'  => 'background:var(--adm-danger-bg);color:var(--adm-danger-fg)',
                            ];
                            $ss = $statusStyles[$order->status] ?? 'background:var(--adm-warn-bg);color:var(--adm-warn-fg)';
                        @endphp
                        <span class="text-[10px] px-2 py-0.5 mt-1 inline-block tracking-wider uppercase" style="{{ $ss }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center text-sm" style="color:var(--adm-muted);">No orders yet.</div>
                @endforelse
            </div>

            {{-- Reviews --}}
            @if($user->reviews->count())
            <div style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <div class="px-5 py-4" style="border-bottom:1px solid var(--adm-border);">
                    <p class="text-sm font-medium" style="color:var(--adm-text);">Reviews ({{ $user->reviews->count() }})</p>
                </div>
                @foreach($user->reviews as $review)
                <div class="px-5 py-4" style="border-bottom:1px solid var(--adm-border);">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm" style="color:var(--adm-text);">{{ $review->product?->name }}</p>
                        <div class="flex gap-0.5">
                            @for($i=1;$i<=5;$i++)
                            <span class="text-xs" style="color:{{ $i<=$review->rating ? 'var(--adm-gold)' : 'var(--adm-border)' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    @if($review->body)
                    <p class="text-sm leading-relaxed" style="color:var(--adm-muted);">{{ $review->body }}</p>
                    @endif
                    <p class="text-xs mt-2" style="color:var(--adm-muted);">
                        {{ $review->created_at->format('M j, Y') }} ·
                        <span style="color:{{ $review->is_approved ? 'var(--adm-success-fg)' : 'var(--adm-warn-fg)' }}">
                            {{ $review->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </p>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Send Email Modal --}}
<div id="email-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;background:rgba(0,0,0,0.65);">
    <div class="w-full max-w-lg" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid var(--adm-border);">
            <div>
                <h2 class="text-base font-semibold" style="color:var(--adm-text);">Send Email</h2>
                <p class="text-xs mt-0.5" style="color:var(--adm-muted);">To: {{ $user->name }} &lt;{{ $user->email }}&gt;</p>
            </div>
            <button onclick="document.getElementById('email-modal').style.display='none'" style="color:var(--adm-muted);" class="hover:opacity-70 transition-opacity">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.customers.email', $user) }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-1.5" style="color:var(--adm-muted);">Subject</label>
                <input type="text" name="subject" required maxlength="200" placeholder="e.g. A special gift just for you"
                       class="adm-input w-full">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-1.5" style="color:var(--adm-muted);">Message</label>
                <textarea name="body" required maxlength="5000" rows="7"
                          placeholder="Write your personal message here..."
                          class="adm-input w-full resize-none" style="font-size:13px;line-height:1.6;"></textarea>
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-1.5" style="color:var(--adm-muted);">Coupon Code <span style="color:var(--adm-muted);text-transform:none;letter-spacing:0;">(optional — will be displayed prominently)</span></label>
                <input type="text" name="coupon_code" maxlength="50" placeholder="e.g. LOYAL20"
                       class="adm-input w-full" style="text-transform:uppercase;"
                       oninput="this.value = this.value.toUpperCase()">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-2.5 text-xs tracking-[0.2em] uppercase font-medium transition-all hover:opacity-90"
                        style="background:#6B2016;color:#F5EDE4;">
                    Send Email
                </button>
                <button type="button"
                        onclick="document.getElementById('email-modal').style.display='none'"
                        class="flex-1 py-2.5 text-xs tracking-[0.2em] uppercase font-medium transition-colors"
                        style="border:1px solid var(--adm-border);color:var(--adm-text);"
                        onmouseover="this.style.borderColor='var(--adm-gold)'"
                        onmouseout="this.style.borderColor='var(--adm-border)'">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
