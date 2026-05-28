@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-display text-2xl text-white">Categories</h1>
        <p class="text-text-muted text-sm mt-1">Organise your product collections</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sage text-cream text-xs tracking-widest uppercase font-medium hover:bg-sage-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Category
    </a>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 bg-mahogany/12 border border-mahogany/25 text-mahogany text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-6 px-4 py-3 bg-mahogany/10 border border-mahogany/20 text-mahogany text-sm">{{ session('error') }}</div>
@endif

<div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-[rgba(55,18,32,0.10)]">
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Category</th>
                <th class="px-5 py-3.5 text-left text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium hidden sm:table-cell">Parent</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Products</th>
                <th class="px-5 py-3.5 text-center text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Status</th>
                <th class="px-5 py-3.5 text-right text-[10px] tracking-[0.2em] uppercase text-text-muted font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[rgba(55,18,32,0.10)]">
            @forelse($categories as $category)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        @if($category->image)
                        <div class="w-10 h-10 bg-[rgba(55,18,32,0.10)] overflow-hidden flex-shrink-0">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="w-10 h-10 bg-[rgba(55,18,32,0.10)] flex-shrink-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-text-dark/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </div>
                        @endif
                        <div>
                            <p class="text-white text-sm font-medium">{{ $category->name }}</p>
                            <p class="text-text-muted text-xs">/{{ $category->slug }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 hidden sm:table-cell">
                    <span class="text-text-muted text-sm">{{ $category->parent?->name ?? '—' }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="text-warmSand-300 text-sm">{{ $category->products_count }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    @if($category->is_active)
                    <span class="px-2 py-0.5 bg-mahogany/15 text-mahogany text-[10px] tracking-widest uppercase">Active</span>
                    @else
                    <span class="px-2 py-0.5 bg-mahogany/15 text-mahogany text-[10px] tracking-widest uppercase">Inactive</span>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="px-3 py-1.5 bg-[rgba(55,18,32,0.10)] text-warmSand-300 hover:text-cream text-xs transition-colors">Edit</a>
                        @if($category->products_count === 0)
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-mahogany/10 text-mahogany hover:bg-mahogany/15 text-xs transition-colors">Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-16 text-center text-text-muted">
                    <p class="mb-2">No categories yet</p>
                    <a href="{{ route('admin.categories.create') }}" class="text-sage underline text-sm">Create your first category</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($categories->hasPages())
    <div class="px-5 py-4 border-t border-[rgba(55,18,32,0.10)]">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
