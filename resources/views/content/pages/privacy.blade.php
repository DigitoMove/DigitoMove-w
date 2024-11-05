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
        <h5 class="pb-1 mb-4">Privacy Policy</h5>
        <hr>
        <div class="row py-5">
            <div class="card">
                <div class="card-body">
                    Your Data is not shared to any third party.
                    <hr>
                    DigitoMove Co. the enterprise Business that developed and maintains the Application
                    Digito Estates, reserves the right to change this Privacy Policy.
                </div>
            </div>
            <hr class="py-5">
            <div class="card">
                <div class="card-body">
                    You can always request to delete your data by sending us an email at digitomove@gmail.com
                </div>
            </div>

        </div>
    </div>
@endsection
