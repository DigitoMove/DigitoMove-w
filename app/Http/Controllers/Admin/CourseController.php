<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    use HandlesUploads;
    public function index() { return view('admin.courses.index', ['courses' => Course::withCount(['lessons', 'applications'])->latest()->paginate(12)]); }
    public function create() { return view('admin.courses.form', ['course' => new Course()]); }
    public function edit(Course $course) { $course->load('lessons'); return view('admin.courses.form', compact('course')); }
    public function store(Request $request) { $course = Course::create($this->validated($request)); return redirect()->route('admin.courses.edit', $course)->with('success', 'Course created.'); }
    public function update(Request $request, Course $course) { $course->update($this->validated($request, $course)); return back()->with('success', 'Course updated.'); }
    public function destroy(Course $course) { $course->delete(); return back()->with('success', 'Course deleted.'); }

    private function validated(Request $request, ?Course $course = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'], 'slug' => ['nullable', 'string', 'max:180', Rule::unique('courses')->ignore($course)],
            'level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])], 'summary' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'], 'tuition_fee' => ['required', 'numeric', 'min:0'], 'application_fee' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'string', 'max:100'], 'delivery_mode' => ['required', Rule::in(['online', 'in_person', 'hybrid'])],
            'status' => ['required', Rule::in(['draft', 'published'])], 'applications_open' => ['nullable', 'boolean'], 'cover_image' => ['nullable', 'image', 'max:5120'],
        ]);
        $data['slug'] = Str::slug(($data['slug'] ?? null) ?: $data['title']); $data['applications_open'] = $request->boolean('applications_open');
        if ($cover = $this->upload($request->file('cover_image'), 'courses')) $data['cover_image'] = $cover; else unset($data['cover_image']);
        return $data;
    }
}
