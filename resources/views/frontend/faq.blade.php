@extends('layouts.app')
@section('title', 'FAQ — Aurachell')
@section('meta_description', 'Frequently asked questions about Aurachell diffusers, orders, shipping, and returns.')

@section('content')
<div class="bg-sage/5 border-b border-sand/30 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-[10px] text-text-muted mb-4 font-sans tracking-[0.2em] uppercase">
            <a href="{{ route('home') }}" class="hover:text-sage transition-colors">Home</a>
            <span class="text-sand">—</span>
            <span class="text-text-dark">FAQ</span>
        </nav>
        <h1 class="font-display text-4xl lg:text-5xl text-text-dark tracking-tight">Frequently Asked Questions</h1>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    @if($content)
    @foreach(explode("\n\n", $content) as $block)
    @php $block = trim($block); @endphp
    @if($block)
    @if(strtoupper(trim($block)) === trim($block) && strlen($block) < 80 && !str_contains($block, '.'))
    <h2 class="font-display text-2xl text-text-dark mt-10 mb-6 tracking-tight">{{ ucwords(strtolower(trim($block))) }}</h2>
    @elseif(str_starts_with($block, 'Q:') || str_starts_with($block, 'Q '))
    @php
        $lines = explode("\n", $block, 2);
        $q = ltrim(ltrim($lines[0], 'Q:'), ' ');
        $a = isset($lines[1]) ? ltrim(ltrim(trim($lines[1]), 'A:'), ' ') : '';
    @endphp
    <div class="mb-6 pb-6 border-b border-sand/30 last:border-0">
        <h3 class="font-display text-lg text-text-dark mb-2">{{ $q }}</h3>
        @if($a)<p class="text-text-muted font-sans leading-relaxed">{{ $a }}</p>@endif
    </div>
    @elseif(str_starts_with($block, 'A:'))
    {{-- skip standalone answers --}}
    @else
    <p class="mb-5 text-text-muted font-sans leading-relaxed whitespace-pre-line">{{ $block }}</p>
    @endif
    @endif
    @endforeach
    @endif
    <div class="mt-12 pt-8 border-t border-sand/30">
        <p class="text-sm text-text-muted font-sans">Still have questions? <a href="{{ route('contact') }}" class="text-sage underline">Contact us</a> — we're happy to help.</p>
    </div>
</div>
@endsection
