@extends('layouts.admin')
@section('title', 'Customers')
@section('breadcrumb', 'Customers')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text);">Customers</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">{{ number_format($stats['total']) }} registered customers</p>
        </div>
        <a href="{{ route('admin.reports.index') }}"
           class="px-4 py-2 text-xs tracking-wider uppercase transition-colors"
           style="border:1px solid var(--adm-border);color:var(--adm-muted);"
           onmouseover="this.style.color='var(--adm-text)';this.style.borderColor='var(--adm-gold)'"
           onmouseout="this.style.color='var(--adm-muted)';this.style.borderColor='var(--adm-border)'">
            View Reports
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        @foreach([['Total Customers',$stats['total']],['New Today',$stats['today']],['This Month',$stats['this_month']]] as [$label,$val])
        <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
            <p class="text-2xl font-semibold" style="color:var(--adm-text);">{{ number_format($val) }}</p>
            <p class="text-[10px] tracking-[0.2em] uppercase mt-1" style="color:var(--adm-muted);">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-6 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by name, email or phone…"
               class="flex-1 px-4 py-2.5 text-sm focus:outline-none"
               style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
        <button type="submit" class="px-5 py-2.5 text-xs tracking-wider uppercase transition-colors"
                style="border:1px solid var(--adm-border);color:var(--adm-muted);"
                onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
            Search
        </button>
        @if(request('search'))
        <a href="{{ route('admin.customers.index') }}"
           class="px-5 py-2.5 text-xs tracking-wider uppercase transition-colors"
           style="border:1px solid var(--adm-border);color:var(--adm-muted);">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div style="border:1px solid var(--adm-border);overflow:hidden;">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:var(--adm-surface-alt);border-bottom:1px solid var(--adm-border);">
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Customer</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden md:table-cell" style="color:var(--adm-muted);">Phone</th>
                    <th class="text-center px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden lg:table-cell" style="color:var(--adm-muted);">Orders</th>
                    <th class="text-right px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden lg:table-cell" style="color:var(--adm-muted);">Total Spent</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-medium hidden xl:table-cell" style="color:var(--adm-muted);">Joined</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr style="border-top:1px solid var(--adm-border);" class="transition-colors"
                    onmouseover="this.style.background='var(--adm-surface-alt)'" onmouseout="this.style.background='transparent'">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold shrink-0"
                                 style="background:rgba(107,32,22,0.25);color:var(--adm-gold);">
                                {{ strtoupper(substr($customer->name,0,1)) }}
                            </div>
                            <div>
                                <p class="font-medium" style="color:var(--adm-text);">{{ $customer->name }}</p>
                                <p class="text-xs" style="color:var(--adm-muted);">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-xs hidden md:table-cell" style="color:var(--adm-muted);">{{ $customer->phone ?: '—' }}</td>
                    <td class="px-5 py-4 text-center hidden lg:table-cell" style="color:var(--adm-text);">{{ $customer->orders_count }}</td>
                    <td class="px-5 py-4 text-right hidden lg:table-cell" style="color:var(--adm-text);">₦{{ number_format($customer->orders_sum_total ?? 0, 2) }}</td>
                    <td class="px-5 py-4 text-xs hidden xl:table-cell" style="color:var(--adm-muted);">{{ $customer->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="text-xs transition-colors" style="color:var(--adm-gold);"
                           onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">View →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center text-sm" style="color:var(--adm-muted);">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if($customers->hasPages())
    <div class="mt-6">{{ $customers->links() }}</div>
    @endif

</div>
@endsection
