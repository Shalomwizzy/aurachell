@extends('layouts.admin')
@section('title', 'New Blog Post')
@section('breadcrumb', 'Blog')

@section('content')
<div class="p-6 lg:p-8">

<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.blog.index') }}" class="hover:opacity-80 transition-opacity" style="color:var(--adm-muted)">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <h1 class="text-xl font-semibold" style="color:var(--adm-text);">New Blog Post</h1>
</div>

@if($errors->any())
<div class="mb-6 px-4 py-3 text-sm" style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.20);color:var(--adm-text);">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

{{-- AI Generate Panel --}}
<div class="mb-6 p-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);"
     x-data="{ generating: false, topic: '' }">
    <div class="flex items-center gap-3 mb-3">
        <svg class="w-4 h-4" style="color:var(--adm-gold)" fill="currentColor" viewBox="0 0 20 20">
            <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
        </svg>
        <span class="text-sm font-medium" style="color:var(--adm-gold)">AI Blog Writer</span>
    </div>
    <div class="flex gap-3">
        <input x-model="topic" type="text"
               placeholder="Enter a topic e.g. Benefits of aromatherapy for sleep"
               class="flex-1 px-3 py-2 text-sm focus:outline-none"
               style="background:rgba(255,255,255,0.04);border:1px solid var(--adm-border);color:var(--adm-text);"
               x-on:keydown.enter.prevent="if(topic.trim() && !generating){ generating = true; generateBlogPost(topic, () => generating = false) }">
        <button type="button"
                :disabled="generating || !topic.trim()"
                x-on:click="generating = true; generateBlogPost(topic, () => generating = false)"
                class="px-4 py-2 text-xs tracking-wider uppercase font-medium flex items-center gap-2 transition-opacity hover:opacity-90"
                style="background:#371220;color:#FFFFFF;"
                :class="generating ? 'opacity-60 cursor-not-allowed' : ''">
            <svg x-show="!generating" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <svg x-show="generating" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span x-text="generating ? 'Generating…' : 'Generate with AI'"></span>
        </button>
    </div>
</div>

<form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="p-6 space-y-5" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <div>
                    <label class="admin-label">Post Title *</label>
                    <input id="field-title" name="title" value="{{ old('title') }}" required
                           class="admin-input" placeholder="e.g. The Art of Scent: How Aromatherapy Changes Your Space">
                </div>
                <div>
                    <label class="admin-label">Excerpt <span class="text-xs font-normal" style="color:var(--adm-muted)">(shown in listings, max 500 chars)</span></label>
                    <textarea id="field-excerpt" name="excerpt" rows="2" class="admin-input resize-none"
                              placeholder="A short description that draws readers in…">{{ old('excerpt') }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Content * <span class="text-xs font-normal" style="color:var(--adm-muted)">(HTML supported)</span></label>
                    <textarea id="field-content" name="content" rows="18"
                              class="admin-input resize-y font-mono text-xs leading-relaxed"
                              placeholder="<h2>Introduction</h2>&#10;<p>Your blog content here…</p>">{{ old('content') }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Tags <span class="text-xs font-normal" style="color:var(--adm-muted)">(comma-separated)</span></label>
                    <input id="field-tags" name="tags" value="{{ old('tags') }}" class="admin-input"
                           placeholder="aromatherapy, candles, wellness, home decor">
                </div>
            </div>

            <div class="p-6 space-y-4" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <h3 class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted)">SEO</h3>
                <div>
                    <label class="admin-label">Meta Title <span class="text-xs font-normal" style="color:var(--adm-muted)">(max 70 chars)</span></label>
                    <input id="field-meta-title" name="meta_title" value="{{ old('meta_title') }}" class="admin-input" maxlength="70">
                </div>
                <div>
                    <label class="admin-label">Meta Description <span class="text-xs font-normal" style="color:var(--adm-muted)">(max 160 chars)</span></label>
                    <textarea id="field-meta-desc" name="meta_description" rows="2" class="admin-input resize-none" maxlength="160">{{ old('meta_description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="p-5 space-y-4" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <h3 class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted)">Publish</h3>
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                           class="w-4 h-4 accent-mahogany">
                    <span class="text-sm" style="color:var(--adm-text)">Publish immediately</span>
                </label>
                <button type="submit"
                        class="w-full py-2.5 text-xs tracking-[0.15em] uppercase font-medium transition-opacity hover:opacity-90"
                        style="background:#371220;color:#FFFFFF;">
                    Save Post
                </button>
                <a href="{{ route('admin.blog.index') }}"
                   class="block text-center text-xs hover:underline" style="color:var(--adm-muted)">Cancel</a>
            </div>

            <div class="p-5 space-y-3" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <h3 class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted)">Cover Image</h3>
                <div x-data="{ preview: null }">
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-xs" style="color:var(--adm-text)"
                           x-on:change="const f = $event.target.files[0]; if(f){ const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); } else { preview = null }">
                    <template x-if="preview">
                        <img :src="preview" class="mt-3 w-full object-cover rounded" style="max-height:160px">
                    </template>
                </div>
                <p class="text-xs" style="color:var(--adm-muted)">JPG, PNG or WebP · Max 3 MB</p>
            </div>
        </div>
    </div>
</form>

</div>

@push('scripts')
<script>
function generateBlogPost(topic, done) {
    var csrf = document.querySelector('meta[name=csrf-token]').content;
    var prompt = 'You are a content writer for Aurachell, a luxury Nigerian aromatherapy and home diffuser brand. Write a detailed SEO-optimised blog post about: ' + topic + '. Return ONLY a valid JSON object with these exact keys: title (string), excerpt (string, max 160 chars), content (string, full HTML with h2/p/ul tags, min 500 words), tags (array of 5 strings), meta_title (string, max 60 chars), meta_description (string, max 160 chars). No markdown, no extra text, just the JSON object.';

    fetch('{{ route("admin.ai.gemini") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ prompt: prompt, max_tokens: 4000 })
    })
    .then(function(r) {
        if (!r.ok && r.status === 401) {
            throw new Error('Session expired. Please refresh the page and log in again.');
        }
        return r.json();
    })
    .then(function(res) {
        if (res.success && res.data && typeof res.data === 'object') {
            var d = res.data;
            document.getElementById('field-title').value      = d.title || '';
            document.getElementById('field-excerpt').value    = d.excerpt || '';
            document.getElementById('field-content').value    = d.content || '';
            document.getElementById('field-tags').value       = Array.isArray(d.tags) ? d.tags.join(', ') : (d.tags || '');
            document.getElementById('field-meta-title').value = d.meta_title || '';
            document.getElementById('field-meta-desc').value  = d.meta_description || '';
            var provider = res.provider === 'groq' ? ' (via Groq)' : '';
            window.showToast && window.showToast('Blog post generated' + provider + '!', 'success');
        } else {
            var msg = res.error || 'AI could not generate content. Try a more specific topic.';
            window.showToast && window.showToast(msg, 'error');
        }
    })
    .catch(function(e) {
        window.showToast && window.showToast(e.message || 'Request failed. Check your connection.', 'error');
    })
    .finally(function() { done && done(); });
}
</script>
@endpush
@endsection
