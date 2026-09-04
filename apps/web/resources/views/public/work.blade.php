@extends('layouts.public')
@section('title', 'Selected work')

@section('content')
<section class="page-hero section-shell">
  <span class="kicker">Selected work</span>
  <h1>Proof lives in what people can <em>use.</em></h1>
  <p>Explore launched websites, systems, applications, and product experiences. Demo access is clearly marked where available.</p>
</section>

<section class="section-shell project-grid">
  @forelse($projects as $project)
    @php
      $isFeatured = $loop->first;
      $isLogo = $project->cover_image === 'logo.png' || str_contains((string) $project->cover_image, '/icons/');
    @endphp
    <article @class(['project-card', 'project-featured' => $isFeatured, 'reveal'])>
      <a href="{{ route('projects.show', $project) }}" @class(['project-image', 'project-logo-image' => $isLogo])>
        <img src="{{ asset($project->cover_image ?: 'assets/img/exhibition/ulrika/homepage-top.png') }}" alt="{{ $project->title }}">
      </a>
      <div class="project-card-copy">
        <span class="kicker">{{ str_replace('_', ' ', ucfirst($project->type)) }}{{ $project->client ? ' · '.$project->client : '' }}</span>
        <h2>{{ $project->title }}</h2>
        <p>{{ $project->summary }}</p>
        <div class="project-actions">
          <a href="{{ route('projects.show', $project) }}" class="text-link">View case study</a>
          @if($project->website_url)
            <a href="{{ $project->website_url }}" target="_blank" rel="noopener" class="project-external">Visit website <i class="bx bx-link-external"></i></a>
          @elseif($project->demo_url)
            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="project-external">Open demo <i class="bx bx-link-external"></i></a>
          @endif
        </div>
      </div>
    </article>
  @empty
    <article class="project-card project-featured">
      <div class="project-image"><img src="{{ asset('assets/img/exhibition/ulrika/homepage-top.png') }}" alt="Ulrika Institute website"></div>
      <div class="project-card-copy"><span class="kicker">Web platform · Education</span><h2>Ulrika Institute of Home Economics</h2><p>A clearer digital presence designed to help prospective students act with confidence.</p></div>
    </article>
  @endforelse

  <article class="project-note">
    <div><span class="kicker light">Your project</span><h2>The next case study could be yours.</h2><p>We work discreetly when needed and collaboratively always.</p></div>
    <a href="{{ route('contact') }}" class="button light">Start a conversation <i class="bx bx-right-arrow-alt"></i></a>
  </article>
</section>
@endsection
