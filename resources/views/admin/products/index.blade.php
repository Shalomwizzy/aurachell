@extends('layouts.admin')
@section('title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Products</h1>
        <p class="text-gray-400 text-sm mt-1">Manage your product catalogue</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sage text-cream text-xs tracking-widest uppercase font-medium hover:bg-sage-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Product
    </a>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 bg-green-500/10 border border-green-500/30 text-green-400 text-sm">{{ session('success') }}</div>
@endif

{{-- Filters --}}
<div class="bg-[#1E1E1E] border border-[#2A2A2A] p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or SKU…"
                   class="w-full bg-[#2A2A2A] border border-[#3A3A3A] px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-sage transition-colors">
        </div>
        <div>
            <select name="category" class="bg-[#2A2A2A] border border-[#3A3A3A] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="status" class="bg-[#2A2A2A] border border-[#3A3A3A] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors">
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
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 text-xs text-gray-400 hover:text-white transition-colors">Clear</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-[#1E1E1E] border border-[#2A2A2A] overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-[#2A2A2A]">
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium">Product</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium hidden md:table-cell">Category</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium">Price</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium hidden sm:table-cell">Stock</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium">Status</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#2A2A2A]">
            @forelse($products as $product)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-[#2A2A2A] flex-shrink-0 overflow-hidden">
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://placehold.co/48x48/2A2A2A/666?text=?'">
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $product->name }}</p>
                            <p class="text-gray-500 text-xs mt-0.5">{{ $product->sku }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <span class="text-gray-400 text-sm">{{ $product->category?->name ?? '—' }}</span>
                </td>
                <td class="px-5 py-4 text-right">
                    <p class="text-white text-sm">₦{{ number_format($product->price, 0) }}</p>
                    @if($product->compare_at_price)
                    <p class="text-gray-500 text-xs line-through">₦{{ number_format($product->compare_at_price, 0) }}</p>
                    @endif
                </td>
                <td class="px-5 py-4 text-center hidden sm:table-cell">
                    @php $reserved = (int) $product->reserved_quantity; @endphp
                    <span class="text-sm {{ $product->stock_quantity <= 5 ? 'text-red-400' : ($product->stock_quantity <= 15 ? 'text-amber-400' : 'text-gray-300') }}">
                        {{ $product->stock_quantity }}
                    </span>
                    @if($reserved > 0)
                    <span class="block text-[10px] text-amber-400/70 tracking-wide mt-0.5">{{ $reserved }} held</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        @if($product->trashed())
                        <span class="px-2 py-0.5 bg-gray-500/20 text-gray-400 text-[10px] tracking-widest uppercase">Archived</span>
                        @elseif($product->is_active)
                        <span class="px-2 py-0.5 bg-green-500/20 text-green-400 text-[10px] tracking-widest uppercase">Active</span>
                        @else
                        <span class="px-2 py-0.5 bg-red-500/20 text-red-400 text-[10px] tracking-widest uppercase">Inactive</span>
                        @endif
                    </div>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        @unless($product->trashed())
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="px-3 py-1.5 bg-[#2A2A2A] text-gray-300 hover:text-white text-xs transition-colors">Edit</a>
                        <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 text-xs transition-colors
                                {{ $product->is_active ? 'bg-red-500/10 text-red-400 hover:bg-red-500/20' : 'bg-green-500/10 text-green-400 hover:bg-green-500/20' }}">
                                {{ $product->is_active ? 'Archive' : 'Activate' }}
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 bg-green-500/10 text-green-400 hover:bg-green-500/20 text-xs transition-colors">Restore</button>
                        </form>
                        @endunless
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center text-gray-500">
                    <p class="text-lg mb-2">No products found</p>
                    <a href="{{ route('admin.products.create') }}" class="text-sage underline text-sm">Add your first product</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="px-5 py-4 border-t border-[#2A2A2A]">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
