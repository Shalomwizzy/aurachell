@extends('layouts.admin')
@section('title', 'Edit: ' . $product->name)

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.products.index') }}" class="text-text-muted hover:text-cream transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="font-display text-2xl text-white">Edit Product</h1>
        <p class="text-text-muted text-sm">{{ $product->name }}</p>
    </div>
</div>

@if(session('success'))
<div class="mb-6 px-4 py-3 bg-mahogany/12 border border-mahogany/25 text-mahogany text-sm">{{ session('success') }}</div>
@endif

{{-- AI Description Generator --}}
<div class="adm-card rounded p-5 mb-6">
    <div class="flex items-center gap-3 mb-3">
        <div class="w-7 h-7 rounded flex items-center justify-center flex-shrink-0" style="background:rgba(55,18,32,0.4)">
            <svg class="w-4 h-4" style="color:var(--adm-gold)" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/></svg>
        </div>
        <span class="text-sm font-medium" style="color:var(--adm-gold)">AI Re-Generate Content</span>
        <span class="text-xs ml-1" style="color:var(--adm-muted)">— overwrites existing descriptions</span>
    </div>
    <button type="button" id="prod-regen-btn" onclick="prodRegenAi()"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-opacity hover:opacity-90"
            style="background:var(--adm-accent);color:#FFFFFF;">
        <svg id="prod-regen-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        <svg id="prod-regen-spin" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        <span id="prod-regen-label">Regenerate with AI</span>
    </button>
</div>
<script>
function prodRegenAi() {
    var btn=document.getElementById('prod-regen-btn'),icon=document.getElementById('prod-regen-icon'),spin=document.getElementById('prod-regen-spin'),lbl=document.getElementById('prod-regen-label');
    btn.disabled=true; icon.style.display='none'; spin.style.display='inline'; lbl.textContent='Generating…';
    regenProductContent(function(){btn.disabled=false;icon.style.display='inline';spin.style.display='none';lbl.textContent='Regenerate with AI';});
}
</script>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main fields --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6 space-y-5">
                <h2 class="text-[11px] font-medium text-white tracking-widest uppercase">Product Information</h2>
                <div>
                    <label class="admin-label">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="admin-input">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Category *</label>
                        <select name="category_id" required class="admin-input">
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="admin-input">
                    </div>
                </div>
                <div>
                    <label class="admin-label">Short Description</label>
                    <textarea name="short_description" rows="2" class="admin-input resize-none">{{ old('short_description', $product->short_description) }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Full Description</label>
                    <textarea name="description" rows="6" class="admin-input resize-y">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6 space-y-5">
                <h2 class="text-[11px] font-medium text-white tracking-widest uppercase">Pricing & Inventory</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Sale Price (₦) *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" required class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Compare-at Price (₦)</label>
                        <input type="number" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" step="0.01" class="admin-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0" class="admin-input">
                    </div>
                </div>
            </div>

            <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6 space-y-5">
                <h2 class="text-[11px] font-medium text-white tracking-widest uppercase">Product Details</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="admin-label">Capacity (ml)</label>
                        <input type="number" name="capacity_ml" value="{{ old('capacity_ml', $product->capacity_ml) }}" class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Longevity</label>
                        <input type="text" name="burn_time_hours" value="{{ old('burn_time_hours', $product->burn_time_hours) }}" maxlength="100" class="admin-input" placeholder="e.g. 40 hours or 6 months">
                    </div>
                    <div>
                        <label class="admin-label">Weight (kg)</label>
                        <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" step="0.01" class="admin-input">
                    </div>
                </div>
                <div>
                    <label class="admin-label">Scent Notes</label>
                    <input type="text" name="scent_notes" value="{{ old('scent_notes', $product->scent_notes) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">How to Use <span class="text-[10px] normal-case tracking-normal" style="color:var(--adm-muted);opacity:0.7;">(optional)</span></label>
                    <textarea name="usage_notes" rows="3" maxlength="2000" class="admin-input resize-none" placeholder="Leave blank to hide this section. Otherwise, brief instructions shown on the product page.">{{ old('usage_notes', $product->usage_notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Inactive warning --}}
            @unless($product->is_active)
            <div class="px-4 py-3 text-sm flex items-center gap-2"
                 style="background:var(--adm-warn-bg);border:1px solid var(--adm-warn-fg);color:var(--adm-warn-fg);">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                This product is <strong>not visible</strong> in the shop. Check the Active box below and save.
            </div>
            @endunless

            <div class="p-5 space-y-4" style="background:var(--adm-surface);border:1px solid var(--adm-border);">
                <h2 class="text-[10px] tracking-[0.2em] uppercase font-medium" style="color:var(--adm-muted);">Publishing</h2>
                <label class="flex items-center justify-between gap-3 cursor-pointer py-1">
                    <div>
                        <p class="text-sm" style="color:var(--adm-text);">Active</p>
                        <p class="text-[10px]" style="color:var(--adm-muted);">Visible in shop</p>
                    </div>
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="w-4 h-4" style="accent-color:var(--adm-accent);">
                </label>
                <label class="flex items-center justify-between gap-3 cursor-pointer py-1" style="border-top:1px solid var(--adm-border);">
                    <div class="pt-3">
                        <p class="text-sm" style="color:var(--adm-text);">Featured</p>
                        <p class="text-[10px]" style="color:var(--adm-muted);">Highlight on homepage</p>
                    </div>
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1"
                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                           class="w-4 h-4 mt-3" style="accent-color:var(--adm-accent);">
                </label>
                <div class="pt-3" style="border-top:1px solid var(--adm-border);">
                    <a href="{{ route('product.show', $product->slug) }}" target="_blank"
                       class="flex items-center gap-2 text-xs transition-opacity hover:opacity-70"
                       style="color:var(--adm-gold);">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        View in Shop
                    </a>
                </div>
            </div>

            {{-- Current images --}}
            @if($product->images->count())
            <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6 space-y-4">
                <h2 class="text-[11px] font-medium text-white tracking-widest uppercase">Current Images</h2>
                <div class="grid grid-cols-2 gap-2" id="existing-images-grid">
                    @foreach($product->images as $image)
                    <div class="relative group" id="img-wrap-{{ $image->id }}">
                        <div class="aspect-square bg-[rgba(55,18,32,0.10)] overflow-hidden border-2 {{ $image->is_primary ? 'border-[var(--adm-gold)]' : 'border-transparent' }}" id="img-border-{{ $image->id }}">
                            <img src="{{ $image->url }}" alt="" class="w-full h-full object-cover">
                        </div>

                        {{-- Primary badge --}}
                        <span id="img-primary-badge-{{ $image->id }}"
                              class="absolute top-1 left-1 bg-[var(--adm-gold)] text-[rgba(55,18,32,0.95)] text-[9px] px-1.5 py-0.5 font-semibold {{ $image->is_primary ? '' : 'hidden' }}">Primary</span>

                        {{-- Delete button --}}
                        <button type="button"
                                onclick="deleteProductImage({{ $image->id }}, this)"
                                class="absolute top-1 right-1 w-6 h-6 bg-mahogany hover:bg-mahogany text-white flex items-center justify-center text-sm leading-none transition-colors shadow">
                            ×
                        </button>

                        {{-- Set primary on hover --}}
                        @unless($image->is_primary)
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-2" id="img-hover-{{ $image->id }}">
                            <button type="button"
                                    onclick="setPrimaryImage({{ $image->id }}, {{ $product->id }})"
                                    class="text-[10px] px-3 py-1 bg-[var(--adm-gold)] text-[rgba(55,18,32,0.95)] font-semibold">Set Primary</button>
                        </div>
                        @endunless
                    </div>
                    @endforeach
                </div>
                <p class="text-[var(--adm-muted)] text-[10px]">Tap × to delete · Hover to set as primary</p>
            </div>
            @endif

            {{-- Add more images --}}
            <div class="bg-[var(--adm-surface)] border border-[rgba(55,18,32,0.10)] p-6 space-y-4">
                <h2 class="text-[11px] font-medium text-white tracking-widest uppercase">Add Images</h2>
                <label id="edit-img-drop-zone" class="flex flex-col w-full h-32 border-2 border-dashed border-[rgba(55,18,32,0.15)] cursor-pointer transition-colors items-center justify-center gap-2 text-text-muted">
                    <input type="file" name="images[]" id="edit-img-input" multiple accept="image/*" class="sr-only">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                    <span id="edit-img-label" class="text-xs">Click to select images</span>
                </label>
                <div id="edit-img-previews" class="grid grid-cols-2 gap-2" style="display:none;"></div>
                <p id="edit-img-note" class="text-[var(--adm-muted)] text-[10px]" style="display:none;">
                    {{ $product->images->count() === 0 ? 'First image will be set as primary.' : 'Will be added to existing images.' }}
                    Click Save Changes to upload.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2.5 text-xs tracking-[0.2em] uppercase font-medium transition-opacity hover:opacity-90"
                        style="background:var(--adm-accent);color:#FFFFFF;">Save Changes</button>
                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2.5 text-xs tracking-[0.2em] uppercase transition-opacity hover:opacity-70"
                   style="border:1px solid var(--adm-border);color:var(--adm-muted);">Cancel</a>
            </div>
        </div>
    </div>
</form>

{{-- Danger zone — must be OUTSIDE the product update form --}}
<div class="lg:grid lg:grid-cols-3 lg:gap-6 mt-0">
    <div class="lg:col-start-3">
        <div style="border:1px solid rgba(248,113,113,0.30);border-radius:4px;padding:1rem;">
            <p style="color:rgba(248,113,113,0.85);font-size:10px;letter-spacing:0.2em;text-transform:uppercase;margin-bottom:12px;font-weight:600;">Danger Zone</p>
            <div class="flex items-center justify-between gap-4">
                <p style="color:var(--adm-text);font-size:12px;line-height:1.5;">Permanently delete this product and all its images. This cannot be undone.</p>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                      onsubmit="return confirm('Permanently delete {{ addslashes($product->name) }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <input type="hidden" name="force_delete" value="1">
                    <button type="submit"
                            style="background:rgba(248,113,113,0.85);color:#fff;padding:8px 16px;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;font-weight:500;border-radius:4px;border:none;cursor:pointer;white-space:nowrap;"
                            onmouseover="this.style.background='rgba(248,113,113,1)'" onmouseout="this.style.background='rgba(248,113,113,0.85)'">
                        Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const _csrf = document.querySelector('meta[name=csrf-token]').content;

// Image upload preview — vanilla JS
(function() {
    var input    = document.getElementById('edit-img-input');
    var previews = document.getElementById('edit-img-previews');
    var label    = document.getElementById('edit-img-label');
    var note     = document.getElementById('edit-img-note');
    var dropZone = document.getElementById('edit-img-drop-zone');
    var existingCount = {{ $product->images->count() }};
    if (!input) return;

    input.addEventListener('change', function() {
        var files = Array.from(this.files);
        previews.innerHTML = '';

        if (!files.length) {
            previews.style.display = 'none';
            note.style.display = 'none';
            label.textContent = 'Click to select images';
            dropZone.style.borderColor = 'rgba(55,18,32,0.15)';
            return;
        }

        label.textContent = files.length + ' image(s) selected — save to upload';
        dropZone.style.borderColor = 'var(--adm-gold)';

        files.forEach(function(file, i) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var wrap = document.createElement('div');
                wrap.className = 'relative aspect-square overflow-hidden';
                wrap.style.background = 'rgba(55,18,32,0.10)';
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                wrap.appendChild(img);
                if (i === 0 && existingCount === 0) {
                    var badge = document.createElement('span');
                    badge.className = 'absolute top-1 left-1 text-[9px] px-1.5 py-0.5 font-semibold';
                    badge.style.cssText = 'background:var(--adm-gold);color:rgba(55,18,32,0.95)';
                    badge.textContent = 'Primary';
                    wrap.appendChild(badge);
                }
                previews.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });

        previews.style.display = 'grid';
        note.style.display = 'block';
    });
})();

function deleteProductImage(imageId, btn) {
    if (!confirm('Delete this image?')) return;
    btn.disabled = true;
    fetch('{{ url("admin/products/images") }}/' + imageId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': _csrf, 'Accept': 'application/json' }
    })
    .then(r => r.ok ? r.json() : Promise.reject(r.status))
    .then(() => {
        var wrap = document.getElementById('img-wrap-' + imageId);
        if (wrap) wrap.remove();
        window.showToast && window.showToast('Image deleted.', 'success');
    })
    .catch(() => {
        btn.disabled = false;
        window.showToast && window.showToast('Could not delete image.', 'error');
    });
}

function setPrimaryImage(imageId, productId) {
    fetch('{{ url("admin/products") }}/' + productId, {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': _csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ primary_image_id: imageId, _set_primary_only: true })
    })
    .then(r => r.ok || r.status === 302 ? true : Promise.reject(r.status))
    .then(() => {
        document.querySelectorAll('[id^="img-border-"]').forEach(el => {
            el.classList.remove('border-[var(--adm-gold)]');
            el.classList.add('border-transparent');
        });
        document.querySelectorAll('[id^="img-primary-badge-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="img-hover-"]').forEach(el => el.classList.remove('hidden'));

        var border = document.getElementById('img-border-' + imageId);
        if (border) { border.classList.remove('border-transparent'); border.classList.add('border-[var(--adm-gold)]'); }
        var badge = document.getElementById('img-primary-badge-' + imageId);
        if (badge) badge.classList.remove('hidden');
        var hover = document.getElementById('img-hover-' + imageId);
        if (hover) hover.classList.add('hidden');

        window.showToast && window.showToast('Primary image updated.', 'success');
    })
    .catch(() => window.showToast && window.showToast('Could not update primary image.', 'error'));
}

function regenProductContent(done) {
    var name = (document.querySelector('[name=name]') || {}).value || '';
    var cat  = (document.querySelector('[name=category_id]')?.selectedOptions[0])?.text || '';
    name = name.trim();
    if (!name) { window.showToast && window.showToast('Product name is required', 'error'); done && done(); return; }

    var csrf = document.querySelector('meta[name=csrf-token]').content;
    var prompt = 'You are a copywriter for Aurachell, a luxury Nigerian aromatherapy brand. '
        + 'Generate compelling product content for: ' + name + ' (category: ' + cat + '). '
        + 'Return a JSON object with these exact keys: short_description (2 sentences, max 160 chars), '
        + 'description (rich HTML with 3-4 paragraphs using p/ul tags, highlight ingredients/benefits/experience), '
        + 'meta_title (max 60 chars SEO-optimised), meta_description (max 155 chars compelling CTA), '
        + 'scent_notes (comma-separated 3-5 scent notes). Return ONLY valid JSON, no markdown wrapper.';

    fetch('{{ route("admin.ai.gemini") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ prompt: prompt, max_tokens: 1200 })
    })
    .then(function(r) {
        if (r.status === 401) throw new Error('Session expired. Please refresh and log in again.');
        return r.json();
    })
    .then(function(res) {
        if (res.success && typeof res.data === 'object') {
            var d = res.data;
            var fields = {
                short_description: d.short_description,
                description:       d.description,
                meta_title:        d.meta_title,
                meta_description:  d.meta_description,
                scent_notes:       d.scent_notes
            };
            Object.entries(fields).forEach(function([k, v]) {
                var el = document.querySelector('[name=' + k + ']');
                if (el && v) el.value = v;
            });
            var tag = res.provider === 'groq' ? ' (via Groq)' : '';
            window.showToast && window.showToast('Content regenerated' + tag + '!', 'success');
        } else {
            window.showToast && window.showToast(res.error || 'AI could not generate content', 'error');
        }
    })
    .catch(function(e) {
        window.showToast && window.showToast(e.message || 'Request failed', 'error');
    })
    .finally(function() { done && done(); });
}
</script>
@endpush
@endsection
