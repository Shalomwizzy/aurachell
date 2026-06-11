@extends('layouts.admin')
@section('title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Products</h1>
        <p class="text-text-muted text-sm mt-1">Manage your product catalogue</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sage text-cream text-xs tracking-widest uppercase font-medium hover:bg-sage-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
        Add Product
    </a>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 bg-mahogany/12 border border-mahogany/25 text-mahogany text-sm">{{ session('success') }}</div>
@endif

{{-- Filters --}}
<div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or SKU…"
                   class="w-full bg-[rgba(55,18,32,0.10)] border border-[rgba(55,18,32,0.15)] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors" style="color:var(--adm-text);">
        </div>
        <div>
            <select name="category" class="bg-[rgba(55,18,32,0.10)] border border-[rgba(55,18,32,0.15)] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="status" class="bg-[rgba(55,18,32,0.10)] border border-[rgba(55,18,32,0.15)] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-sage text-cream text-xs tracking-widest uppercase font-medium hover:bg-sage-800 transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['q','category','status']))
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 text-xs text-text-muted hover:text-cream transition-colors">Clear</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-[rgba(55,18,32,0.10)]">
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Product</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium hidden md:table-cell">Category</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Price</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium hidden sm:table-cell">Stock</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Status</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[rgba(55,18,32,0.10)]">
            @forelse($products as $product)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-[rgba(55,18,32,0.10)] flex-shrink-0 overflow-hidden">
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://placehold.co/48x48/2A2A2A/666?text=?'">
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $product->name }}</p>
                            <p class="text-text-muted text-xs mt-0.5">{{ $product->sku }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <span class="text-text-muted text-sm">{{ $product->category?->name ?? '—' }}</span>
                </td>
                <td class="px-5 py-4 text-right">
                    <p class="text-white text-sm">₦{{ number_format($product->price, 0) }}</p>
                    @if($product->compare_at_price)
                    <p class="text-text-muted text-xs line-through">₦{{ number_format($product->compare_at_price, 0) }}</p>
                    @endif
                </td>
                <td class="px-5 py-4 text-center hidden sm:table-cell">
                    @php $reserved = (int) $product->reserved_quantity; @endphp
                    <span class="text-sm {{ $product->stock_quantity <= 5 ? 'text-mahogany' : ($product->stock_quantity <= 15 ? 'text-mahogany' : 'text-warmSand-300') }}">
                        {{ $product->stock_quantity }}
                    </span>
                    @if($reserved > 0)
                    <span class="block text-[10px] text-mahogany/70 tracking-wide mt-0.5">{{ $reserved }} held</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        @if($product->trashed())
                        <span class="px-2 py-0.5 bg-gray-500/20 text-text-muted text-[10px] tracking-widest uppercase">Archived</span>
                        @elseif($product->is_active)
                        <span class="px-2 py-0.5 bg-mahogany/15 text-mahogany text-[10px] tracking-widest uppercase">Active</span>
                        @else
                        <span class="px-2 py-0.5 bg-mahogany/15 text-mahogany text-[10px] tracking-widest uppercase">Inactive</span>
                        @endif
                    </div>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        @unless($product->trashed())
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="px-3 py-1.5 bg-[rgba(55,18,32,0.10)] text-warmSand-300 hover:text-cream text-xs transition-colors">Edit</a>
                        <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 text-xs transition-colors
                                {{ $product->is_active ? 'bg-mahogany/10 text-mahogany hover:bg-mahogany/15' : 'bg-mahogany/12 text-mahogany hover:bg-mahogany/15' }}">
                                {{ $product->is_active ? 'Archive' : 'Activate' }}
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 bg-mahogany/12 text-mahogany hover:bg-mahogany/15 text-xs transition-colors">Restore</button>
                        </form>
                        @endunless
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center text-text-muted">
                    <p class="text-lg mb-2">No products found</p>
                    <a href="{{ route('admin.products.create') }}" class="text-sage underline text-sm">Add your first product</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="px-5 py-4 border-t border-[rgba(55,18,32,0.10)]">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
