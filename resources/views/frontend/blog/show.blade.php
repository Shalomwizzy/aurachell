@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' — Aurachell')
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('og_title', $post->title)
@section('og_type', 'article')
@if($post->cover_image)
@section('og_image', asset('images/blog/' . $post->cover_image))
@endif

@section('content')

{{-- Hero --}}
<div class="relative w-full" style="max-height:480px;overflow:hidden;">
    @if($post->cover_image)
    <img src="{{ asset('images/blog/' . $post->cover_image) }}" alt="{{ $post->title }}"
         class="w-full object-cover" style="max-height:480px">
    <div class="absolute inset-0" style="background:linear-gradient(to bottom,rgba(55,18,32,0.1),rgba(55,18,32,0.7))"></div>
    @else
    <div class="w-full h-72" style="background:linear-gradient(135deg,#F7F2EB,rgba(55,18,32,0.08))"></div>
    @endif
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs mb-8" style="color:var(--color-text-muted)">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span>/</span>
        <a href="{{ route('blog.index') }}" class="hover:underline">Journal</a>
        <span>/</span>
        <span class="truncate" style="max-width:200px">{{ $post->title }}</span>
    </nav>

    {{-- Tags --}}
    @if($post->tags && count($post->tags))
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($post->tags as $tag)
        <span class="text-[10px] tracking-widest uppercase px-2.5 py-1 rounded-full"
              style="background:rgba(55,18,32,0.08);color:var(--color-accent)">{{ $tag }}</span>
        @endforeach
    </div>
    @endif

    <h1 class="font-display text-3xl md:text-4xl leading-snug mb-5" style="color:var(--color-text-dark)">
        {{ $post->title }}
    </h1>

    <div class="flex items-center gap-4 mb-10 pb-6" style="border-bottom:1px solid rgba(55,18,32,0.1)">
        <div>
            <p class="text-sm font-medium" style="color:var(--color-text-dark)">{{ $post->author?->name ?? 'Aurachell' }}</p>
            <p class="text-xs" style="color:var(--color-text-muted)">
                {{ $post->published_at?->format('d F Y') ?? $post->created_at->format('d F Y') }}
                &middot; {{ $post->reading_time }} min read
                &middot; {{ number_format($post->views) }} views
            </p>
        </div>
    </div>

    {{-- Content --}}
    <div class="prose prose-lg max-w-none"
         style="--tw-prose-body:var(--color-text-dark);--tw-prose-headings:var(--color-text-dark);--tw-prose-links:var(--color-accent);--tw-prose-bold:var(--color-text-dark);">
        {!! $post->content !!}
    </div>

    {{-- Share --}}
    <div class="mt-12 pt-8 flex items-center gap-4" style="border-top:1px solid rgba(55,18,32,0.1)">
        <span class="text-xs uppercase tracking-widest" style="color:var(--color-text-muted)">Share</span>
        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}"
           target="_blank" rel="noopener"
           class="text-xs px-3 py-1.5 rounded-full transition-opacity hover:opacity-70"
           style="background:rgba(55,18,32,0.08);color:var(--color-accent)">Twitter / X</a>
        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}"
           target="_blank" rel="noopener"
           class="text-xs px-3 py-1.5 rounded-full transition-opacity hover:opacity-70"
           style="background:rgba(55,18,32,0.08);color:var(--color-accent)">WhatsApp</a>
    </div>
</div>

{{-- Related posts --}}
@if($related->count())
<section class="py-16" style="background:var(--color-surface)">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-2xl mb-8" style="color:var(--color-text-dark)">You might also like</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($related as $rel)
            <a href="{{ route('blog.show', $rel->slug) }}"
               class="group block rounded-sm overflow-hidden"
               style="background:white;box-shadow:0 1px 4px rgba(55,18,32,0.08);">
                @if($rel->cover_image)
                <div class="overflow-hidden aspect-video">
                    <img src="{{ asset('images/blog/' . $rel->cover_image) }}" alt="{{ $rel->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                @endif
                <div class="p-4">
                    <p class="font-display text-base leading-snug" style="color:var(--color-text-dark)">{{ $rel->title }}</p>
                    <p class="text-xs mt-1" style="color:var(--color-text-muted)">{{ $rel->reading_time }} min read</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
