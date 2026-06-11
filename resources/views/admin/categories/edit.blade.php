@extends('layouts.admin')
@section('title', 'Edit: ' . $category->name)

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.categories.index') }}" class="text-text-muted hover:text-cream transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <h1 class="font-display text-2xl text-white">Edit Category</h1>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
    @csrf @method('PUT')
    <div class="space-y-6">
        <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6 space-y-5">
            <div>
                <label class="admin-label">Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="admin-input">
            </div>
            <div>
                <label class="admin-label">Description</label>
                <textarea name="description" rows="3" class="admin-input resize-none">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="admin-label">Parent Category</label>
                    <select name="parent_id" class="admin-input">
                        <option value="">None (top-level)</option>
                        @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="admin-input" min="0">
                </div>
            </div>
            <div>
                <label class="admin-label">Category Image</label>
                <div class="flex items-start gap-4">
                    @if($category->image)
                    <div class="w-24 h-24 bg-[rgba(55,18,32,0.10)] overflow-hidden flex-shrink-0">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                    </div>
                    @endif
                    <label class="flex-1 h-24 border-2 border-dashed border-[rgba(55,18,32,0.15)] hover:border-sage/50 cursor-pointer flex flex-col items-center justify-center gap-2 text-text-muted hover:text-warmSand-300 transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                        <span class="text-xs">{{ $category->image ? 'Replace image' : 'Upload image' }}</span>
                        <input type="file" name="image" accept="image/*" class="sr-only">
                    </label>
                </div>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="w-4 h-4 accent-sage">
                <span class="text-sm text-warmSand-300">Active (visible in shop)</span>
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-8 py-3 bg-sage text-cream text-xs tracking-widest uppercase font-medium hover:bg-sage-800 transition-colors">
                Save Changes
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-[rgba(55,18,32,0.10)] text-warmSand-300 text-xs tracking-widest uppercase hover:bg-[rgba(55,18,32,0.15)] transition-colors">
                Cancel
            </a>
        </div>
    </div>
</form>

<style>
.admin-label { @apply block text-[10px] tracking-[0.2em] uppercase text-text-muted mb-2; }
.admin-input { @apply w-full bg-[rgba(55,18,32,0.10)] border border-[rgba(55,18,32,0.15)] px-4 py-2.5 text-sm text-white focus:outline-none focus:border-sage transition-colors; }
.admin-input::placeholder { color: var(--adm-muted); }
</style>
@endsection
