<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function create(Course $course) { return view('admin.lessons.form', ['course' => $course, 'lesson' => new Lesson()]); }
    public function edit(Lesson $lesson) { return view('admin.lessons.form', ['course' => $lesson->course, 'lesson' => $lesson]); }
    public function store(Request $request, Course $course) { $course->lessons()->create($this->validated($request)); return redirect()->route('admin.courses.edit', $course)->with('success', 'Lesson added.'); }
    public function update(Request $request, Lesson $lesson) { $lesson->update($this->validated($request)); return back()->with('success', 'Lesson updated.'); }
    public function destroy(Lesson $lesson) { $course = $lesson->course; $lesson->delete(); return redirect()->route('admin.courses.edit', $course)->with('success', 'Lesson deleted.'); }

    private function validated(Request $request): array
    {
        $data = $request->validate(['title' => ['required', 'string', 'max:180'], 'summary' => ['nullable', 'string'], 'content' => ['nullable', 'string'], 'video_url' => ['nullable', 'url'], 'resource_url' => ['nullable', 'url'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_preview' => ['nullable', 'boolean']]);
        $data['slug'] = Str::slug($data['title']); $data['is_preview'] = $request->boolean('is_preview');
        $data['content'] = strip_tags($data['content'] ?? '', '<p><br><h2><h3><h4><strong><em><ul><ol><li><blockquote><a><code><pre>');
        return $data;
    }
}
