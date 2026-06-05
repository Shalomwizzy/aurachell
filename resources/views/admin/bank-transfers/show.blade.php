@extends('layouts.admin')
@section('title', 'Bank Transfer — ' . $transfer->reference)

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.bank-transfers.index') }}" class="text-xs tracking-wider uppercase transition-colors" style="color:var(--adm-muted);">&larr; Bank Transfers</a>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 text-sm" style="background:rgba(201,169,111,0.12);border:1px solid rgba(201,169,111,0.25);color:rgba(250,245,237,0.90);">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-6 px-4 py-3 text-sm" style="background:rgba(180,50,50,0.12);border:1px solid rgba(180,50,50,0.25);color:rgba(250,200,200,0.90);">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Order Info --}}
        <div class="adm-card p-6">
            <h3 class="text-sm font-semibold mb-4" style="color:var(--adm-text);">Order Details</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span style="color:var(--adm-muted);">Order Number</span><span style="color:var(--adm-text);">{{ $transfer->order->order_number }}</span></div>
                <div class="flex justify-between"><span style="color:var(--adm-muted);">Customer</span><span style="color:var(--adm-text);">{{ $transfer->order->user?->name ?? $transfer->order->guest_name ?? 'Guest' }}</span></div>
                <div class="flex justify-between"><span style="color:var(--adm-muted);">Email</span><span style="color:var(--adm-text);">{{ $transfer->order->user?->email ?? $transfer->order->guest_email }}</span></div>
                <div class="flex justify-between"><span style="color:var(--adm-muted);">Order Total</span><span style="color:#C9A96F;font-weight:600;">&#8358;{{ number_format($transfer->order->total, 2) }}</span></div>
                <div class="flex justify-between"><span style="color:var(--adm-muted);">Reference</span><span style="color:var(--adm-text);font-family:monospace;font-size:0.8em;">{{ $transfer->reference }}</span></div>
                <div class="flex justify-between"><span style="color:var(--adm-muted);">Submitted</span><span style="color:var(--adm-text);">{{ $transfer->submitted_at?->format('M d, Y H:i') ?? '—' }}</span></div>
            </div>
        </div>

        {{-- Proof of Payment --}}
        <div class="adm-card p-6">
            <h3 class="text-sm font-semibold mb-4" style="color:var(--adm-text);">Proof of Payment</h3>
            @if($transfer->proof_path)
                @php $ext = strtolower(pathinfo($transfer->proof_path, PATHINFO_EXTENSION)); @endphp
                @if(in_array($ext, ['jpg','jpeg','png','webp']))
                <div class="mb-4">
                    <img src="{{ route('admin.bank-transfers.proof', $transfer) }}" alt="Proof of payment"
                         class="max-w-full rounded" style="max-height:500px;border:1px solid var(--adm-border);">
                </div>
                @endif
                <a href="{{ route('admin.bank-transfers.proof', $transfer) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium transition-colors"
                   style="background:rgba(201,169,111,0.15);color:#C9A96F;border:1px solid rgba(201,169,111,0.25);">
                    View / Download Proof
                </a>
                @if($transfer->customer_note)
                <p class="mt-4 text-xs p-3" style="background:rgba(255,255,255,0.03);border:1px solid var(--adm-border);color:var(--adm-muted);">
                    <strong style="color:var(--adm-text);">Customer note:</strong> {{ $transfer->customer_note }}
                </p>
                @endif
            @else
            <p class="text-sm" style="color:var(--adm-muted);">No proof uploaded yet.</p>
            @endif
        </div>

        {{-- Order Items --}}
        <div class="adm-card p-6">
            <h3 class="text-sm font-semibold mb-4" style="color:var(--adm-text);">Order Items</h3>
            <div class="space-y-3">
                @foreach($transfer->order->items as $item)
                <div class="flex items-center gap-3 text-sm">
                    <div class="flex-1" style="color:var(--adm-text);">{{ $item->product?->name ?? 'Product' }}</div>
                    <div style="color:var(--adm-muted);">&times;{{ $item->quantity }}</div>
                    <div style="color:#C9A96F;">&#8358;{{ number_format($item->price * $item->quantity, 2) }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="space-y-6">

        {{-- Status --}}
        <div class="adm-card p-6">
            <h3 class="text-sm font-semibold mb-3" style="color:var(--adm-text);">Status</h3>
            @if($transfer->status === 'pending')
            <span class="px-3 py-1.5 text-xs tracking-wider uppercase" style="background:rgba(201,169,111,0.15);color:#C9A96F;">Pending Review</span>
            @elseif($transfer->status === 'approved')
            <span class="px-3 py-1.5 text-xs tracking-wider uppercase" style="background:rgba(50,180,100,0.15);color:rgba(100,220,140,0.90);">Approved</span>
            <p class="mt-3 text-xs" style="color:var(--adm-muted);">Reviewed by {{ $transfer->reviewer?->name }} on {{ $transfer->reviewed_at?->format('M d, Y H:i') }}</p>
            @else
            <span class="px-3 py-1.5 text-xs tracking-wider uppercase" style="background:rgba(180,50,50,0.15);color:rgba(220,100,100,0.90);">Rejected</span>
            @if($transfer->admin_note)
            <p class="mt-3 text-xs p-3" style="background:rgba(180,50,50,0.08);border:1px solid rgba(180,50,50,0.20);color:rgba(220,150,150,0.90);">{{ $transfer->admin_note }}</p>
            @endif
            <p class="mt-2 text-xs" style="color:var(--adm-muted);">Reviewed by {{ $transfer->reviewer?->name }} on {{ $transfer->reviewed_at?->format('M d, Y H:i') }}</p>
            @endif
        </div>

        {{-- Approve / Reject --}}
        @if($transfer->status === 'pending')
        <div class="adm-card p-6">
            <h3 class="text-sm font-semibold mb-4" style="color:var(--adm-text);">Review Payment</h3>

            {{-- Approve --}}
            <form action="{{ route('admin.bank-transfers.approve', $transfer) }}" method="POST" class="mb-4"
                  onsubmit="return confirm('Approve this payment and mark the order as paid?')">
                @csrf @method('PATCH')
                <button type="submit" class="w-full py-3 text-xs tracking-widest uppercase font-semibold transition-colors"
                        style="background:#C9A96F;color:#371220;">Approve Payment</button>
            </form>

            {{-- Reject --}}
            <form action="{{ route('admin.bank-transfers.reject', $transfer) }}" method="POST"
                  onsubmit="return confirm('Reject this payment? The customer will be notified.')">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label class="adm-label">Rejection reason (optional)</label>
                    <textarea name="admin_note" rows="2" placeholder="e.g. Amount transferred does not match order total."
                              class="adm-input w-full resize-none" style="font-size:0.8rem;"></textarea>
                </div>
                <button type="submit" class="w-full py-3 text-xs tracking-widest uppercase font-semibold transition-colors"
                        style="background:rgba(180,50,50,0.20);color:rgba(220,100,100,0.90);border:1px solid rgba(180,50,50,0.30);">Reject Payment</button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
