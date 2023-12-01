@extends('layouts/commonMaster')

@php
    /* Display elements */
    $contentNavbar = true;
    $containerNav = $containerNav ?? 'container-xxl';
    $isNavbar = $isNavbar ?? true;
    $isMenu = false;
    $isFlex = $isFlex ?? false;
    $isFooter = $isFooter ?? true;
    $customizerHidden = $customizerHidden ?? '';
    $pricingModal = $pricingModal ?? false;

    /* HTML Classes */
    $navbarDetached = 'navbar-detached';

    /* Content classes */
    $container = $container ?? 'container-xxl';

@endphp

@section('layoutContent')
    <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
        <div class="layout-container my-0 py-0">

            @if ($isMenu)
                @include('layouts/sections/menu/verticalMenu')
            @endif

            <!-- Layout page -->
            <div class="w-100 my-0 py-0">
                <!-- BEGIN: Navbar-->
                @if ($isNavbar)
                    @include('layouts/sections/navbar/navbar')
                @endif
                <!-- END: Navbar-->


                <!-- Content wrapper -->
                <div class="content-wrapper my-5">

                    @yield('content')

                </div>
                <!-- / Content -->

                <!-- Footer -->
                @if ($isFooter)
                    @include('layouts/sections/footer/app-footer')
                @endif
            </div>
            <!--/ Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>
@endsection
