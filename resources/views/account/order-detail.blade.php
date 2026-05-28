@extends('layouts.account')
@section('title', 'Order ' . $order->order_number)
@section('account-content')

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <a href="{{ route('account.orders') }}" class="text-xs font-sans text-text-muted hover:text-sage transition-colors">&larr; Back to orders</a>
        <h1 class="font-display text-2xl text-text-dark mt-1">{{ $order->order_number }}</h1>
        <p class="text-xs text-text-muted font-sans">Placed {{ $order->created_at->format('d M Y, g:ia') }}</p>
    </div>
    @php
        $statusClass = match($order->status) {
            'delivered'        => 'bg-mahogany/15 text-mahogany',
            'shipped'          => 'bg-sand/20 text-text-muted',
            'out_for_delivery' => 'bg-sand/20 text-text-muted',
            'cancelled'        => 'bg-mahogany/10 text-mahogany',
            'refunded'         => 'bg-mahogany/10 text-mahogany',
            default            => 'bg-sand/30 text-sage',
        };
    @endphp
    <div class="flex items-center gap-3">
        <span class="badge {{ $statusClass }} text-[10px]">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
        <a href="{{ route('account.order.invoice', $order->order_number) }}" class="btn-ghost text-xs py-2 px-4">
            Download Invoice
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left column: items + totals --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Items --}}
        <div class="bg-white shadow-luxury p-6">
            <h2 class="font-display text-lg mb-4">Items Ordered</h2>
            @foreach($order->items as $item)
            <div class="flex items-center gap-4 py-4 border-b border-sand/30 last:border-0">
                @if($item->product)
                <a href="{{ route('product.show', $item->product->slug) }}" class="shrink-0">
                    <img src="{{ $item->product->primaryImageUrl }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover">
                </a>
                @else
                <div class="w-16 h-16 bg-sand/20 shrink-0"></div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-sans text-sm font-medium text-text-dark">{{ $item->product_name }}</p>
                    @if($item->variant_name)
                    <p class="text-xs text-text-muted font-sans">{{ $item->variant_name }}</p>
                    @endif
                    <p class="text-xs text-text-muted font-sans mt-0.5">Qty: {{ $item->quantity }} × ₦{{ number_format($item->unit_price, 0) }}</p>
                </div>
                <p class="font-sans text-sm font-semibold text-text-dark shrink-0">₦{{ number_format($item->total_price, 0) }}</p>
            </div>
            @endforeach

            {{-- Totals --}}
            <div class="mt-4 pt-4 space-y-2">
                <div class="flex justify-between text-sm font-sans">
                    <span class="text-text-muted">Subtotal</span>
                    <span class="text-text-dark">₦{{ number_format($order->subtotal, 0) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-between text-sm font-sans">
                    <span class="text-text-muted">Discount@if($order->coupon_code) ({{ $order->coupon_code }})@endif</span>
                    <span class="text-mahogany">−₦{{ number_format($order->discount, 0) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm font-sans">
                    <span class="text-text-muted">Shipping</span>
                    <span class="text-text-dark">{{ $order->shipping_fee > 0 ? '₦'.number_format($order->shipping_fee, 0) : 'Free' }}</span>
                </div>
                <div class="flex justify-between font-sans font-semibold text-base border-t border-sand/40 pt-2 mt-2">
                    <span class="text-text-dark">Total</span>
                    <span class="text-sage">₦{{ number_format($order->total, 0) }}</span>
                </div>
            </div>
        </div>

        {{-- Status history --}}
        @if($order->statusHistory->count())
        <div class="bg-white shadow-luxury p-6">
            <h2 class="font-display text-lg mb-4">Order History</h2>
            <div class="space-y-3">
                @foreach($order->statusHistory as $history)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-sage mt-1.5 shrink-0"></div>
                    <div>
                        <p class="text-sm font-sans font-medium text-text-dark">{{ ucfirst(str_replace('_', ' ', $history->status)) }}</p>
                        @if($history->note)
                        <p class="text-xs text-text-muted font-sans">{{ $history->note }}</p>
                        @endif
                        <p class="text-xs text-text-muted font-sans mt-0.5">{{ $history->created_at->format('d M Y, g:ia') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Return request --}}
    @if($order->status === 'delivered')
    @php
        $windowDays  = (int) (\App\Models\Setting::get('return_window_days') ?? 3);
        $deliveredAt = $order->delivered_at ?? $order->updated_at;
        $daysUsed    = (int) $deliveredAt->diffInDays(now());
        $windowOpen  = $windowDays === 0 || $daysUsed <= $windowDays;
        $windowCloseDate = $deliveredAt->copy()->addDays($windowDays);
    @endphp
    <div class="bg-white shadow-luxury p-6">
        <h2 class="font-display text-lg mb-1">Returns</h2>
        @if($order->returnRequest)
        @php $rr = $order->returnRequest; @endphp
        <p class="text-xs text-text-muted font-sans mb-4">Request submitted {{ $rr->created_at->format('d M Y') }}</p>
        <div class="flex items-center gap-3 mb-3">
            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $rr->statusColor() }}">
                {{ $rr->statusLabel() }}
            </span>
        </div>
        <p class="text-sm font-sans text-text-muted"><span class="font-medium text-text-dark">Reason:</span> {{ $rr->reason }}</p>
        @if($rr->details)
        <p class="text-sm font-sans text-text-muted mt-1">{{ $rr->details }}</p>
        @endif
        @if($rr->admin_notes)
        <div class="mt-3 p-3 rounded bg-sand/20 text-sm font-sans text-text-dark">
            <p class="text-[10px] uppercase tracking-widest text-text-muted mb-1">Note from us</p>
            {{ $rr->admin_notes }}
        </div>
        @endif
        @elseif(!$windowOpen)
        <p class="text-sm text-text-muted font-sans">The {{ $windowDays }}-day return window for this order closed on {{ $windowCloseDate->format('d M Y') }}.</p>
        @else
        <p class="text-sm text-text-muted font-sans mb-1">Not satisfied? You can request a return within {{ $windowDays }} days of delivery.</p>
        @if($windowDays > 0)
        <p class="text-xs text-text-muted font-sans mb-4">Window closes: <strong class="text-text-dark">{{ $windowCloseDate->format('d M Y') }}</strong></p>
        @endif
        @if(session('success'))
        <div class="mb-4 p-3 rounded text-sm font-sans text-mahogany bg-mahogany/10 border border-mahogany/30">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('account.returns.store', $order->order_number) }}" x-data="{ open: false }">
            @csrf
            <button type="button" @click="open = !open"
                    class="btn-ghost text-xs py-2 px-4 mb-4">
                Request a Return
            </button>
            <div x-show="open" x-transition class="space-y-4 pt-2">
                <div>
                    <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-2">Reason for Return *</label>
                    <select name="reason" required
                            class="w-full border border-sand/40 rounded-sm bg-white text-sm font-sans text-text-dark px-3 py-2.5 focus:outline-none focus:border-sage transition-colors">
                        <option value="">Select a reason…</option>
                        <option value="Damaged or defective product">Damaged or defective product</option>
                        <option value="Wrong item received">Wrong item received</option>
                        <option value="Product not as described">Product not as described</option>
                        <option value="Changed my mind">Changed my mind</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('reason')<p class="text-xs text-mahogany mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-2">Additional Details <span class="normal-case tracking-normal text-[9px]">(optional)</span></label>
                    <textarea name="details" rows="3" maxlength="1000" placeholder="Describe the issue…"
                              class="w-full border border-sand/40 rounded-sm bg-white text-sm font-sans text-text-dark px-3 py-2.5 focus:outline-none focus:border-sage transition-colors resize-none"></textarea>
                </div>
                <button type="submit" class="btn-primary text-xs py-2.5 px-6">Submit Request</button>
            </div>
        </form>
        @endif
    </div>
    @endif

    {{-- Right column: shipping + payment --}}
    <div class="space-y-4">

        {{-- Shipping address --}}
        @if($order->shipping_address)
        <div class="bg-white shadow-luxury p-5">
            <h3 class="font-display text-base mb-3">Shipping Address</h3>
            <p class="text-sm font-sans text-text-dark font-medium">{{ $order->shipping_address['full_name'] ?? $order->customer_name }}</p>
            <p class="text-sm font-sans text-text-muted leading-relaxed mt-1">
                {{ $order->shipping_address['address_line_1'] ?? '' }}
                @if(!empty($order->shipping_address['address_line_2'])), {{ $order->shipping_address['address_line_2'] }}@endif<br>
                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}<br>
                {{ $order->shipping_address['country'] ?? '' }}
            </p>
            @if(!empty($order->shipping_address['phone']))
            <p class="text-xs text-text-muted font-sans mt-2">{{ $order->shipping_address['phone'] }}</p>
            @endif
        </div>
        @endif

        {{-- Payment info --}}
        <div class="bg-white shadow-luxury p-5">
            <h3 class="font-display text-base mb-3">Payment</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm font-sans">
                    <span class="text-text-muted">Method</span>
                    <span class="text-text-dark capitalize">{{ $order->payment_method ?? 'Card' }}</span>
                </div>
                <div class="flex justify-between text-sm font-sans">
                    <span class="text-text-muted">Status</span>
                    <span class="{{ $order->payment_status === 'paid' ? 'text-mahogany' : 'text-mahogany' }} font-medium">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                @if($order->paid_at)
                <div class="flex justify-between text-sm font-sans">
                    <span class="text-text-muted">Paid on</span>
                    <span class="text-text-dark">{{ $order->paid_at->format('d M Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Tracking --}}
        @if($order->tracking_code)
        <div class="bg-white shadow-luxury p-5">
            <h3 class="font-display text-base mb-3">Tracking</h3>
            <p class="text-[10px] tracking-widest uppercase text-text-muted font-sans mb-1">Tracking Code</p>
            <p class="font-sans font-semibold text-text-dark">{{ $order->tracking_code }}</p>
        </div>
        @endif

    </div>
</div>
@endsection
