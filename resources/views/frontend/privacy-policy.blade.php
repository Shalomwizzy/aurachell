@extends('layouts.app')
@section('title', 'Privacy Policy — Aurachell')
@section('meta_description', 'Aurachell privacy policy — how we collect, use, and protect your personal information.')

@section('content')
<div class="bg-sage/5 border-b border-sand/30 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-[10px] text-text-muted mb-4 font-sans tracking-[0.2em] uppercase">
            <a href="{{ route('home') }}" class="hover:text-sage transition-colors">Home</a>
            <span class="text-sand">—</span>
            <span class="text-text-dark">Privacy Policy</span>
        </nav>
        <h1 class="font-display text-4xl lg:text-5xl text-text-dark tracking-tight">Privacy Policy</h1>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
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
