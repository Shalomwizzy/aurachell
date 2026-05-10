@extends('layouts.app')
@section('title', 'Request a Product — Aurachell')
@section('meta_description', 'Can\'t find what you\'re looking for? Submit a product request and we\'ll do our best to source it for you.')

@section('content')

{{-- Breadcrumb --}}
<div class="border-b border-sand/30 bg-white/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex items-center gap-2 text-[10px] text-text-muted font-sans tracking-[0.2em] uppercase">
            <a href="{{ route('home') }}" class="hover:text-mahogany transition-colors">Home</a>
            <span class="text-sand">—</span>
            <a href="{{ route('shop') }}" class="hover:text-mahogany transition-colors">Shop</a>
            <span class="text-sand">—</span>
            <span class="text-text-dark">Request a Product</span>
        </nav>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Header --}}
    <div class="text-center mb-12">
        <p class="font-sans text-[10px] tracking-[0.3em] uppercase text-mahogany mb-3">Can't Find It?</p>
        <h1 class="font-display text-4xl lg:text-5xl text-text-dark leading-tight mb-4">Request a Product</h1>
        <p class="font-sans text-text-muted text-base leading-relaxed max-w-xl mx-auto">Tell us what you're looking for — the scent, the style, the occasion — and we'll do our best to source it for you.</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-8 px-6 py-4 bg-green-50 border border-green-200 text-green-800 text-sm font-sans">
        {{ session('success') }}
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('product-request.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white shadow-luxury p-8 lg:p-10 space-y-7"
          x-data="{ preview: null, dragover: false }">
        @csrf

        {{-- Name + Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-2">Your Name <span class="text-mahogany">*</span></label>
                <input type="text" name="customer_name"
                       value="{{ old('customer_name', auth()->user()?->name) }}"
                       required
                       class="w-full border-b border-sand bg-transparent py-3 text-text-dark placeholder-text-muted focus:outline-none focus:border-mahogany transition-colors text-sm"
                       placeholder="Full name">
                @error('customer_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-2">Email Address <span class="text-mahogany">*</span></label>
                <input type="email" name="customer_email"
                       value="{{ old('customer_email', auth()->user()?->email) }}"
                       required
                       class="w-full border-b border-sand bg-transparent py-3 text-text-dark placeholder-text-muted focus:outline-none focus:border-mahogany transition-colors text-sm"
                       placeholder="hello@example.com">
                @error('customer_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Product Name --}}
        <div>
            <label class="block font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-2">Product Name / What You're Looking For <span class="text-mahogany">*</span></label>
            <input type="text" name="product_name"
                   value="{{ old('product_name') }}"
                   required
                   class="w-full border-b border-sand bg-transparent py-3 text-text-dark placeholder-text-muted focus:outline-none focus:border-mahogany transition-colors text-sm"
                   placeholder="e.g. Oud & Rose Reed Diffuser, Lavender Car Diffuser...">
            @error('product_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-2">Description <span class="text-text-muted font-normal normal-case tracking-normal text-xs">(optional)</span></label>
            <textarea name="description" rows="4"
                      class="w-full border-b border-sand bg-transparent py-3 text-text-dark placeholder-text-muted focus:outline-none focus:border-mahogany transition-colors resize-none text-sm leading-relaxed"
                      placeholder="Describe what you're looking for — the vibe, who it's for, what occasion...">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Scent + Budget --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-2">Scent Preference <span class="text-text-muted font-normal normal-case tracking-normal text-xs">(optional)</span></label>
                <input type="text" name="scent_preference"
                       value="{{ old('scent_preference') }}"
                       class="w-full border-b border-sand bg-transparent py-3 text-text-dark placeholder-text-muted focus:outline-none focus:border-mahogany transition-colors text-sm"
                       placeholder="e.g. Woody, Floral, Fresh, Oud...">
                @error('scent_preference')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-2">Your Budget <span class="text-text-muted font-normal normal-case tracking-normal text-xs">(optional)</span></label>
                <input type="text" name="budget"
                       value="{{ old('budget') }}"
                       class="w-full border-b border-sand bg-transparent py-3 text-text-dark placeholder-text-muted focus:outline-none focus:border-mahogany transition-colors text-sm"
                       placeholder="e.g. ₦10,000 – ₦30,000">
                @error('budget')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Image Upload --}}
        <div>
            <label class="block font-sans text-[10px] tracking-[0.2em] uppercase text-text-muted mb-3">Reference Image <span class="text-text-muted font-normal normal-case tracking-normal text-xs">(optional — helps us find exactly what you want)</span></label>

            <div class="relative border-2 border-dashed border-sand/60 hover:border-mahogany/40 transition-colors cursor-pointer"
                 :class="dragover ? 'border-mahogany bg-mahogany/5' : ''"
                 @dragover.prevent="dragover = true"
                 @dragleave="dragover = false"
                 @drop.prevent="dragover = false; const f = $event.dataTransfer.files[0]; if(f){ preview = URL.createObjectURL(f); $refs.imgInput.files = $event.dataTransfer.files; }"
                 @click="$refs.imgInput.click()">

                <input type="file" name="image" accept="image/*" x-ref="imgInput" class="hidden"
                       @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">

                {{-- Preview --}}
                <template x-if="preview">
                    <div class="relative">
                        <img :src="preview" class="w-full max-h-56 object-contain p-4">
                        <button type="button"
                                @click.stop="preview = null; $refs.imgInput.value = ''"
                                class="absolute top-2 right-2 w-7 h-7 bg-white/90 shadow text-text-dark flex items-center justify-center text-lg leading-none hover:bg-red-50 hover:text-red-600 transition-colors">
                            ×
                        </button>
                    </div>
                </template>

                {{-- Placeholder --}}
                <template x-if="!preview">
                    <div class="py-10 flex flex-col items-center gap-3 text-text-muted pointer-events-none">
                        <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="font-sans text-sm">Drop an image here, or <span class="text-mahogany underline underline-offset-2">browse</span></p>
                        <p class="font-sans text-xs opacity-60">JPG, PNG, WEBP · Max 4MB</p>
                    </div>
                </template>
            </div>
            @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Submit --}}
        <div class="pt-2">
            <button type="submit" class="btn-primary w-full sm:w-auto px-12">Submit Request</button>
        </div>
    </form>

</div>

@endsection
