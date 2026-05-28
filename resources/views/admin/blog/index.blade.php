@extends('layouts.admin')
@section('title', 'Blog Posts')
@section('breadcrumb', 'Content')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--adm-text);">Blog Posts</h1>
            <p class="text-xs mt-1" style="color:var(--adm-muted);">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</p>
        </div>
        <a href="{{ route('admin.blog.create') }}"
           class="px-5 py-2.5 text-xs tracking-[0.15em] uppercase font-medium transition-opacity hover:opacity-90"
           style="background:#371220;color:#FAF5ED;">
            + New Post
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(55,18,32,0.12);border:1px solid rgba(55,18,32,0.3);color:#C9A96F;">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-hidden" style="border:1px solid var(--adm-border);">
        <table class="w-full text-sm">
            <thead>
                <tr style="border-bottom:1px solid var(--adm-border);background:rgba(55,18,32,0.04);">
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal" style="color:var(--adm-muted);">Title</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal hidden md:table-cell" style="color:var(--adm-muted);">Author</th>
                    <th class="text-center px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal hidden lg:table-cell" style="color:var(--adm-muted);">Status</th>
                    <th class="text-center px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal hidden lg:table-cell" style="color:var(--adm-muted);">Views</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal hidden md:table-cell" style="color:var(--adm-muted);">Date</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr style="border-bottom:1px solid var(--adm-border);" class="transition-colors hover:bg-[rgba(55,18,32,0.03)]">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($post->cover_image)
                            <img src="{{ asset('images/blog/' . $post->cover_image) }}"
                                 alt="" class="w-10 h-10 object-cover rounded shrink-0">
                            @else
                            <div class="w-10 h-10 rounded shrink-0 flex items-center justify-center"
                                 style="background:rgba(55,18,32,0.12);">
                                <svg class="w-4 h-4" style="color:var(--adm-muted)" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-medium truncate" style="color:var(--adm-text);" title="{{ $post->title }}">
                                    {{ Str::limit($post->title, 55) }}
                                </p>
                                <p class="text-xs mt-0.5 truncate" style="color:var(--adm-muted);">/blog/{{ $post->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell text-xs" style="color:var(--adm-muted);">
                        {{ $post->author?->name ?? 'Unknown' }}
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-center">
                        @if($post->is_published)
                        <span class="text-[10px] px-2.5 py-1 tracking-wider uppercase"
                              style="background:rgba(201,169,111,0.12);color:#C9A96F;">Published</span>
                        @else
                        <span class="text-[10px] px-2.5 py-1 tracking-wider uppercase"
                              style="background:rgba(212,185,154,0.1);color:var(--adm-muted);">Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-center text-xs" style="color:var(--adm-muted);">
                        {{ number_format($post->views) }}
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell text-xs" style="color:var(--adm-muted);">
                        {{ ($post->published_at ?? $post->created_at)->format('d M Y') }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                               class="text-xs transition-colors" style="color:var(--adm-muted);"
                               onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
                               View ↗
                            </a>
                            <a href="{{ route('admin.blog.edit', $post) }}"
                               class="text-xs transition-colors" style="color:var(--adm-gold);"
                               onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                               Edit
                            </a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}"
                                  onsubmit="return confirm('Delete \'{{ addslashes($post->title) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs transition-colors"
                                        style="color:rgba(55,18,32,0.5);"
                                        onmouseover="this.style.color='#371220'" onmouseout="this.style.color='rgba(55,18,32,0.5)'">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center text-sm" style="color:var(--adm-muted);">
                        No blog posts yet.
                        <a href="{{ route('admin.blog.create') }}" class="ml-2 underline" style="color:var(--adm-gold);">Create the first one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($posts->hasPages())
    <div class="mt-5">{{ $posts->links() }}</div>
    @endif

</div>
@endsection
