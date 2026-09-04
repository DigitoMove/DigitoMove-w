<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageSectionController extends Controller
{
    public function store(Request $request, Page $page)
    {
        $page->sections()->create($this->validated($request));

        return back()->with('success', 'Section added.');
    }

    public function update(Request $request, PageSection $section)
    {
        $section->update($this->validated($request));

        return back()->with('success', 'Section updated.');
    }

    public function destroy(PageSection $section)
    {
        $section->delete();

        return back()->with('success', 'Section removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['hero', 'content', 'features', 'stats', 'cta'])],
            'heading' => ['nullable', 'string', 'max:180'],
            'body' => ['nullable', 'string'],
            'cta_label' => ['nullable', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);
        $data['is_visible'] = $request->boolean('is_visible');

        return $data;
    }
}
