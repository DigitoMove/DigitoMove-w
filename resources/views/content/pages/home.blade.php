@extends('layouts/app')

@section('title', 'Home')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}">
@endsection

@section('content')
    <!--Under Maintenance -->
    <div class="container-xl container-p-y">
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="text-center py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <img src="{{ asset('assets/img/logos/logo-white.png') }}" alt="Black Logo" class="rounded w-100">

                        </div>
                        <div class="col-md-8 my-auto">
                            <x-about-contact />

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <x-side-about />
            </div>
        </div>
    </div>
    <!-- /Under Maintenance -->
@endsection
