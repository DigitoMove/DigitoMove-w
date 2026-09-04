<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Page;
use App\Models\PageView;
use App\Models\Project;
use App\Models\Post;
use App\Models\Course;
use App\Models\CourseApplication;
use App\Models\Payment;
use Carbon\Carbon;

class Analytics extends Controller
{
  public function index()
  {
    $start = now()->subDays(13)->startOfDay();
    $viewsByDay = PageView::where('viewed_at', '>=', $start)
      ->selectRaw('DATE(viewed_at) as day, COUNT(*) as total')
      ->groupBy('day')
      ->pluck('total', 'day');

    $traffic = collect(range(0, 13))->map(function ($offset) use ($start, $viewsByDay) {
      $date = $start->copy()->addDays($offset);
      return ['label' => $date->format('M j'), 'value' => (int) ($viewsByDay[$date->toDateString()] ?? 0)];
    });

    $stats = [
      'published' => Page::where('status', 'published')->count(),
      'drafts' => Page::whereIn('status', ['draft', 'review'])->count(),
      'views' => PageView::where('viewed_at', '>=', now()->subDays(30))->count(),
      'inquiries' => Inquiry::where('created_at', '>=', now()->subDays(30))->count(),
      'new_inquiries' => Inquiry::where('status', 'new')->count(),
      'conversion' => round(
        (Inquiry::where('created_at', '>=', now()->subDays(30))->count() / max(PageView::where('viewed_at', '>=', now()->subDays(30))->count(), 1)) * 100,
        1
      ),
    ];

    $sources = PageView::where('viewed_at', '>=', now()->subDays(30))
      ->selectRaw('referrer, COUNT(*) as total')
      ->groupBy('referrer')->orderByDesc('total')->limit(5)->get();

    $topPages = Page::withCount(['views' => fn ($query) => $query->where('viewed_at', '>=', now()->subDays(30))])
      ->orderByDesc('views_count')->limit(5)->get();

    $recentInquiries = Inquiry::latest()->limit(6)->get();
    $operations = [
      ['label' => 'Published works', 'value' => Project::where('status', 'published')->count(), 'icon' => 'bx-briefcase-alt-2', 'route' => 'admin.projects.index'],
      ['label' => 'Published articles', 'value' => Post::where('status', 'published')->count(), 'icon' => 'bx-edit', 'route' => 'admin.posts.index'],
      ['label' => 'Active courses', 'value' => Course::where('status', 'published')->count(), 'icon' => 'bx-book-open', 'route' => 'admin.courses.index'],
      ['label' => 'Applications', 'value' => CourseApplication::count(), 'icon' => 'bx-receipt', 'route' => 'admin.applications.index'],
      ['label' => 'Pending payments', 'value' => Payment::where('status', 'pending')->count(), 'icon' => 'bx-wallet', 'route' => 'admin.applications.index'],
    ];
    $dashboardData = [
      'labels' => $traffic->pluck('label')->values(),
      'values' => $traffic->pluck('value')->values(),
      'sourceLabels' => $sources->pluck('referrer')->values(),
      'sourceValues' => $sources->pluck('total')->values(),
    ];

    return view('content.dashboard.dashboards-analytics', compact('stats', 'traffic', 'sources', 'topPages', 'recentInquiries', 'dashboardData', 'operations'));
  }
}
