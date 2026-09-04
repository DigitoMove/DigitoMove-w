@extends('layouts.public')
@section('title', 'Digital products that move business forward')

@section('content')
<section class="hero section-shell">
  <div class="hero-copy reveal">
    <span class="kicker">{{ $page?->eyebrow ?? 'Strategy · Design · Technology' }}</span>
    <h1>We turn useful ideas into <em>digital momentum.</em></h1>
    <p>{{ $page?->summary ?? 'Digito Move designs and builds websites, software, and digital experiences that are clear, dependable, and ready to grow.' }}</p>
    <div class="button-row"><a href="{{ route('contact') }}" class="button primary">Start a project <i class="bx bx-right-arrow-alt"></i></a><a href="{{ route('work') }}" class="text-link">See our work</a></div>
  </div>
  <div class="hero-orbit reveal">
    <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div>
    <div class="hero-card card-main"><span>01</span><strong>Build for clarity.</strong><p>Less noise. Better systems. Stronger outcomes.</p></div>
    <div class="hero-card card-float"><i class="bx bx-code-alt"></i><span>From concept to launch</span></div>
  </div>
</section>

<section class="trust-strip"><span>Trusted capabilities</span><div>Web platforms</div><div>Mobile apps</div><div>Business systems</div><div>Digital training</div></section>

<section class="section-shell image-led-services">
  <div class="image-service-main reveal"><img src="{{ asset('assets/img/promotions/web-dev3.jpg') }}" alt="Web development"><div><span class="kicker light">Web platforms</span><h2>Designed clearly. Built to perform.</h2><a href="{{ route('services.index') }}" class="button light">Explore services</a></div></div>
  <div class="image-service-stack">
    <article class="reveal"><img src="{{ asset('assets/img/promotions/mobile-dev2.jpg') }}" alt="Mobile app development"><div><strong>Mobile applications</strong><span>Useful experiences for people on the move.</span></div></article>
    <article class="reveal"><img src="{{ asset('assets/img/promotions/software-dev1.jpg') }}" alt="Custom software development"><div><strong>Custom business software</strong><span>Systems shaped around real operating needs.</span></div></article>
  </div>
</section>

<section class="section-shell split-section">
  <div><span class="kicker">What we do</span><h2>Technology should feel like progress, not friction.</h2></div>
  <div><p>We combine product thinking, intentional design, and robust engineering to solve real operating problems.</p><a href="{{ route('services.index') }}" class="text-link">Explore every capability <i class="bx bx-right-arrow-alt"></i></a></div>
</section>

<section class="section-shell service-grid">
  @foreach([
    ['01','Digital products','Websites, applications, and platforms designed around the people who use them.','bx-window-alt'],
    ['02','Custom software','Purpose-built systems that simplify workflows and make better decisions possible.','bx-shape-square'],
    ['03','Technology enablement','Training, support, and practical guidance that helps teams make technology useful.','bx-bulb'],
  ] as [$number,$title,$copy,$icon])
  <article class="service-card reveal"><span>{{ $number }}</span><i class="bx {{ $icon }}"></i><h3>{{ $title }}</h3><p>{{ $copy }}</p><a href="{{ route('services.index') }}">Learn more <i class="bx bx-right-arrow-alt"></i></a></article>
  @endforeach
</section>

<section class="work-feature section-shell">
  <div class="work-copy"><span class="kicker light">Selected work</span><h2>Digital experiences built to earn attention and action.</h2><p>We make the complicated understandable, useful, and beautifully direct.</p><a href="{{ route('work') }}" class="button light">Explore our work</a></div>
  <div class="work-browser"><div class="browser-bar"><span></span><span></span><span></span></div><img src="{{ asset('assets/img/exhibition/ulrika/homepage-top.png') }}" alt="Ulrika Institute website project"></div>
</section>

<section class="section-shell stats-row">
  <div><strong>2021</strong><span>Founded in Kampala</span></div><div><strong>End-to-end</strong><span>From strategy to support</span></div><div><strong>+256 701 822 382</strong><span>Call or WhatsApp Digito Move</span></div>
</section>
@endsection
