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

            </div>
            <div class="col-md-6 col-lg-4">
                <x-side-about />
            </div>
        </div>
    </div>
    <!-- /Under Maintenance -->
@endsection
