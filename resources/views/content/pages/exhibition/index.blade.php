@extends('layouts/app')

@section('title', 'Exhibition')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}">
@endsection

@section('content')
    <!--Under Maintenance -->
    <div class="container-xl container-p-y">
        <h5 class="pb-1 mb-2">Our Latest Work</h5>
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <a href="{{ route('exhibition.show', 'ulrika') }}">
                                <img src="{{ asset('assets/img/exhibition/ulrika/homepage-top.png') }}"
                                    alt="Ulrika Institute of Home Economics Website" class="card-img-top">
                            </a>
                            <div class="card-body">
                                <p class="card-text">Ulrika Institute of Home Economics Website</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body">
                        <p class="card-text">
                            Shows all the products we have worked on, the products and services we have offered over time.
                            It should be noted that all projects being worked on are not diplayed on this page for purposes
                            of confidentiality.
                        </p>

                        <small class="text-light fw-semibold">Project Categories Include but not limited to:</small>
                        <div class="demo-inline-spacing mt-3">
                            <ol class="list-group list-group-numbered">
                                <li class="list-group-item">Websites</li>
                                <li class="list-group-item">Mobile Apps</li>
                                <li class="list-group-item">Graphics Products</li>
                                <li class="list-group-item">Desktop Applications</li>
                                <li class="list-group-item">Digital Management Systems</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Under Maintenance -->
@endsection
