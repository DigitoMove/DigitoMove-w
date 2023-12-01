@extends('layouts/app')

@section('title', 'About Us')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}">
@endsection

@section('content')
    <!--Under Maintenance -->
    <div class="container-xl container-p-y">
        <!-- Content types -->
        <h5 class="pb-1 mb-4">About Us</h5>

        <div class="row mb-5">
            <div class="col-md-6 col-lg-4">
                <h6 class="mt-2 text-muted">History</h6>
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text">
                            Digito Move Company started in May 2021 by Epaphradito Lugayavu, a fullstack software Engineer,
                            as an Online Software Development Business, contracting different invidual and company size
                            projects for a number of startups and already established businesses.
                        </p>
                        <p class="card-text">
                            In 2023, the company has received a number of projects involving making websites and mobile apps
                            and Management Systems for different businesses accross the country. The business growth has
                            been attributed to the resilience of the Director, who always finds a way forward even in hard
                            times.
                        </p>
                        <p class="card-text">
                            Currently, Digito Move provides services that include; System Development, for both Desktop and
                            the web, Mobile App Development, Deployment of software products, both enterprise and
                            customized, acquisition of Enterprise software systems for Use with or without subscriptions,
                            among others.
                        </p>
                    </div>
                </div>
                <x-side-about />
            </div>
            <div class="col-md-6 col-lg-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <img class="card-img-top" src="{{ asset('assets/img/objectives/goals.png') }}"
                                alt="Card image cap" />
                            <div class="card-body">
                                <div class="card-body">
                                    <h5 class="card-title">Goals</h5>
                                    <p class="card-text">
                                        Projecting the future, precise targets...
                                    </p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">To Own an Office by July 2024</li>
                                    <li class="list-group-item">To Educate over 5 People in terms of Software development by
                                        July
                                        2024</li>
                                    <li class="list-group-item">To Increase sales, projects, by 40% until July 2024</li>
                                    <li class="list-group-item">To Hire at least 2 people by July 2024</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('assets/img/objectives/embracing-tech.jpg') }}"
                                alt="Card image cap" />
                            <div class="card-body">
                                <h5 class="card-title">Objectives</h5>
                                <p class="card-text">
                                    We aim to achieve the following Objectives;
                                </p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">To promote Digital Migration accross the Universe.</li>
                                <li class="list-group-item">To Provide Digital Literacy (Knowledge) through training.</li>
                                <li class="list-group-item">To Encourage the use of Technology through educating the people,
                                    ushering in and accelerating the Digital Age.</li>
                                <li class="list-group-item">To Find and Apply new approaches to problem solving using
                                    Technology,
                                    and specifically, using software applications.</li>
                                <li class="list-group-item">To Improve existing software solutions through research.</li>
                                <li class="list-group-item">To make software development and software products affordable to
                                    majority of the population.</li>
                                <li class="list-group-item">To Enable the Tech-enthuasiates find their potential in the
                                    world of
                                    competition and speed.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 my-5">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Our Contacts</h3>
                                <div class="text-center">
                                    <div>
                                        <a href="tel:{{ env('PHONE_01') }}" target="_blank"
                                            class="btn btn-sm btn-dark mx-1 my-1">
                                            <i class="bx bx-phone"></i> +256701-822-382
                                        </a>
                                        <a href="tel:{{ env('PHONE_02') }}" target="_blank"
                                            class="btn btn-sm btn-dark mx-1 my-1">
                                            <i class="bx bx-phone"></i> +256777-522-214
                                        </a>

                                    </div>
                                    <div>
                                        <a href="{{ env('SOCIAL_WHATSAPP') }}" target="_blank"
                                            class="btn btn-sm btn-success mx-1 my-1">
                                            <i class="bx bxl-whatsapp"></i> +256777-522-214
                                        </a>
                                        <a href="{{ env('SOCIAL_FACEBOOK') }}" target="_blank"
                                            class="btn btn-sm btn-primary mx-1 my-1">
                                            <i class="bx bxl-facebook"></i> @DigitoMove
                                        </a>
                                        <a href="{{ env('SOCIAL_TWITTER') }}" target="_blank"
                                            class="btn btn-sm btn-dark mx-1 my-1">
                                            <i class="bx bxl-twitter"></i> @DigitoMove
                                        </a>
                                        <a href="{{ env('SOCIAL_YOUTUBE') }}" target="_blank"
                                            class="btn btn-sm btn-danger mx-1 my-1">
                                            <i class="bx bxl-youtube"></i> @DigitoMove
                                        </a>
                                        <a href="{{ env('SOCIAL_INSTAGRAM') }}" target="_blank"
                                            class="btn btn-sm btn-danger mx-1 my-1">
                                            <i class="bx bxl-instagram"></i> @DigitoMove
                                        </a>

                                    </div>

                                    <div>
                                        <a href="mailto:{{ env('MAIL_INFO') }}" target="_blank"
                                            class="btn btn-sm btn-dark mx-1 my-1">
                                            <i class="bx bx-envelope"></i> {{ env('MAIL_INFO') }}
                                        </a>
                                        <a href="mailto:{{ env('MAIL_GMAIL_01') }}" target="_blank"
                                            class="btn btn-sm btn-danger mx-1 my-1">
                                            <i class="bx bx-mail-send"></i> {{ env('MAIL_GMAIL_01') }}
                                        </a>
                                        <a href="mailto:{{ env('MAIL_GMAIL_02') }}" target="_blank"
                                            class="btn btn-sm btn-danger mx-1 my-1">
                                            <i class="bx bx-mail-send"></i> {{ env('MAIL_GMAIL_02') }}
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /Under Maintenance -->
@endsection
