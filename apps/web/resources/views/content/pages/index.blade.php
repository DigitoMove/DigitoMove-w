@extends('layouts/contentNavbarLayout')
@section('title', 'Content Pages')

@section('content')
<div class="admin-page reveal">
  <div class="page-heading">
    <div><span class="eyebrow">Content studio</span><h1>Pages</h1><p>Shape, review, and publish the core website experience.</p></div>
    <a href="{{ route('content-pages.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> New page</a>
  </div>
  @include('content.shared.flash')
  <div class="card admin-card">
    <div class="card-header filter-bar">
      <div><h5 class="mb-1">Content inventory</h5><small>{{ $pages->total() }} pages across every state</small></div>
      <form><select name="status" class="form-select" onchange="this.form.submit()"><option value="">All statuses</option>@foreach(['draft','review','published'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></form>
    </div>
    <div class="table-responsive">
      <table class="table align-middle admin-table">
        <thead><tr><th>Page</th><th>Status</th><th>Structure</th><th>Reach</th><th>Updated</th><th></th></tr></thead>
        <tbody>
          @forelse($pages as $page)
          <tr>
            <td><strong>{{ $page->title }}</strong><small>/{{ $page->slug }}</small></td>
            <td><span class="status-dot status-{{ $page->status }}">{{ ucfirst($page->status) }}</span></td>
            <td>{{ $page->sections_count }} sections</td><td>{{ number_format($page->views_count) }} views</td>
            <td>{{ $page->updated_at->diffForHumans() }}</td>
            <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('content-pages.edit', $page) }}">Edit</a></td>
          </tr>
          @empty
          <tr><td colspan="6" class="empty-state">No pages yet. Start with your home page.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($pages->hasPages())<div class="card-footer">{{ $pages->links() }}</div>@endif
  </div>
</div>
@endsection
