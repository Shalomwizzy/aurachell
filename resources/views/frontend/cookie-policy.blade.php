@extends('layouts.app')
@section('title', 'Cookie Policy — Aurachell')
@section('meta_description', 'Aurachell cookie policy — how we use cookies and how to manage your preferences.')

@section('content')
<div class="bg-sage/5 border-b border-sand/30 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-[10px] text-text-muted mb-4 font-sans tracking-[0.2em] uppercase">
            <a href="{{ route('home') }}" class="hover:text-sage transition-colors">Home</a>
            <span class="text-sand">—</span>
            <span class="text-text-dark">Cookie Policy</span>
        </nav>
        <h1 class="font-display text-4xl lg:text-5xl text-text-dark tracking-tight">Cookie Policy</h1>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Cookie preference box --}}
    <div class="mb-10 p-5 border border-sand/40 bg-sand/10"
         x-data="{ consent: localStorage.getItem('aurachell_cookie_consent') }">
        <p class="text-xs tracking-widest uppercase font-sans text-text-muted mb-3">Your Current Preference</p>
        <template x-if="consent === 'accepted'">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full" style="background:#16a34a;"></span>
                    <span class="text-sm font-sans text-text-dark">You have accepted cookies.</span>
                </div>
                <button @click="localStorage.setItem('aurachell_cookie_consent','declined'); consent='declined';"
                        class="text-xs px-4 py-2 border border-sand/60 text-text-muted hover:text-text-dark transition-colors font-sans">
                    Withdraw Consent
                </button>
            </div>
        </template>
        <template x-if="consent === 'declined'">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                    <span class="text-sm font-sans text-text-dark">You have declined non-essential cookies.</span>
                </div>
                <button @click="localStorage.setItem('aurachell_cookie_consent','accepted'); consent='accepted'; if(window.loadGA) loadGA();"
                        class="text-xs px-4 py-2 font-sans font-medium transition-opacity hover:opacity-90"
                        style="background:#371220;color:#FAF5ED;">
                    Accept Cookies
                </button>
            </div>
        </template>
        <template x-if="!consent">
            <div class="flex items-center justify-between gap-4">
                <span class="text-sm font-sans text-text-muted">You have not set a preference yet.</span>
                <div class="flex gap-2">
                    <button @click="localStorage.setItem('aurachell_cookie_consent','declined'); consent='declined';"
                            class="text-xs px-4 py-2 border border-sand/60 text-text-muted hover:text-text-dark transition-colors font-sans">
                        Decline
                    </button>
                    <button @click="localStorage.setItem('aurachell_cookie_consent','accepted'); consent='accepted'; if(window.loadGA) loadGA();"
                            class="text-xs px-4 py-2 font-sans font-medium transition-opacity hover:opacity-90"
                            style="background:#371220;color:#FAF5ED;">
                        Accept
                    </button>
                </div>
            </div>
        </template>
    </div>

    @if($content)
    @foreach(explode("\n\n", $content) as $block)
    @php $block = trim($block); @endphp
    @if($block)
    @if(strtoupper(trim($block)) === trim($block) && strlen($block) < 80 && !str_contains($block, '.'))
    <h2 class="font-display text-2xl text-text-dark mt-10 mb-4 tracking-tight">{{ ucwords(strtolower(trim($block))) }}</h2>
    @elseif(str_starts_with($block, '-'))
    <ul class="list-none space-y-1 mb-5">
        @foreach(explode("\n", $block) as $li)
        @php $li = ltrim(ltrim(trim($li), '-'), ' '); @endphp
        @if($li)<li class="flex items-start gap-2 text-sm text-text-muted font-sans"><span class="text-sage mt-1">—</span>{{ $li }}</li>@endif
        @endforeach
    </ul>
    @else
    <p class="mb-4 text-text-muted font-sans leading-relaxed whitespace-pre-line">{{ $block }}</p>
    @endif
    @endif
    @endforeach
    @endif

</div>
@endsection
