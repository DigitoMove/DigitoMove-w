@extends('layouts/app')

@section('title', 'Exhibition')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}">
@endsection

@section('content')
    <!--Under Maintenance -->
    <div class="container-xl container-p-y">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h4 class="pb-1 mb-3 text-center">
                    Exhibition | Ulrika Institute of Home Economics Website
                </h4>

                <div id="ulrikaCarousel" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-bs-target="#ulrikaCarousel" data-bs-slide-to="0" class="active"></li>
                        <li data-bs-target="#ulrikaCarousel" data-bs-slide-to="1"></li>
                        <li data-bs-target="#ulrikaCarousel" data-bs-slide-to="2"></li>
                        <li data-bs-target="#ulrikaCarousel" data-bs-slide-to="3"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="{{ asset('assets/img/exhibition/ulrika/homepage-top.png') }}"
                                alt="Home Page - Top" />
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ asset('assets/img/exhibition/ulrika/homepage-middle.png') }}"
                                alt="Home Page - Middle" />
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ asset('assets/img/exhibition/ulrika/homepage-footer.png') }}"
                                alt="Home Page - Footer" />
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100"
                                src="{{ asset('assets/img/exhibition/ulrika/homepage-activities.png') }}"
                                alt="Activities Page" />
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ asset('assets/img/exhibition/ulrika/about-us.png') }}"
                                alt="About Us Page" />
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#ulrikaCarousel" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#ulrikaCarousel" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
