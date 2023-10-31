@extends('layouts/app')

@section('title', 'Blog')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}">
@endsection

@section('content')
    <!--Under Maintenance -->
    <div class="container-xl container-p-y">
        <div class="misc-wrapper">
            <h2 class="mb-2 mx-2">Our Blog</h2>
            <a href="{{ url('/') }}" class="btn btn-primary">Back to home</a>
            <div class="mt-4">
                <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" alt="girl-doing-yoga-light"
                    width="500" class="img-fluid">
            </div>
        </div>
    </div>
    <!-- /Under Maintenance -->
@endsection
