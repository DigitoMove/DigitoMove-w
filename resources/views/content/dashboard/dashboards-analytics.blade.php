@extends('layouts/contentNavbarLayout')

@section('title', 'Operations Dashboard')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('page-script')
<script>
window.dashboardData = @json($dashboardData);
</script>
<script src="{{ asset('assets/js/admin-dashboard.js') }}"></script>
@endsection

@section('content')
<div class="admin-page reveal">
  <div class="hero-panel mb-4">
    <div>
      <span class="eyebrow">Command centre</span>
      <h1>Make the next move obvious.</h1>
      <p>Publishing health, audience signals, and inbound opportunities in one focused view.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('content-pages.create') }}" class="btn btn-light"><i class="bx bx-plus me-1"></i> New page</a>
      <a href="{{ route('inquiries.index') }}" class="btn btn-outline-light">Review inquiries</a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    @foreach([
      ['Published pages', $stats['published'], 'bx-globe', 'primary', 'Live and discoverable'],
      ['30-day views', number_format($stats['views']), 'bx-show', 'info', 'Across all published content'],
      ['New inquiries', $stats['new_inquiries'], 'bx-message-square-dots', 'warning', 'Waiting for a response'],
      ['Conversion', $stats['conversion'].'%', 'bx-trending-up', 'success', 'Views turning into inquiries'],
    ] as [$label, $value, $icon, $color, $hint])
    <div class="col-sm-6 col-xl-3">
      <div class="metric-card h-100">
        <span class="metric-icon bg-label-{{ $color }}"><i class="bx {{ $icon }}"></i></span>
        <span class="metric-label">{{ $label }}</span>
        <strong>{{ $value }}</strong>
        <small>{{ $hint }}</small>
      </div>
    </div>
    @endforeach
  </div>

  <div class="row g-4 mb-4">
    <div class="col-xl-8">
      <div class="card admin-card h-100">
        <div class="card-header d-flex justify-content-between align-items-start">
          <div><span class="eyebrow">Audience</span><h5 class="mb-0">Traffic momentum</h5></div>
          <span class="soft-badge">Last 14 days</span>
        </div>
        <div class="card-body"><div id="trafficChart"></div></div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card admin-card h-100">
        <div class="card-header"><span class="eyebrow">Discovery</span><h5 class="mb-0">Traffic sources</h5></div>
        <div class="card-body"><div id="sourceChart"></div></div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-7">
      <div class="card admin-card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div><span class="eyebrow">Content</span><h5 class="mb-0">Page performance</h5></div>
          <a href="{{ route('content-pages.index') }}" class="text-primary fw-semibold">Manage pages</a>
        </div>
        <div class="table-responsive">
          <table class="table align-middle admin-table">
            <thead><tr><th>Page</th><th>Status</th><th class="text-end">30-day views</th></tr></thead>
            <tbody>
              @forelse($topPages as $page)
              <tr>
                <td><strong>{{ $page->title }}</strong><small>/{{ $page->slug }}</small></td>
                <td><span class="status-dot status-{{ $page->status }}">{{ ucfirst($page->status) }}</span></td>
                <td class="text-end fw-semibold">{{ number_format($page->views_count) }}</td>
              </tr>
              @empty
              <tr><td colspan="3" class="empty-state">No pages yet. Create the first one.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-5">
      <div class="card admin-card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div><span class="eyebrow">Pipeline</span><h5 class="mb-0">Latest inquiries</h5></div>
          <a href="{{ route('inquiries.index') }}" class="text-primary fw-semibold">View all</a>
        </div>
        <div class="card-body pt-2">
          @forelse($recentInquiries as $inquiry)
          <div class="inquiry-row">
            <span class="avatar-initial">{{ strtoupper(substr($inquiry->name, 0, 1)) }}</span>
            <div class="flex-grow-1"><strong>{{ $inquiry->name }}</strong><small>{{ $inquiry->subject ?: 'General inquiry' }} · {{ $inquiry->created_at->diffForHumans() }}</small></div>
            <span class="status-dot status-{{ $inquiry->status }}">{{ ucfirst($inquiry->status) }}</span>
          </div>
          @empty
          <div class="empty-state">New conversations will appear here.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
