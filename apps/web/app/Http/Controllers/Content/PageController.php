<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::withCount(['sections', 'views'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('content.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('content.pages.form', ['page' => new Page()]);
    }

    public function store(Request $request)
    {
        $page = Page::create($this->validated($request));

        return redirect()->route('content-pages.edit', $page)->with('success', 'Page created. Add sections when you are ready.');
    }

    public function edit(Page $page)
    {
        $page->load('sections');

        return view('content.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $page->update($this->validated($request, $page));

        return back()->with('success', 'Page details updated.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('content-pages.index')->with('success', 'Page deleted.');
    }

    private function validated(Request $request, ?Page $page = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('pages')->ignore($page)],
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['draft', 'review', 'published'])],
            'seo_title' => ['nullable', 'string', 'max:160'],
            'seo_description' => ['nullable', 'string', 'max:300'],
        ]);

        $data['slug'] = Str::slug(($data['slug'] ?? null) ?: $data['title']);
        $data['published_at'] = $data['status'] === 'published' ? ($page?->published_at ?: now()) : null;

        return $data;
    }
}
