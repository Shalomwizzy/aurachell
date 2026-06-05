@extends('layouts.admin')
@section('title', 'Bank Transfers')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl" style="color:var(--adm-text);">Bank Transfers</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">Review and approve customer bank transfer payments</p>
    </div>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 text-sm" style="background:rgba(201,169,111,0.12);border:1px solid rgba(201,169,111,0.25);color:rgba(250,245,237,0.90);">{{ session('success') }}</div>
@endif

{{-- Status Tabs --}}
<div class="flex gap-1 mb-6 border-b" style="border-color:var(--adm-border);">
    @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $key=>$label)
    <a href="{{ route('admin.bank-transfers.index', ['status'=>$key]) }}"
       class="px-4 py-2.5 text-xs tracking-wider uppercase transition-colors"
       style="{{ $status===$key ? 'color:#C9A96F;border-bottom:2px solid #C9A96F;' : 'color:rgba(250,245,237,0.45);' }}">
        {{ $label }}
        <span class="ml-1.5 px-1.5 py-0.5 text-[10px] rounded"
              style="background:rgba(201,169,111,0.15);color:rgba(250,245,237,0.60);">{{ $counts[$key] }}</span>
    </a>
    @endforeach
</div>

<div class="adm-card overflow-hidden">
    <table class="w-full">
        <thead>
            <tr style="border-bottom:1px solid var(--adm-border);">
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase" style="color:var(--adm-muted);">Order</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase hidden sm:table-cell" style="color:var(--adm-muted);">Customer</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase" style="color:var(--adm-muted);">Amount</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase hidden md:table-cell" style="color:var(--adm-muted);">Submitted</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase" style="color:var(--adm-muted);">Status</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase" style="color:var(--adm-muted);">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transfers as $transfer)
            <tr style="border-bottom:1px solid var(--adm-border);" class="transition-colors hover:bg-white/[0.02]">
                <td class="px-5 py-4">
                    <p class="text-sm font-medium" style="color:var(--adm-text);">{{ $transfer->order->order_number }}</p>
                    <p class="text-xs" style="color:var(--adm-muted);">{{ $transfer->reference }}</p>
                </td>
                <td class="px-5 py-4 hidden sm:table-cell">
                    <p class="text-sm" style="color:var(--adm-text);">{{ $transfer->order->user?->name ?? $transfer->order->guest_name ?? 'Guest' }}</p>
                    <p class="text-xs" style="color:var(--adm-muted);">{{ $transfer->order->user?->email ?? $transfer->order->guest_email }}</p>
                </td>
                <td class="px-5 py-4 text-right">
                    <span class="text-sm font-medium" style="color:#C9A96F;">&#8358;{{ number_format($transfer->amount, 2) }}</span>
                </td>
                <td class="px-5 py-4 text-center hidden md:table-cell">
                    <span class="text-xs" style="color:var(--adm-muted);">{{ $transfer->submitted_at?->format('M d, Y') ?? '—' }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    @if($transfer->status === 'pending')
                    <span class="px-2 py-1 text-[10px] tracking-wider uppercase" style="background:rgba(201,169,111,0.15);color:#C9A96F;">Pending</span>
                    @elseif($transfer->status === 'approved')
                    <span class="px-2 py-1 text-[10px] tracking-wider uppercase" style="background:rgba(50,180,100,0.15);color:rgba(100,220,140,0.90);">Approved</span>
                    @else
                    <span class="px-2 py-1 text-[10px] tracking-wider uppercase" style="background:rgba(180,50,50,0.15);color:rgba(220,100,100,0.90);">Rejected</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.bank-transfers.show', $transfer) }}"
                       class="px-3 py-1.5 text-xs font-medium transition-colors"
                       style="background:rgba(201,169,111,0.18);color:rgba(250,245,237,0.90);border:1px solid rgba(201,169,111,0.30);">Review</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center" style="color:var(--adm-muted);">No bank transfers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($transfers->hasPages())
    <div class="px-5 py-4" style="border-top:1px solid var(--adm-border);">{{ $transfers->links() }}</div>
    @endif
</div>
@endsection
