<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    use HandlesUploads;
    public function index() { return view('admin.projects.index', ['projects' => Project::latest()->paginate(12)]); }
    public function create() { return view('admin.projects.form', ['project' => new Project()]); }
    public function edit(Project $project) { return view('admin.projects.form', compact('project')); }
    public function store(Request $request) { $project = Project::create($this->validated($request)); return redirect()->route('admin.projects.edit', $project)->with('success', 'Work added.'); }
    public function update(Request $request, Project $project) { $project->update($this->validated($request, $project)); return back()->with('success', 'Work updated.'); }
    public function destroy(Project $project) { $project->delete(); return back()->with('success', 'Work removed.'); }

    private function validated(Request $request, ?Project $project = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'], 'slug' => ['nullable', 'string', 'max:180', Rule::unique('projects')->ignore($project)],
            'type' => ['required', Rule::in(['website', 'system', 'mobile_app', 'design', 'other'])], 'client' => ['nullable', 'string', 'max:180'],
            'summary' => ['required', 'string', 'max:500'], 'description' => ['nullable', 'string'], 'website_url' => ['nullable', 'url'],
            'demo_url' => ['nullable', 'url'], 'demo_username' => ['nullable', 'string', 'max:180'], 'demo_password' => ['nullable', 'string', 'max:180'],
            'technologies' => ['nullable', 'string'], 'status' => ['required', Rule::in(['draft', 'published'])], 'is_featured' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:5120'], 'screenshots.*' => ['nullable', 'image', 'max:5120'],
        ]);
        $data['slug'] = Str::slug(($data['slug'] ?? null) ?: $data['title']); $data['is_featured'] = $request->boolean('is_featured');
        $data['technologies'] = array_values(array_filter(array_map('trim', explode(',', $data['technologies'] ?? ''))));
        $data['published_at'] = $data['status'] === 'published' ? ($project?->published_at ?: now()) : null;
        if ($cover = $this->upload($request->file('cover_image'), 'projects')) $data['cover_image'] = $cover; else unset($data['cover_image']);
        if ($request->hasFile('screenshots')) $data['screenshots'] = collect($request->file('screenshots'))->map(fn ($file) => $this->upload($file, 'projects'))->all(); else unset($data['screenshots']);
        return $data;
    }
}
