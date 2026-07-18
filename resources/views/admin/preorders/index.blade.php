@extends('layouts.admin')
@section('title', 'Pre-orders')

@section('content')
<div class="p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-xl font-semibold text-white">Pre-orders</h1>
            <p class="text-text-muted text-sm mt-1">Customers waiting on out-of-stock products — contact them when you restock</p>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'contacted' => 'Contacted', 'fulfilled' => 'Fulfilled', 'cancelled' => 'Cancelled'] as $key => $label)
        <a href="{{ $key === 'all' ? route('admin.preorders.index') : route('admin.preorders.index', ['status' => $key]) }}"
           class="px-4 py-1.5 text-xs font-sans tracking-wide border transition-colors
               {{ (request('status', 'all') === $key) ? 'bg-[var(--adm-gold)] text-[rgba(55,18,32,0.95)] border-[var(--adm-gold)]' : 'text-text-muted border-[rgba(55,18,32,0.10)] hover:border-[var(--adm-gold)] hover:text-[var(--adm-gold)]' }}">
            {{ $label }}
            <span class="ml-1 opacity-70">({{ $counts[$key] }})</span>
        </a>
        @endforeach
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 bg-mahogany/20 border border-mahogany/25 text-mahogany text-sm">{{ session('success') }}</div>
    @endif

    {{-- Table --}}
    <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)]">
        @if($preorders->isEmpty())
        <div class="py-16 text-center text-text-muted text-sm">No pre-orders yet. When a customer pre-orders an out-of-stock product it will appear here.</div>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[rgba(55,18,32,0.10)]">
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal">Product</th>
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal hidden md:table-cell">Customer</th>
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal">Qty</th>
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal">Status</th>
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal hidden lg:table-cell">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[rgba(55,18,32,0.10)]">
                @foreach($preorders as $preorder)
                <tr class="hover:bg-[rgba(55,18,32,0.15)] transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $preorder->product->primary_image_url }}" class="w-10 h-10 object-cover flex-shrink-0 border border-[rgba(55,18,32,0.10)]"
                                 onerror="this.style.display='none'">
                            <div>
                                <p class="text-white font-medium">{{ $preorder->product->name }}</p>
                                <p class="text-text-muted text-xs mt-0.5">
                                    ₦{{ number_format($preorder->product->price, 0) }} ·
                                    {{ $preorder->product->isInStock() ? 'Back in stock ('.$preorder->product->stock_quantity.' units)' : 'Still out of stock' }}
                                </p>
                                @if($preorder->note)
                                <p class="text-text-muted text-xs mt-0.5 italic">"{{ Str::limit($preorder->note, 80) }}"</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <p class="text-warmSand-300">{{ $preorder->customer_name }}</p>
                        <p class="text-text-muted text-xs">{{ $preorder->customer_email }}</p>
                        @if($preorder->customer_phone)
                        <p class="text-text-muted text-xs">{{ $preorder->customer_phone }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-warmSand-300">{{ $preorder->quantity }}</td>
                    <td class="px-5 py-4">
                        <form action="{{ route('admin.preorders.update', $preorder) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="bg-transparent border border-[rgba(55,18,32,0.20)] text-xs px-2 py-1.5 text-warmSand-300 focus:outline-none">
                                @foreach(['pending', 'contacted', 'fulfilled', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $preorder->status === $status ? 'selected' : '' }} style="color:#2C0F0A;">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-text-muted text-xs">{{ $preorder->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <form action="{{ route('admin.preorders.destroy', $preorder) }}" method="POST"
                              onsubmit="return confirm('Delete this pre-order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-text-muted hover:text-mahogany transition-colors">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Pagination --}}
    @if($preorders->hasPages())
    <div class="mt-6">{{ $preorders->links() }}</div>
    @endif

</div>
@endsection
