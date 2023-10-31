@php
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';

@endphp

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-dark"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
{{-- @if (isset($navbarFull)) --}}
<div class="navbar-brand app-brand demo d-xl-flex py-0 me-4">
    <a href="{{ url('/') }}" class="app-brand-link gap-2">
        <span class="app-brand-logo demo">
            <img src="{{ asset('logo.png') }}" width="60" height="60" style="border-radius: 50%;" alt="Logo">
        </span>
        {{-- <span class="app-brand-text menu-text fw-bolder">{{ config('variables.templateName') }}</span> --}}
    </a>
</div>
{{-- @endif --}}


<div class="navbar-nav-right align-items-center ml-auto" id="navbar-collapse">
    <div></div>
    <ul class="navbar-nav flex-row align-items-center ms-auto">

        {{-- <!-- Place this tag where you want the button to render. -->
          <li class="nav-item lh-1 me-3">
            <a class="github-button" href="https://github.com/themeselection/sneat-html-laravel-admin-template-free" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star themeselection/sneat-html-laravel-admin-template-free on GitHub">Star</a>
          </li> --}}
        <li class="nav-item lh-1 me-3">
            <a href="{{ route('home') }}" class="btn btn-sm btn-{{ Route::is('home') ? 'danger' : 'primary' }}">
                Home
            </a>
        </li>
        <li class="nav-item lh-1 me-3">
            <a href="{{ route('exhibition.index') }}"
                class="btn btn-sm btn-{{ Route::is('exhibition*') ? 'danger' : 'primary' }}">
                Exhibition
            </a>
        </li>
        <li class="nav-item lh-1 me-3">
            <a href="{{ route('blog.index') }}"
                class="btn btn-sm btn-{{ Route::is('blog*') ? 'danger' : 'primary' }}">Blog</a>
        </li>
        <li class="nav-item lh-1 me-3">
            <a href="{{ route('services.index') }}"
                class="btn btn-sm btn-{{ Route::is('services*') ? 'danger' : 'primary' }}">Services</a>
        </li>
    </ul>
</div>

@if (!isset($navbarDetached))
    </div>
@endif
</nav>
<!-- / Navbar -->
