@extends('layouts.public')
@section('title', 'Services')
@section('content')
<section class="page-hero section-shell"><span class="kicker">Capabilities</span><h1>One partner for your next <em>digital move.</em></h1><p>Software delivery, practical technology support, and training grounded in how people and organisations actually work.</p></section>
<section class="section-shell service-catalog">
@foreach([
 ['Web design & development','Fast, accessible websites and web platforms designed around measurable goals.','assets/img/services/web-development.jpeg','Website or web platform'],
 ['Mobile app development','Useful mobile experiences for customers, teams, and communities.','assets/img/services/mobile-development.jpg','Mobile application'],
 ['Custom software development','Management systems, dashboards, reporting tools, and workflow automation.','assets/img/services/software-development.jpg','Custom business software'],
 ['Enterprise software procurement','Guidance, installation, and rollout support for dependable enterprise software.','assets/img/services/software-installation-procurement.png','Technology support'],
 ['Computer maintenance & servicing','Practical maintenance and servicing that keeps essential equipment dependable.','assets/img/services/computer-maintenance.png','Technology support'],
] as [$title,$copy,$image,$subject])
<article class="visual-service-card reveal"><img src="{{ asset($image) }}" alt="{{ $title }}"><div><h2>{{ $title }}</h2><p>{{ $copy }}</p><a href="{{ route('contact') }}?subject={{ urlencode($subject) }}" class="text-link">Discuss this service</a></div></article>
@endforeach
</section>
<section class="section-shell training-callout"><div><span class="kicker light">Digital literacy</span><h2>Learn the technology, not just the buttons.</h2><p>Choose basic computer training or an advanced practical programming path.</p><a href="{{ route('learning') }}" class="button light">Explore courses</a></div><div class="training-images"><img src="{{ asset('assets/img/services/ict-basic-training.png') }}" alt="Basic computer training"><img src="{{ asset('assets/img/services/advanced-computer-training.png') }}" alt="Advanced programming training"></div></section>
@endsection
