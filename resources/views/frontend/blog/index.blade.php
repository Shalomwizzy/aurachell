@extends('layouts.app')

@section('title', 'Blog — Aurachell')
@section('meta_description', 'Explore our journal: wellness rituals, scent guides, and the art of creating a calm, luxurious home.')
@section('og_title', 'The Aurachell Journal')

@section('content')
<section class="py-16 md:py-24" style="background:var(--color-surface)">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14">
            <p class="text-xs tracking-[0.3em] uppercase mb-3" style="color:var(--color-text-muted)">The Journal</p>
            <h1 class="font-display text-4xl md:text-5xl mb-4" style="color:var(--color-text-dark)">Stories & Rituals</h1>
            <p class="max-w-xl mx-auto text-sm leading-relaxed" style="color:var(--color-text-muted)">
                Scent guides, wellness rituals, and the art of transforming your space into a sanctuary.
            </p>
        </div>

        @if($posts->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <article class="group flex flex-col rounded-sm overflow-hidden"
                     style="background:white;box-shadow:0 1px 4px rgba(55,18,32,0.08);">
                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden aspect-[16/9]">
                    @if($post->cover_image)
                    <img src="{{ asset('images/blog/' . $post->cover_image) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                    <div class="w-full h-full flex items-center justify-center"
                         style="background:linear-gradient(135deg,#F7F2EB,rgba(55,18,32,0.08))">
                        <svg class="w-10 h-10 opacity-30" style="color:var(--color-accent)" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3C8.686 3 6 5.686 6 9c0 4.418 6 12 6 12s6-7.582 6-12c0-3.314-2.686-6-6-6zm0 8a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </div>
                    @endif
                </a>
                <div class="p-5 flex flex-col flex-1">
                    @if($post->tags && count($post->tags))
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        @foreach(array_slice($post->tags, 0, 3) as $tag)
                        <span class="text-[10px] tracking-wider uppercase px-2 py-0.5 rounded-full"
                              style="background:rgba(55,18,32,0.08);color:var(--color-accent)">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                    <a href="{{ route('blog.show', $post->slug) }}"
                       class="font-display text-lg leading-snug mb-2 hover:opacity-70 transition-opacity"
                       style="color:var(--color-text-dark)">{{ $post->title }}</a>
                    @if($post->excerpt)
                    <p class="text-sm leading-relaxed flex-1 mb-4" style="color:var(--color-text-muted)">
                        {{ Str::limit($post->excerpt, 120) }}
                    </p>
                    @endif
                    <div class="flex items-center justify-between mt-auto pt-3"
                         style="border-top:1px solid rgba(55,18,32,0.06)">
                        <span class="text-xs" style="color:var(--color-text-muted)">
                            {{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}
                        </span>
                        <span class="text-xs" style="color:var(--color-text-muted)">{{ $post->reading_time }} min read</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        @if($posts->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $posts->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-24" style="color:var(--color-text-muted)">
            <p class="text-lg">No posts published yet. Check back soon.</p>
        </div>
        @endif

    </div>
</section>
@endsection
