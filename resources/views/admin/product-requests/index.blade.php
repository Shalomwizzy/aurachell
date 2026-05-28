@extends('layouts.admin')
@section('title', 'Product Requests')

@section('content')
<div class="p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-xl font-semibold text-white">Product Requests</h1>
            <p class="text-text-muted text-sm mt-1">Customers requesting products not yet in your catalogue</p>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'viewed' => 'Viewed', 'fulfilled' => 'Fulfilled'] as $key => $label)
        <a href="{{ $key === 'all' ? route('admin.product-requests.index') : route('admin.product-requests.index', ['status' => $key]) }}"
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
        @if($requests->isEmpty())
        <div class="py-16 text-center text-text-muted text-sm">No product requests yet.</div>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[rgba(55,18,32,0.10)]">
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal">Product Requested</th>
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal hidden md:table-cell">Customer</th>
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal hidden lg:table-cell">Budget</th>
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal">Status</th>
                    <th class="text-left text-[10px] uppercase tracking-widest text-text-muted px-5 py-3 font-normal hidden lg:table-cell">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[rgba(55,18,32,0.10)]">
                @foreach($requests as $req)
                <tr class="hover:bg-[rgba(55,18,32,0.15)] transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($req->image_path)
                            <img src="{{ $req->image_url }}" class="w-10 h-10 object-cover flex-shrink-0 border border-[rgba(55,18,32,0.10)]"
                                 onerror="this.style.display='none'">
                            @endif
                            <div>
                                <p class="text-white font-medium">{{ $req->product_name }}</p>
                                @if($req->scent_preference)
                                <p class="text-text-muted text-xs mt-0.5">Scent: {{ $req->scent_preference }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <p class="text-warmSand-300">{{ $req->customer_name }}</p>
                        <p class="text-text-muted text-xs">{{ $req->customer_email }}</p>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-text-muted">{{ $req->budget ?? '—' }}</td>
                    <td class="px-5 py-4">
                        @php $colors = ['pending' => 'text-mahogany bg-mahogany/12 border-mahogany/20', 'viewed' => 'text-mahogany bg-sand/12 border-sand/20', 'fulfilled' => 'text-mahogany bg-mahogany/12 border-mahogany/25']; @endphp
                        <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider border {{ $colors[$req->status] }}">{{ $req->status }}</span>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-text-muted text-xs">{{ $req->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.product-requests.show', $req) }}"
                           class="text-xs text-[var(--adm-gold)] hover:underline underline-offset-2">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Pagination --}}
    @if($requests->hasPages())
    <div class="mt-6">{{ $requests->links() }}</div>
    @endif

</div>
@endsection
