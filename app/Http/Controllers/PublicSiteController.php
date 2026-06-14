<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Page;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Project;
use App\Models\Course;
use App\Models\CourseApplication;
use App\Models\Payment;
use Illuminate\Http\Request;

class PublicSiteController extends Controller
{
    public function home(Request $request)
    {
        return $this->render($request, 'home', 'public.home');
    }

    public function about(Request $request)
    {
        return $this->render($request, 'about-us', 'public.about');
    }

    public function services(Request $request)
    {
        return $this->render($request, 'services', 'public.services');
    }

    public function work(Request $request)
    {
        $page = Page::where('slug', 'work')->where('status', 'published')->first();
        $projects = Project::where('status', 'published')->latest('published_at')->get();
        $this->track($request, $page);
        return view('public.work', compact('page', 'projects'));
    }

    public function insights(Request $request)
    {
        $page = Page::where('slug', 'insights')->where('status', 'published')->first();
        $posts = Post::with('author')->where('status', 'published')->latest('published_at')->paginate(9);
        $this->track($request, $page);
        return view('public.insights', compact('page', 'posts'));
    }

    public function privacy(Request $request)
    {
        return $this->render($request, 'privacy', 'public.privacy');
    }

    public function contact(Request $request)
    {
        return $this->render($request, 'contact', 'public.contact');
    }

    public function page(Request $request, Page $page)
    {
        abort_unless($page->status === 'published', 404);
        $this->track($request, $page);

        return view('public.page', compact('page'));
    }

    public function project(Project $project)
    {
        abort_unless($project->status === 'published', 404);
        return view('public.project', compact('project'));
    }

    public function post(Post $post)
    {
        abort_unless($post->status === 'published', 404);
        return view('public.post', compact('post'));
    }

    public function learning()
    {
        return view('public.learning', ['courses' => Course::where('status', 'published')->withCount('lessons')->get()]);
    }

    public function course(Course $course)
    {
        abort_unless($course->status === 'published', 404);
        $course->load('lessons');
        return view('public.course', compact('course'));
    }

    public function apply(Request $request, Course $course)
    {
        abort_unless($course->status === 'published' && $course->applications_open, 404);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:40'], 'education_level' => ['nullable', 'string', 'max:120'],
            'motivation' => ['nullable', 'string', 'max:2000'],
        ]);
        $application = $course->applications()->create($data + ['user_id' => auth()->id()]);
        $payment = $application->payments()->create([
            'amount' => $course->application_fee, 'reference' => 'DM-'.strtoupper(substr(md5(uniqid()), 0, 10)),
            'status' => $course->application_fee > 0 ? 'pending' : 'paid', 'paid_at' => $course->application_fee > 0 ? null : now(),
        ]);
        return view('public.application-success', compact('application', 'payment'));
    }

    public function submitInquiry(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Inquiry::create($data + ['source' => 'website', 'status' => 'new']);

        return back()->with('success', 'Thanks. Your message is in our hands and we will get back to you shortly.');
    }

    private function render(Request $request, string $slug, string $view)
    {
        $page = Page::with('sections')->where('slug', $slug)->where('status', 'published')->first();
        $this->track($request, $page);

        return view($view, compact('page'));
    }

    private function track(Request $request, ?Page $page): void
    {
        PageView::create([
            'page_id' => $page?->id,
            'path' => '/'.$request->path(),
            'referrer' => parse_url((string) $request->headers->get('referer'), PHP_URL_HOST) ?: 'direct',
            'visitor_hash' => hash('sha256', $request->ip().'|'.$request->userAgent()),
            'viewed_at' => now(),
        ]);
    }
}
