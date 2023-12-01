@extends('layouts/app')

@section('title', 'Services')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}">
@endsection

@section('content')
    <!--Under Maintenance -->
    <div class="container-xl container-p-y">
        <div class="">
            <h2 class="mb-2 mx-2">Our Services</h2>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 my-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Web Design and Development</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 my-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Mobile App Design and Development</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 my-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Custom Software Design and Development</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 my-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Enterprise Software Procurement</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 my-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Training In Basic ICT Applications</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 my-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Advanced Training in Programming</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 my-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Computer Maintenance</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Under Maintenance -->
@endsection
