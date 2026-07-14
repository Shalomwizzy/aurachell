@extends('layouts.admin')
@section('title', 'Add Product')
@section('breadcrumb', 'Catalog · Products')

@section('content')
<div class="p-6 lg:p-8 max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-8 flex-wrap">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}"
               class="w-9 h-9 rounded flex items-center justify-center transition-colors"
               style="color:var(--adm-muted);background:var(--adm-surface-alt);"
               onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold" style="color:var(--adm-text-strong);">Add New Product</h1>
                <p class="text-xs mt-0.5" style="color:var(--adm-muted);">Create a new product listing for your shop</p>
            </div>
        </div>
    </div>

    {{-- AI Description Generator --}}
    <div class="adm-card p-5 mb-6">
        <div class="flex flex-wrap items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0" style="background:rgba(55,18,32,0.25);">
                <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a1 1 0 011 1v2a1 1 0 01-2 0V3a1 1 0 011-1zm0 16a1 1 0 011 1v2a1 1 0 01-2 0v-2a1 1 0 011-1zM4.22 4.22a1 1 0 011.42 0l1.41 1.42a1 1 0 01-1.42 1.41L4.22 5.64a1 1 0 010-1.42zm12.73 12.73a1 1 0 011.41 0l1.42 1.41a1 1 0 01-1.42 1.42l-1.41-1.42a1 1 0 010-1.41zM2 12a1 1 0 011-1h2a1 1 0 010 2H3a1 1 0 01-1-1zm16 0a1 1 0 011-1h2a1 1 0 010 2h-2a1 1 0 01-1-1zM4.22 19.78a1 1 0 010-1.41l1.42-1.42a1 1 0 011.41 1.42L5.64 19.78a1 1 0 01-1.42 0zm12.73-12.73a1 1 0 010-1.42l1.41-1.41a1 1 0 011.42 1.41l-1.42 1.42a1 1 0 01-1.41 0zM12 8a4 4 0 110 8 4 4 0 010-8z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium" style="color:var(--adm-text);">AI Content Generator</p>
                <p class="text-xs" style="color:var(--adm-muted);">Fill the product name first, then click Generate to auto-write descriptions, scent notes & SEO.</p>
            </div>
            <button type="button" id="prod-ai-btn" onclick="prodAiGenerate()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium rounded transition-opacity tracking-wider uppercase"
                    style="background:var(--adm-accent);color:#FFFFFF;">
                <svg id="prod-ai-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <svg id="prod-ai-spin" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span id="prod-ai-label">Generate with AI</span>
            </button>
        </div>
    </div>
    <script>
    function prodAiGenerate() {
        var name = (document.querySelector('[name=name]') || {}).value || '';
        name = name.trim();
        var cat = ((document.querySelector('[name=category_id]') || {}).selectedOptions || [{}])[0].text || '';
        if (!name) { window.showToast && window.showToast('Enter a product name first', 'error'); return; }
        var btn=document.getElementById('prod-ai-btn'), icon=document.getElementById('prod-ai-icon'), spin=document.getElementById('prod-ai-spin'), lbl=document.getElementById('prod-ai-label');
        btn.disabled=true; icon.style.display='none'; spin.style.display='inline'; lbl.textContent='Generating…';
        fetch('/admin/ai/gemini', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
            body:JSON.stringify({prompt:'You are a copywriter for Aurachell, a luxury Nigerian aromatherapy brand. Generate compelling product content for: "'+name+'" (category: '+cat+'). Return a JSON object with these exact fields: short_description (2 sentences, max 160 chars), description (rich HTML, 3-4 paragraphs, highlight ingredients/benefits/experience), meta_title (max 60 chars, SEO-optimised), meta_description (max 155 chars, compelling CTA), scent_notes (comma-separated list of 3-5 scent notes). Return ONLY valid JSON, no markdown wrapper.',max_tokens:1200})
        })
        .then(function(r){return r.json();})
        .then(function(res){
            if(res.success && typeof res.data==='object'){
                var d=res.data;
                var set=function(n,v){var el=document.querySelector('[name='+n+']');if(el&&v)el.value=v;};
                set('short_description',d.short_description); set('description',d.description);
                set('meta_title',d.meta_title); set('meta_description',d.meta_description); set('scent_notes',d.scent_notes);
                window.showToast && window.showToast('Content generated!','success');
            } else { window.showToast && window.showToast(res.error||'AI could not generate content','error'); }
        })
        .catch(function(){window.showToast && window.showToast('Request failed','error');})
        .finally(function(){btn.disabled=false;icon.style.display='inline';spin.style.display='none';lbl.textContent='Generate with AI';});
    }
    </script>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main fields (2/3) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Product Information --}}
                <div class="adm-card p-6 space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b" style="border-color:var(--adm-border);">
                        <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <h2 class="text-xs font-semibold tracking-[0.15em] uppercase" style="color:var(--adm-text);">Product Information</h2>
                    </div>
                    <div>
                        <label class="adm-label">Product Name <span style="color:var(--adm-danger-fg);">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="adm-input" placeholder="e.g. Sage & Cedarwood Ceramic Diffuser">
                        @error('name')<p class="text-xs mt-1" style="color:var(--adm-danger-fg);">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="adm-label">Category <span style="color:var(--adm-danger-fg);">*</span></label>
                            <select name="category_id" required class="adm-input">
                                <option value="">Select category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-xs mt-1" style="color:var(--adm-danger-fg);">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="adm-label">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" class="adm-input font-mono" placeholder="Auto-generated if empty">
                        </div>
                    </div>
                    <div>
                        <label class="adm-label">Short Description</label>
                        <textarea name="short_description" rows="2" class="adm-input resize-none" placeholder="Brief product summary (shown in listings)">{{ old('short_description') }}</textarea>
                    </div>
                    <div>
                        <label class="adm-label">Full Description</label>
                        <textarea name="description" rows="6" class="adm-input resize-y" placeholder="Detailed product description (HTML supported)">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- Pricing & Inventory --}}
                <div class="adm-card p-6 space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b" style="border-color:var(--adm-border);">
                        <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h2 class="text-xs font-semibold tracking-[0.15em] uppercase" style="color:var(--adm-text);">Pricing & Inventory</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="adm-label">Sale Price (₦) <span style="color:var(--adm-danger-fg);">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required class="adm-input" placeholder="0.00">
                            @error('price')<p class="text-xs mt-1" style="color:var(--adm-danger-fg);">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="adm-label">Compare-at Price (₦)</label>
                            <input type="number" name="compare_at_price" value="{{ old('compare_at_price') }}" step="0.01" min="0" class="adm-input" placeholder="Strike-through original price">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="adm-label">Stock Quantity <span style="color:var(--adm-danger-fg);">*</span></label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required class="adm-input">
                        </div>
                        <div>
                            <label class="adm-label">Low Stock Threshold</label>
                            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="0" class="adm-input">
                        </div>
                    </div>
                </div>

                {{-- Product Details --}}
                <div class="adm-card p-6 space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b" style="border-color:var(--adm-border);">
                        <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"/></svg>
                        <h2 class="text-xs font-semibold tracking-[0.15em] uppercase" style="color:var(--adm-text);">Product Details</h2>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="adm-label">Capacity (ml)</label>
                            <input type="number" name="capacity_ml" value="{{ old('capacity_ml') }}" min="0" class="adm-input" placeholder="200">
                        </div>
                        <div>
                            <label class="adm-label">Longevity</label>
                            <input type="text" name="burn_time_hours" value="{{ old('burn_time_hours') }}" maxlength="100" class="adm-input" placeholder="e.g. 40 hours or 6 months">
                        </div>
                        <div>
                            <label class="adm-label">Weight (kg)</label>
                            <input type="number" name="weight" value="{{ old('weight') }}" step="0.01" min="0" class="adm-input" placeholder="0.5">
                        </div>
                    </div>
                    <div>
                        <label class="adm-label">Scent Notes</label>
                        <input type="text" name="scent_notes" value="{{ old('scent_notes') }}" class="adm-input" placeholder="Sage, Cedarwood, White Musk (comma-separated)">
                    </div>
                    <div>
                        <label class="adm-label">How to Use <span class="text-[10px] normal-case tracking-normal" style="color:var(--adm-muted);opacity:0.7;">(optional)</span></label>
                        <textarea name="usage_notes" rows="3" maxlength="2000" class="adm-input resize-none" placeholder="Leave blank to hide this section. Otherwise, brief instructions shown on the product page.">{{ old('usage_notes') }}</textarea>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="adm-card p-6 space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b" style="border-color:var(--adm-border);">
                        <svg class="w-4 h-4" style="color:var(--adm-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <h2 class="text-xs font-semibold tracking-[0.15em] uppercase" style="color:var(--adm-text);">SEO</h2>
                    </div>
                    <div>
                        <label class="adm-label">Meta Title <span class="text-[10px] normal-case tracking-normal" style="color:var(--adm-muted);opacity:0.7;">(max 60 chars)</span></label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="60" class="adm-input" placeholder="Leave blank to use product name">
                    </div>
                    <div>
                        <label class="adm-label">Meta Description <span class="text-[10px] normal-case tracking-normal" style="color:var(--adm-muted);opacity:0.7;">(max 160 chars)</span></label>
                        <textarea name="meta_description" rows="2" maxlength="160" class="adm-input resize-none" placeholder="Search engine description">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sidebar (1/3) --}}
            <div class="space-y-6">

                {{-- Publishing --}}
                <div class="adm-card p-6 space-y-4 lg:sticky lg:top-6">
                    <h2 class="text-xs font-semibold tracking-[0.15em] uppercase pb-3 border-b" style="color:var(--adm-text);border-color:var(--adm-border);">Publishing</h2>
                    <label class="flex items-center justify-between gap-3 cursor-pointer py-1">
                        <div>
                            <p class="text-sm" style="color:var(--adm-text);">Active</p>
                            <p class="text-[10px]" style="color:var(--adm-muted);">Visible in shop</p>
                        </div>
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="w-4 h-4" style="accent-color:var(--adm-accent);">
                    </label>
                    <label class="flex items-center justify-between gap-3 cursor-pointer py-1">
                        <div>
                            <p class="text-sm" style="color:var(--adm-text);">Featured</p>
                            <p class="text-[10px]" style="color:var(--adm-muted);">Highlight on homepage</p>
                        </div>
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4" style="accent-color:var(--adm-accent);">
                    </label>

                    <div class="pt-4 border-t flex flex-col gap-2" style="border-color:var(--adm-border);">
                        <button type="submit"
                                class="w-full py-3 text-xs tracking-[0.2em] uppercase font-medium rounded transition-all"
                                style="background:var(--adm-accent);color:#FFFFFF;"
                                onmouseover="this.style.opacity='0.92'" onmouseout="this.style.opacity='1'">
                            Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                           class="w-full py-3 text-xs tracking-[0.2em] uppercase font-medium text-center rounded transition-colors"
                           style="background:var(--adm-surface-alt);color:var(--adm-muted);"
                           onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
                            Cancel
                        </a>
                    </div>
                </div>

                {{-- Images --}}
                <div class="adm-card p-6 space-y-4">
                    <h2 class="text-xs font-semibold tracking-[0.15em] uppercase pb-3 border-b" style="color:var(--adm-text);border-color:var(--adm-border);">Product Images</h2>
                    <div class="space-y-3">
                        <label id="img-drop-zone" class="block w-full aspect-square border-2 border-dashed cursor-pointer transition-colors relative overflow-hidden rounded"
                               style="border-color:var(--adm-border);background:var(--adm-surface-alt);"
                               onmouseover="if(!document.getElementById('img-previews').children.length)this.style.borderColor='var(--adm-gold)'"
                               onmouseout="if(!document.getElementById('img-previews').children.length)this.style.borderColor='var(--adm-border)'">
                            <input type="file" name="images[]" id="img-file-input" multiple accept="image/*" class="sr-only">
                            <div id="img-placeholder" class="absolute inset-0 flex flex-col items-center justify-center gap-2" style="color:var(--adm-muted);">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs">Click to upload images</span>
                                <span class="text-[10px]" style="opacity:0.7;">JPG, PNG, WEBP — max 4MB each</span>
                            </div>
                            <div id="img-previews" class="grid grid-cols-2 gap-1 p-2 absolute inset-0 overflow-auto" style="display:none;"></div>
                        </label>
                        <p class="text-[10px]" style="color:var(--adm-muted);">First image will be the primary product image.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    var input    = document.getElementById('img-file-input');
    var previews = document.getElementById('img-previews');
    var placeholder = document.getElementById('img-placeholder');
    var dropZone = document.getElementById('img-drop-zone');
    if (!input) return;

    input.addEventListener('change', function() {
        var files = Array.from(this.files);
        previews.innerHTML = '';

        if (!files.length) {
            previews.style.display = 'none';
            placeholder.style.display = 'flex';
            dropZone.style.borderStyle = 'dashed';
            dropZone.style.borderColor = 'var(--adm-border)';
            return;
        }

        files.forEach(function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var wrap = document.createElement('div');
                wrap.className = 'aspect-square overflow-hidden rounded';
                wrap.style.background = 'var(--adm-bg)';
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                wrap.appendChild(img);
                previews.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });

        previews.style.display = 'grid';
        placeholder.style.display = 'none';
        dropZone.style.borderStyle = 'solid';
        dropZone.style.borderColor = 'var(--adm-gold)';
    });
})();
</script>
@endpush

<style>
.adm-label {
    display: block;
    font-size: 10px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--adm-muted);
    margin-bottom: 8px;
    font-weight: 500;
}
.adm-input {
    width: 100%;
    background: var(--adm-surface-alt);
    border: 1px solid var(--adm-border);
    padding: 10px 14px;
    font-size: 13px;
    color: var(--adm-text);
    border-radius: 4px;
    transition: border-color .15s, box-shadow .15s;
}
.adm-input::placeholder { color: var(--adm-muted); opacity: 0.6; }
.adm-input:focus {
    outline: none;
    border-color: var(--adm-gold);
    box-shadow: 0 0 0 1px var(--adm-gold);
}
</style>
@endsection
