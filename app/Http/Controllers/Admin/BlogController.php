<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Traits\SecureFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    use SecureFileUpload;

    public function index()
    {
        $posts = BlogPost::with('author')->latest()->paginate(20);

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'tags'             => 'nullable|string',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'is_published'     => 'nullable|boolean',
            'cover_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data['slug']         = Str::slug($data['title']).'-'.Str::random(4);
        $data['user_id']      = auth()->id();
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;
        $data['tags']         = filled($data['tags'] ?? null)
            ? array_filter(array_map('trim', explode(',', $data['tags'])))
            : null;

        if ($request->hasFile('cover_image')) {
            $file     = $request->file('cover_image');
            $ext      = $this->safeExtension($file, ['jpg', 'jpeg', 'png', 'webp']);
            $filename = 'blog_' . time() . '.' . $ext;
            $file->move(public_path('images/blog'), $filename);
            $data['cover_image'] = $filename;
        }

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post saved.');
    }

    public function show(BlogPost $blog)
    {
        return redirect()->route('blog.show', $blog->slug);
    }

    public function edit(BlogPost $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'tags'             => 'nullable|string',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'is_published'     => 'nullable|boolean',
            'cover_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'remove_cover'     => 'nullable|boolean',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published'] && ! $blog->published_at) {
            $data['published_at'] = now();
        } elseif (! $data['is_published']) {
            $data['published_at'] = null;
        }

        $data['tags'] = filled($data['tags'] ?? null)
            ? array_filter(array_map('trim', explode(',', $data['tags'])))
            : null;

        if ($request->boolean('remove_cover') && $blog->cover_image) {
            @unlink(public_path('images/blog/' . basename($blog->cover_image)));
            $data['cover_image'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($blog->cover_image) {
                @unlink(public_path('images/blog/' . basename($blog->cover_image)));
            }
            $file     = $request->file('cover_image');
            $ext      = $this->safeExtension($file, ['jpg', 'jpeg', 'png', 'webp']);
            $filename = 'blog_' . time() . '.' . $ext;
            $file->move(public_path('images/blog'), $filename);
            $data['cover_image'] = $filename;
        }

        unset($data['remove_cover']);
        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post updated.');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->cover_image) {
            @unlink(public_path('images/blog/' . basename($blog->cover_image)));
        }
        $blog->delete();

        return back()->with('success', 'Post deleted.');
    }
}
