@extends('layouts/contentNavbarLayout')
@section('title', $page->exists ? 'Edit '.$page->title : 'Create Page')

@section('content')
<div class="admin-page reveal">
  <div class="page-heading">
    <div><a href="{{ route('content-pages.index') }}" class="back-link"><i class="bx bx-left-arrow-alt"></i> Pages</a><h1>{{ $page->exists ? $page->title : 'Create a page' }}</h1><p>Define the page story, publishing state, and search presentation.</p></div>
    @if($page->exists)<span class="status-dot status-{{ $page->status }}">{{ ucfirst($page->status) }}</span>@endif
  </div>
  @include('content.shared.flash')
  @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

  <form method="POST" action="{{ $page->exists ? route('content-pages.update', $page) : route('content-pages.store') }}">
    @csrf @if($page->exists) @method('PUT') @endif
    <div class="row g-4">
      <div class="col-xl-8">
        <div class="card admin-card"><div class="card-header"><span class="eyebrow">Essentials</span><h5 class="mb-0">Page details</h5></div>
          <div class="card-body row g-3">
            <div class="col-md-8"><label class="form-label">Title</label><input name="title" class="form-control form-control-lg" value="{{ old('title', $page->title) }}" required></div>
            <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select form-select-lg">@foreach(['draft','review','published'] as $status)<option value="{{ $status }}" @selected(old('status', $page->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">URL slug</label><div class="input-group"><span class="input-group-text">/</span><input name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" placeholder="generated-from-title"></div></div>
            <div class="col-md-6"><label class="form-label">Eyebrow</label><input name="eyebrow" class="form-control" value="{{ old('eyebrow', $page->eyebrow) }}" placeholder="Short context label"></div>
            <div class="col-12"><label class="form-label">Summary</label><textarea name="summary" class="form-control" rows="4" placeholder="What should visitors understand immediately?">{{ old('summary', $page->summary) }}</textarea></div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card admin-card"><div class="card-header"><span class="eyebrow">Discovery</span><h5 class="mb-0">Search preview</h5></div><div class="card-body">
          <label class="form-label">SEO title</label><input name="seo_title" class="form-control mb-3" value="{{ old('seo_title', $page->seo_title) }}">
          <label class="form-label">SEO description</label><textarea name="seo_description" class="form-control" rows="5">{{ old('seo_description', $page->seo_description) }}</textarea>
        </div></div>
      </div>
    </div>
    <div class="sticky-actions"><button class="btn btn-primary px-4">{{ $page->exists ? 'Save changes' : 'Create page' }}</button></div>
  </form>

  @if($page->exists)
  <div class="section-heading mt-5"><div><span class="eyebrow">Page builder</span><h2>Sections</h2></div><span>{{ $page->sections->count() }} blocks</span></div>
  <div class="row g-3">
    @foreach($page->sections as $section)
    <div class="col-12">
      <form class="card admin-card section-card" method="POST" action="{{ route('page-sections.update', $section) }}">@csrf @method('PUT')
        <div class="card-body row g-3 align-items-end">
          <div class="col-md-2"><label class="form-label">Type</label><select name="type" class="form-select">@foreach(['hero','content','features','stats','cta'] as $type)<option @selected($section->type === $type)>{{ $type }}</option>@endforeach</select></div>
          <div class="col-md-2"><label class="form-label">Order</label><input type="number" name="sort_order" class="form-control" value="{{ $section->sort_order }}"></div>
          <div class="col-md-8"><label class="form-label">Heading</label><input name="heading" class="form-control" value="{{ $section->heading }}"></div>
          <div class="col-12"><label class="form-label">Body</label><textarea name="body" class="form-control" rows="3">{{ $section->body }}</textarea></div>
          <div class="col-md-4"><label class="form-label">CTA label</label><input name="cta_label" class="form-control" value="{{ $section->cta_label }}"></div>
          <div class="col-md-4"><label class="form-label">CTA URL</label><input name="cta_url" class="form-control" value="{{ $section->cta_url }}"></div>
          <div class="col-md-2"><div class="form-check form-switch mb-2"><input type="hidden" name="is_visible" value="0"><input class="form-check-input" type="checkbox" name="is_visible" value="1" @checked($section->is_visible)><label class="form-check-label">Visible</label></div></div>
          <div class="col-md-2 text-end"><button class="btn btn-outline-primary">Update</button></div>
        </div>
      </form>
    </div>
    @endforeach
    <div class="col-12">
      <form class="card add-section-card" method="POST" action="{{ route('page-sections.store', $page) }}">@csrf
        <div class="card-body row g-3 align-items-end">
          <div class="col-md-2"><label class="form-label">New section</label><select name="type" class="form-select">@foreach(['hero','content','features','stats','cta'] as $type)<option>{{ $type }}</option>@endforeach</select></div>
          <div class="col-md-2"><label class="form-label">Order</label><input type="number" name="sort_order" class="form-control" value="{{ $page->sections->count() }}"></div>
          <div class="col-md-6"><label class="form-label">Heading</label><input name="heading" class="form-control" placeholder="Section heading"></div>
          <input type="hidden" name="is_visible" value="1"><div class="col-md-2"><button class="btn btn-primary w-100"><i class="bx bx-plus"></i> Add</button></div>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
@endsection
