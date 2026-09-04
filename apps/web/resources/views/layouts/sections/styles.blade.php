<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ \App\Support\LocalAsset::url('assets/vendor/fonts/boxicons.css') }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ \App\Support\LocalAsset::url('assets/vendor/css/core.css') }}" />
<link rel="stylesheet" href="{{ \App\Support\LocalAsset::url('assets/vendor/css/theme-default.css') }}" />
<link rel="stylesheet" href="{{ \App\Support\LocalAsset::url('assets/css/demo.css') }}" />

<link rel="stylesheet" href="{{ \App\Support\LocalAsset::url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

<!-- Vendor Styles -->
@yield('vendor-style')


<!-- Application overrides follow vendor styles. -->
<link rel="stylesheet" href="{{ \App\Support\LocalAsset::url('assets/css/admin.css') }}" />
<link rel="stylesheet" href="{{ \App\Support\LocalAsset::url('assets/css/admin-editor.css') }}" />

<!-- Page Styles -->
@yield('page-style')
