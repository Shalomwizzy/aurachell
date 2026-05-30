@extends('layouts.admin')
@section('title', 'Add Category')
@section('breadcrumb', 'Catalog')

@section('content')
<div class="p-6 lg:p-8 max-w-3xl mx-auto">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.categories.index') }}"
           class="w-9 h-9 rounded flex items-center justify-center transition-colors"
           style="color:var(--adm-muted);background:var(--adm-surface-alt);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text-strong);">Add Category</h1>
            <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Group products under a category for the shop and navigation</p>
        </div>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" x-data="{ image: null }">
        @csrf
        <div class="adm-card p-6 space-y-5">
            <div>
                <label class="adm-label">Category Name <span style="color:var(--adm-danger-fg);">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="adm-input" placeholder="e.g. Ceramic Diffusers">
                @error('name')<p class="text-xs mt-1" style="color:var(--adm-danger-fg);">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="adm-label">Description</label>
                <textarea name="description" rows="3" class="adm-input resize-none" placeholder="Brief description shown on the category page">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="adm-label">Parent Category</label>
                    <select name="parent_id" class="adm-input">
                        <option value="">— None (top-level)</option>
                        @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="adm-label">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="adm-input" min="0">
                    <p class="text-[10px] mt-1" style="color:var(--adm-muted);">Lower numbers appear first</p>
                </div>
            </div>

            <div>
                <label class="adm-label">Category Image</label>
                <label class="block w-full aspect-[3/1] border-2 border-dashed cursor-pointer transition-colors group relative overflow-hidden rounded"
                       style="border-color:var(--adm-border);background:var(--adm-surface-alt);"
                       onmouseover="this.style.borderColor='var(--adm-gold)'"
                       onmouseout="this.style.borderColor='var(--adm-border)'">
                    <input type="file" name="image" accept="image/*" class="sr-only"
                           @change="const f = $event.target.files[0]; if(f){const r=new FileReader();r.onload=(e)=>image=e.target.result;r.readAsDataURL(f);}">
                    <div x-show="!image" class="absolute inset-0 flex flex-col items-center justify-center gap-2" style="color:var(--adm-muted);">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-xs">Click to upload category image</span>
                        <span class="text-[10px]" style="opacity:0.7;">JPG / PNG / WEBP — recommended 1600×600</span>
                    </div>
                    <img x-show="image" :src="image" class="absolute inset-0 w-full h-full object-cover">
                </label>
            </div>

            <label class="flex items-center justify-between gap-3 cursor-pointer pt-2">
                <div>
                    <p class="text-sm" style="color:var(--adm-text);">Visible in shop</p>
                    <p class="text-[10px]" style="color:var(--adm-muted);">Toggle off to hide without deleting</p>
                </div>
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="w-4 h-4" style="accent-color:var(--adm-accent);">
            </label>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
            <a href="{{ route('admin.categories.index') }}"
               class="px-6 py-3 text-xs tracking-[0.2em] uppercase font-medium text-center rounded transition-colors"
               style="background:var(--adm-surface-alt);color:var(--adm-muted);">Cancel</a>
            <button type="submit"
                    class="px-8 py-3 text-xs tracking-[0.2em] uppercase font-medium rounded transition-opacity"
                    style="background:var(--adm-accent);color:#FFFFFF;"
                    onmouseover="this.style.opacity='0.92'" onmouseout="this.style.opacity='1'">
                Create Category
            </button>
        </div>
    </form>
</div>

<style>
.adm-label { display:block; font-size:10px; letter-spacing:0.2em; text-transform:uppercase; color:var(--adm-muted); margin-bottom:8px; font-weight:500; }
.adm-input { width:100%; background:var(--adm-surface-alt); border:1px solid var(--adm-border); padding:10px 14px; font-size:13px; color:var(--adm-text); border-radius:4px; transition:border-color .15s, box-shadow .15s; }
.adm-input::placeholder { color:var(--adm-muted); opacity:0.55; }
.adm-input:focus { outline:none; border-color:var(--adm-gold); box-shadow:0 0 0 1px var(--adm-gold); }
</style>
@endsection
