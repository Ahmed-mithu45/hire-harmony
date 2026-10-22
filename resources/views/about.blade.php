@extends('layouts.app')

@section('content')
    {{-- 1. Hero Section --}}
    <section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('images/about.jpg') }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-2 bread">About Us</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ url('/') }}">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span>
                        <span>About us <i class="ion-ios-arrow-forward"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Why Choose Hire Harmony Section --}}
    <section class="ftco-section ftco-no-pt ftc-no-pb mx-5">
        <div class="container-fluid">
            <div class="row d-flex">
                <div class="col-md-5 order-md-last wrap-about wrap-about d-flex align-items-stretch">
                    <div class="img" style="background-image: url('{{ asset('images/side-ber.jpg') }}');"></div>
                </div>
                <div class="col-md-7 wrap-about py-5 pr-md-4 ftco-animate">
                    <h2 class="mb-4">Why Choose Hire Harmony?</h2>
                    <p>We bridge the gap between talented professionals and top-tier companies. Our platform is designed to
                        make job hunting and recruitment efficient, transparent, and highly personalized.</p>

                    <div class="row mt-5">
                        {{-- Feature 1 --}}
                        <div class="col-lg-6">
                            <div class="services-2 d-flex">
                                <div class="icon mt-2 d-flex justify-content-center align-items-center"><span
                                        class="fa fa-bell"></span></div>
                                <div class="text pl-3">
                                    <h3>Smart Notifications</h3>
                                    <p>Create your unique ID and never miss an opportunity. You will get instant
                                        notifications for every new job added to the platform.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Feature 2 --}}
                        <div class="col-lg-6">
                            <div class="services-2 d-flex">
                                <div class="icon mt-2 d-flex justify-content-center align-items-center"><span
                                        class="fa fa-check-circle"></span></div>
                                <div class="text pl-3">
                                    <h3>Verified Employers</h3>
                                    <p>We partner only with trusted companies to ensure every job circular is genuine and
                                        secure.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Feature 3 --}}
                        <div class="col-lg-6">
                            <div class="services-2 d-flex">
                                <div class="icon mt-2 d-flex justify-content-center align-items-center"><span
                                        class="fa fa-id-card"></span></div>
                                <div class="text pl-3">
                                    <h3>Identity Security</h3>
                                    <p>Each user receives a unique ID to track applications and manage their professional
                                        data safely.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Feature 4 --}}
                        <div class="col-lg-6">
                            <div class="services-2 d-flex">
                                <div class="icon mt-2 d-flex justify-content-center align-items-center"><span
                                        class="fa fa-bolt"></span></div>
                                <div class="text pl-3">
                                    <h3>Fast Recruitment</h3>
                                    <p>Our streamlined portal allows companies to view impressive profiles and hire the
                                        right talent quickly.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Feature 5 --}}
                        <div class="col-lg-6">
                            <div class="services-2 d-flex">
                                <div class="icon mt-2 d-flex justify-content-center align-items-center"><span
                                        class="fa fa-line-chart"></span></div>
                                <div class="text pl-3">
                                    <h3>Career Growth</h3>
                                    <p>We offer jobs that match your expertise, helping you land roles that truly fit your
                                        skills.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Feature 6 --}}
                        <div class="col-lg-6">
                            <div class="services-2 d-flex">
                                <div class="icon mt-2 d-flex justify-content-center align-items-center"><span
                                        class="fa fa-users"></span></div>
                                <div class="text pl-3">
                                    <h3>Global Network</h3>
                                    <p>Join a vast community of professionals and associated partners across various
                                        industries.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Stats Section --}}
    <section class="ftco-section ftco-counter img" id="section-counter"
        style="background-image: url({{ asset('images/video.jpg') }});">
        <div class="overlay"></div>
        <div class="container">
            <div class="row d-md-flex align-items-center justify-content-center">
                <div class="col-lg-12">
                    <div class="row d-md-flex align-items-center">
                        {{-- Companies --}}
                        <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18">
                                <div class="icon"><span class="fa fa-building"></span></div>
                                <div class="text">
                                    <strong class="number" data-number="{{ $counts['companies'] }}">0</strong>
                                    <span>Associated Companies</span>
                                </div>
                            </div>
                        </div>
                        {{-- Applicants --}}
                        <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18">
                                <div class="icon"><span class="fa fa-users"></span></div>
                                <div class="text">
                                    <strong class="number" data-number="{{ $counts['candidates'] }}">0</strong>
                                    <span>Active Applicants</span>
                                </div>
                            </div>
                        </div>
                        {{-- Vacancies --}}
                        <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18">
                                <div class="icon"><span class="fa fa-briefcase"></span></div>
                                <div class="text">
                                    <strong class="number" data-number="{{ $counts['vacancies'] }}">0</strong>
                                    <span>Current Vacancies</span>
                                </div>
                            </div>
                        </div>
                        {{-- Hires --}}
                        <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18">
                                <div class="icon"><span class="fa fa-check-circle"></span></div>
                                <div class="text">
                                    <strong class="number" data-number="{{ $counts['hires'] }}">0</strong>
                                    <span>Successful Hires</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Voices of Success (Partial) --}}
    @include('sections.testimonials')
@endsection
