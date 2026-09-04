<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    use HandlesUploads;
    public function index() { return view('admin.posts.index', ['posts' => Post::with('author')->latest()->paginate(12)]); }
    public function create() { return view('admin.posts.form', ['post' => new Post()]); }
    public function edit(Post $post) { return view('admin.posts.form', compact('post')); }
    public function store(Request $request) { $post = Post::create($this->validated($request)); return redirect()->route('admin.posts.edit', $post)->with('success', 'Article created.'); }
    public function update(Request $request, Post $post) { $post->update($this->validated($request, $post)); return back()->with('success', 'Article updated.'); }
    public function destroy(Post $post) { $post->delete(); return back()->with('success', 'Article deleted.'); }

    private function validated(Request $request, ?Post $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'], 'slug' => ['nullable', 'string', 'max:180', Rule::unique('posts')->ignore($post)],
            'excerpt' => ['required', 'string', 'max:500'], 'body' => ['required', 'string'], 'category' => ['required', 'string', 'max:80'],
            'status' => ['required', Rule::in(['draft', 'review', 'published'])], 'cover_image' => ['nullable', 'image', 'max:5120'],
        ]);
        $data['slug'] = Str::slug(($data['slug'] ?? null) ?: $data['title']); $data['author_id'] = auth()->id();
        $data['body'] = strip_tags($data['body'], '<p><br><h2><h3><h4><strong><em><u><ul><ol><li><blockquote><a><img><code><pre>');
        $data['reading_minutes'] = max(1, (int) ceil(str_word_count(strip_tags($data['body'])) / 220));
        $data['published_at'] = $data['status'] === 'published' ? ($post?->published_at ?: now()) : null;
        if ($cover = $this->upload($request->file('cover_image'), 'posts')) $data['cover_image'] = $cover; else unset($data['cover_image']);
        return $data;
    }
}
