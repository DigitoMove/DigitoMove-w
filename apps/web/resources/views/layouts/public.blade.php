<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', ($page ?? null)?->seo_title ?? ($page ?? null)?->title ?? 'Digito Move') | Digito Move</title>
  <meta name="description" content="@yield('description', ($page ?? null)?->seo_description ?? ($page ?? null)?->summary ?? 'Digital products and software built for meaningful progress.')">
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="manifest" href="{{ asset('site.webmanifest') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/client.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/client-platform.css') }}">
</head>
<body>
  <header class="site-header">
    <a href="{{ route('home') }}" class="site-brand" aria-label="Digito Move home">
      <img src="{{ asset('assets/img/logos/logo-color.png') }}" alt="Digito Move">
    </a>
    <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false"><i class="bx bx-menu-alt-right"></i></button>
    <nav class="site-nav">
      <a href="{{ route('services.index') }}" @class(['active' => request()->routeIs('services.*')])>Services</a>
      <a href="{{ route('work') }}" @class(['active' => request()->routeIs('work', 'exhibition.*')])>Work</a>
      <a href="{{ route('about-us') }}" @class(['active' => request()->routeIs('about-us')])>About</a>
      <a href="{{ route('blog.index') }}" @class(['active' => request()->routeIs('blog.*')])>Insights</a>
      <a href="{{ route('learning') }}" @class(['active' => request()->routeIs('learning', 'courses.*')])>Learn</a>
      <a href="{{ route('contact') }}" class="nav-cta">Start a project <i class="bx bx-right-arrow-alt"></i></a>
    </nav>
  </header>

  <main>@yield('content')</main>

  <footer class="site-footer">
    <div class="footer-lead">
      <span class="kicker">Your next move</span>
      <h2>Have an ambitious idea?<br>Let’s make it useful.</h2>
      <a href="{{ route('contact') }}" class="circle-link" aria-label="Start a project"><i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="footer-grid">
      <div><img src="{{ asset('assets/img/logos/logo-white.png') }}" alt="Digito Move"><p>Digital products that move organisations and people forward.</p></div>
      <div><strong>Explore</strong><a href="{{ route('services.index') }}">Services</a><a href="{{ route('work') }}">Selected work</a><a href="{{ route('learning') }}">Learning portal</a><a href="{{ route('about-us') }}">About us</a></div>
      <div><strong>Connect</strong><a href="mailto:{{ config('variables.email') }}">{{ config('variables.email') }}</a><a href="tel:{{ config('variables.phone') }}">{{ config('variables.phoneDisplay') }}</a><a href="{{ config('variables.whatsappUrl') }}" target="_blank" rel="noopener">WhatsApp</a><a href="{{ route('privacy') }}">Privacy</a></div>
      <div><strong>Find us</strong><span>{{ config('variables.location') }}</span><a href="{{ config('variables.facebookUrl') }}" target="_blank" rel="noopener">Facebook</a><a href="{{ config('variables.instagramUrl') }}" target="_blank" rel="noopener">Instagram</a><a href="{{ route('dashboard-analytics') }}">Admin dashboard</a></div>
    </div>
    <div class="footer-bottom"><span>© {{ date('Y') }} Digito Move Company.</span><span>Ideas into motion.</span></div>
  </footer>
  <a class="whatsapp-float" href="{{ config('variables.whatsappUrl') }}" target="_blank" rel="noopener" aria-label="Chat with Digito Move on WhatsApp"><i class="bx bxl-whatsapp"></i></a>
  <script src="{{ asset('assets/js/client.js') }}"></script>
</body>
</html>
