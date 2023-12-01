<nav class="navbar navbar-expand-lg bg-dark w-100" style="position: fixed;">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('logo.png') }}" width="60" height="60" style="border-radius: 50%;" alt="Logo">
            <span class="app-brand-text menu-text fw-bolder">{{ config('app.name') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guest-navbar"
            aria-controls="guest-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="guest-navbar">
            <ul class="navbar-nav">
                <li class="nav-item me-3">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-{{ Route::is('home') ? 'danger' : 'primary' }}">
                        Home
                    </a>
                </li>
                <li class="nav-item me-3">
                    <a href="{{ route('exhibition.index') }}"
                        class="btn btn-sm btn-{{ Route::is('exhibition*') ? 'danger' : 'primary' }}">
                        Exhibition
                    </a>
                </li>
                <li class="nav-item me-3">
                    <a href="{{ route('blog.index') }}"
                        class="btn btn-sm btn-{{ Route::is('blog*') ? 'danger' : 'primary' }}">Blog</a>
                </li>
                <li class="nav-item me-3">
                    <a href="{{ route('services.index') }}"
                        class="btn btn-sm btn-{{ Route::is('services*') ? 'danger' : 'primary' }}">Services</a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('about-us') }}"
                        class="btn btn-sm btn-{{ Route::is('about-us') ? 'danger' : 'primary' }}">About Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
