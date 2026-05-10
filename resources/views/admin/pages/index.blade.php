@extends('layouts.admin')
@section('title', 'Page Content')
@section('breadcrumb', 'System')

@section('content')
<div class="p-6 lg:p-8 max-w-4xl" x-data="{ tab: localStorage.getItem('adm-pages-tab') || 'about', setTab(t){ this.tab=t; localStorage.setItem('adm-pages-tab',t); } }">

    <div class="mb-8">
        <h1 class="text-2xl font-semibold" style="color:var(--adm-text-strong);">Page Content</h1>
        <p class="text-sm mt-1" style="color:var(--adm-muted);">Edit the content shown on your static information pages. Plain text — line breaks are preserved.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm flex items-center gap-3"
         style="background:var(--adm-success-bg);border:1px solid var(--adm-success-fg);color:var(--adm-success-fg);">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.pages.update') }}">
        @csrf @method('PUT')

        {{-- Tabs --}}
        <div class="flex gap-1 border-b mb-6 overflow-x-auto" style="border-color:var(--adm-border);">
            @foreach(['about'=>'About Us','faq'=>'FAQ','shipping_returns'=>'Shipping & Returns','privacy_policy'=>'Privacy Policy','terms'=>'Terms','cookie_policy'=>'Cookie Policy'] as $key=>$label)
            <button type="button" @click="setTab('{{ $key }}')"
                    :class="tab==='{{ $key }}' ? 'border-b-2 font-semibold' : ''"
                    class="px-4 py-2.5 text-xs tracking-wider uppercase transition-colors -mb-px whitespace-nowrap"
                    :style="tab==='{{ $key }}' ? 'border-color:var(--adm-gold);color:var(--adm-gold);' : 'color:var(--adm-muted);'">
                {{ $label }}
            </button>
            @endforeach
        </div>

        @foreach([
            'about' => ['label'=>'About Us','placeholder'=>'Tell your brand story — who you are, what you make, and why you make it…'],
            'faq' => ['label'=>'FAQ','placeholder'=>"Q: How do I use a diffuser?\nA: Add a few drops of essential oil with water…\n\nQ: Do you offer returns?\nA: Yes, within 7 days of delivery…"],
            'shipping_returns' => ['label'=>'Shipping & Returns','placeholder'=>'Describe your delivery timeframes, costs, and return/refund policy…'],
            'privacy_policy' => ['label'=>'Privacy Policy','placeholder'=>'Explain how you collect, use, and protect customer data…'],
            'terms' => ['label'=>'Terms of Service','placeholder'=>'List your terms of sale, warranties, and dispute resolution process…'],
            'cookie_policy' => ['label'=>'Cookie Policy','placeholder'=>'Explain what cookies you use, why, and how visitors can manage them…'],
        ] as $key => $info)
        <div x-show="tab==='{{ $key }}'" style="display:none;">
            <div class="adm-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold" style="color:var(--adm-text-strong);">{{ $info['label'] }}</h3>
                        <p class="text-xs mt-0.5" style="color:var(--adm-muted);">
                            Public URL: <a href="{{ route(match($key) { 'shipping_returns' => 'shipping-returns', 'privacy_policy' => 'privacy-policy', 'cookie_policy' => 'cookie-policy', default => $key }) }}"
                                           target="_blank" style="color:var(--adm-gold);">
                                /{{ str_replace('_', '-', $key) }}
                            </a>
                        </p>
                    </div>
                </div>
                <textarea name="page_{{ $key }}" rows="22"
                          placeholder="{{ $info['placeholder'] }}"
                          class="w-full px-4 py-3 text-sm font-sans leading-relaxed resize-y"
                          style="background:var(--adm-surface-alt);border:1px solid var(--adm-border);color:var(--adm-text);"
                          onfocus="this.style.borderColor='var(--adm-gold)'" onblur="this.style.borderColor='var(--adm-border)'">{{ $settings['page_' . $key] ?? '' }}</textarea>
            </div>
        </div>
        @endforeach

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-8 py-3 text-xs tracking-widest uppercase font-medium transition-opacity hover:opacity-85"
                    style="background:var(--adm-accent);color:#F5EDE4;">
                Save All Pages
            </button>
        </div>
    </form>
</div>
@endsection
