@extends('layouts.admin')
@section('title', 'Edit Post')
@section('breadcrumb', 'Blog')

@section('content')
<div class="p-6 lg:p-8">

<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.blog.index') }}" class="hover:opacity-80 transition-opacity" style="color:var(--adm-muted)">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <h1 class="text-xl font-semibold truncate" style="color:var(--adm-text);">{{ Str::limit($blog->title, 50) }}</h1>
</div>

@if($errors->any())
<div class="mb-6 px-4 py-3 text-sm" style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.20);color:rgba(250,245,237,0.80);">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="p-6 space-y-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <div>
                    <label class="admin-label">Post Title *</label>
                    <input name="title" value="{{ old('title', $blog->title) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Excerpt <span class="text-xs font-normal" style="color:var(--adm-muted)">(max 500 chars)</span></label>
                    <textarea name="excerpt" rows="2" class="admin-input resize-none">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Content * <span class="text-xs font-normal" style="color:var(--adm-muted)">(HTML supported)</span></label>
                    <textarea name="content" rows="18" class="admin-input resize-y font-mono text-xs leading-relaxed">{{ old('content', $blog->content) }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Tags <span class="text-xs font-normal" style="color:var(--adm-muted)">(comma-separated)</span></label>
                    <input name="tags" value="{{ old('tags', is_array($blog->tags) ? implode(', ', $blog->tags) : $blog->tags) }}" class="admin-input">
                </div>
            </div>

            <div class="p-6 space-y-4" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <h3 class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted)">SEO</h3>
                <div>
                    <label class="admin-label">Meta Title <span class="text-xs font-normal" style="color:var(--adm-muted)">(max 70 chars)</span></label>
                    <input name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" class="admin-input" maxlength="70">
                </div>
                <div>
                    <label class="admin-label">Meta Description <span class="text-xs font-normal" style="color:var(--adm-muted)">(max 160 chars)</span></label>
                    <textarea name="meta_description" rows="2" class="admin-input resize-none" maxlength="160">{{ old('meta_description', $blog->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="p-5 space-y-4" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <h3 class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted)">Publish</h3>
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $blog->is_published) ? 'checked' : '' }}
                           class="w-4 h-4 accent-mahogany">
                    <span class="text-sm" style="color:var(--adm-text)">Published</span>
                </label>
                @if($blog->published_at)
                <p class="text-xs" style="color:var(--adm-muted)">
                    Published {{ $blog->published_at->format('d M Y') }} &middot; {{ number_format($blog->views) }} views
                </p>
                @endif
                <button type="submit"
                        class="w-full py-2.5 text-xs tracking-[0.15em] uppercase font-medium transition-opacity hover:opacity-90"
                        style="background:#371220;color:#FFFFFF;">
                    Update Post
                </button>
                <div class="flex justify-between items-center pt-1">
                    <a href="{{ route('blog.show', $blog->slug) }}" target="_blank"
                       class="text-xs hover:underline" style="color:var(--adm-muted)">View live ↗</a>
                    <a href="{{ route('admin.blog.index') }}"
                       class="text-xs hover:underline" style="color:var(--adm-muted)">Back to list</a>
                </div>
            </div>

            <div class="p-5 space-y-3" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <h3 class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted)">Cover Image</h3>
                @if($blog->cover_image)
                <div class="relative" x-data="{ removing: false }">
                    <img src="{{ asset('images/blog/' . $blog->cover_image) }}"
                         alt="Cover" class="w-full object-cover rounded" style="max-height:160px"
                         x-show="!removing">
                    <button type="button" @click="removing = !removing"
                            x-text="removing ? 'Keep image' : 'Remove'"
                            class="mt-1 text-xs hover:underline"
                            :style="removing ? 'color:var(--adm-muted)' : 'color:rgba(55,18,32,0.8)'"></button>
                    <input type="hidden" name="remove_cover" :value="removing ? '1' : '0'">
                </div>
                @endif
                <div x-data="{ preview: null }">
                    <label class="block text-xs mb-2" style="color:var(--adm-muted)">{{ $blog->cover_image ? 'Upload to replace:' : 'Upload image:' }}</label>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-xs" style="color:var(--adm-text)"
                           @change="const f = $event.target.files[0]; if(f) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); } else preview = null">
                    <template x-if="preview">
                        <img :src="preview" class="mt-3 w-full object-cover rounded" style="max-height:140px">
                    </template>
                </div>
                <p class="text-xs" style="color:var(--adm-muted)">JPG, PNG or WebP · Max 3 MB</p>
            </div>

            <div class="p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <h3 class="text-[10px] tracking-[0.2em] uppercase font-medium mb-3" style="color:var(--adm-muted)">Danger Zone</h3>
                <form method="POST" action="{{ route('admin.blog.destroy', $blog) }}"
                      onsubmit="return confirm('Delete this post permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full py-2 text-xs tracking-wider uppercase transition-opacity hover:opacity-80"
                            style="border:1px solid rgba(55,18,32,0.25);color:rgba(55,18,32,0.7);">
                        Delete Post
                    </button>
                </form>
            </div>
        </div>
    </div>
</form>

</div>
@endsection
