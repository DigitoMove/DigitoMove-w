@extends('layouts.public')
@section('title', $page->seo_title ?: $page->title)
@section('description', $page->seo_description ?: $page->summary)
@section('content')
<section class="page-hero section-shell"><span class="kicker">{{ $page->eyebrow }}</span><h1>{{ $page->title }}</h1><p>{{ $page->summary }}</p></section>
@foreach($page->sections->where('is_visible', true) as $section)
<section class="section-shell dynamic-section section-{{ $section->type }}"><span class="kicker">{{ ucfirst($section->type) }}</span><h2>{{ $section->heading }}</h2><p>{!! nl2br(e($section->body)) !!}</p>@if($section->cta_label)<a class="button primary" href="{{ $section->cta_url }}">{{ $section->cta_label }} <i class="bx bx-right-arrow-alt"></i></a>@endif</section>
@endforeach
@endsection
