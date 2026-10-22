@extends('layouts.app')

@section('content')
    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image:url({{ asset('images/slider1.jpg') }});">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-start" data-scrollax-parent="true">
                    <div class="col-md-6 ftco-animate">
                        <h1 class="mb-4">Your Dream Job is Just a Click Away</h1>
                        <p>Explore top-tier job circulars from leading companies. Create your unique profile today and start
                            applying to the most demanding roles in the industry.</p>
                        <p>
                            <a href="{{ url('/jobs') }}" class="btn btn-primary px-4 py-3 mt-3">Browse Jobs</a>
                            <a href="{{ url('/login') }}" class="btn btn-white btn-outline-white px-4 py-3 mt-3">Create
                                Profile</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-item" style="background-image:url({{ asset('images/slider2.jpg') }});">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text align-items-center justify-content-start"
                    data-scrollax-parent="true">
                    <div class="col-md-6 ftco-animate">
                        <h1 class="mb-4">Hire the Best Talent for Your Business</h1>
                        <p>Connect with impressive professionals and scale your team. Post your job openings with a unique
                            ID and manage applications seamlessly through our portal.</p>
                        <p>
                            <a href="{{ url('/login') }}" class="btn btn-primary px-4 py-3 mt-3">Post a Job</a>
                            <a href="{{ url('/about') }}" class="btn btn-white btn-outline-white px-4 py-3 mt-3">Learn
                                More</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-services ftco-no-pb">
        <div class="container-wrap">
            <div class="row no-gutters">
                <div class="col-md-3 d-flex services align-self-stretch py-5 px-4 ftco-animate bg-primary">
                    <div class="media block-6 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center">
                            <span class="fa fa-user-circle"></span>
                        </div>
                        <div class="media-body p-2 mt-3">
                            <h3 class="heading">Build Your Profile</h3>
                            <p>Upload your CV and info. Stand out with a professional presence.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex services align-self-stretch py-5 px-4 ftco-animate bg-darken">
                    <div class="media block-6 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center">
                            <span class="fa fa-search"></span>
                        </div>
                        <div class="media-body p-2 mt-3">
                            <h3 class="heading">Verified Circulars</h3>
                            <p>Access the most demanding jobs every week. We verify every posting for your security.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex services align-self-stretch py-5 px-4 ftco-animate bg-primary">
                    <div class="media block-6 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center">
                            <span class="fa fa-id-badge"></span>
                        </div>
                        <div class="media-body p-2 mt-3">
                            <h3 class="heading">Unique ID Tracking</h3>
                            <p>Every company and applicant gets a unique ID to manage applications and track success.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex services align-self-stretch py-5 px-4 ftco-animate bg-darken">
                    <div class="media block-6 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center">
                            <span class="fa fa-graduation-cap"></span>
                        </div>
                        <div class="media-body p-2 mt-3">
                            <h3 class="heading">Hire Harmony Success</h3>
                            <p>Join the community of impressive professionals who landed dream roles through our partners.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-no-pt ftc-no-pb mx-5">
        <div class="container-fluid">
            <div class="row d-flex">
                <div class="col-md-5 order-md-last wrap-about wrap-about d-flex align-items-stretch">
                    <div class="img" style="background-image: url({{ asset('images/side-ber.jpg') }});"></div>
                </div>
                <div class="col-md-7 wrap-about py-5 pr-md-4 ftco-animate">
                    <h2 class="mb-4">Why Choose Hire Harmony?</h2>
                    <p>We bridge the gap between talented professionals and top-tier companies. Our platform is designed to
                        make job hunting and recruitment efficient, transparent, and highly personalized.</p>

                    <div class="row mt-5">
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

    <section class="ftco-section ftco-counter img" id="section-counter"
        style="background-image: url({{ asset('images/video.jpg') }});" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-10 heading-section heading-section-white ftco-animate text-center">
                    <h2 class="mb-4">The Hire Harmony Experience</h2>
                    <p class="lead" style="font-size: 1.25rem; font-weight: 300;">We are redefining the recruitment
                        landscape by replacing uncertainty with precision.</p>
                    <p>At Hire Harmony, we believe every professional journey is unique. Our platform moves beyond
                        traditional job boards by implementing a <strong>Unique ID Tracking System</strong>.</p>
                </div>
            </div>

            <div class="row d-md-flex align-items-center justify-content-center">
                <div class="col-lg-12">
                    <div class="row d-md-flex align-items-center">
                        {{-- Associated Companies --}}
                        <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18">
                                <div class="icon"><span class="fa fa-building"></span></div>
                                <div class="text">
                                    <strong class="number" data-number="{{ $counts['companies'] }}">0</strong>
                                    <span>Associated Companies</span>
                                </div>
                            </div>
                        </div>
                        {{-- Active Applicants --}}
                        <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18">
                                <div class="icon"><span class="fa fa-users"></span></div>
                                <div class="text">
                                    <strong class="number" data-number="{{ $counts['candidates'] }}">0</strong>
                                    <span>Active Applicants</span>
                                </div>
                            </div>
                        </div>
                        {{-- Current Vacancies --}}
                        <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18">
                                <div class="icon"><span class="fa fa-briefcase"></span></div>
                                <div class="text">
                                    <strong class="number" data-number="{{ $counts['vacancies'] }}">0</strong>
                                    <span>Current Vacancies</span>
                                </div>
                            </div>
                        </div>
                        {{-- Successful Hires --}}
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

    <section class="ftco-section bg-light mx-10">
        <div class="container-fluid">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-8 text-center heading-section ftco-animate">
                    <h2 class="mb-4">Latest 12 <span>Job</span> Circulars</h2>
                    <p>Discover the most demanding and impressive career opportunities available this week. Verified by Hire
                        Harmony.</p>
                </div>
            </div>

            <div class="row">
                @forelse($jobs as $job)
                    <div class="col-md-3 course ftco-animate mb-4">
                        {{-- Dynamic Logo --}}
                        <div class="img"
                            style="background-image: url('{{ $job->company->profile_photo ? asset('images/profiles/' . $job->company->profile_photo) : asset('images/company-placeholder.jpg') }}'); background-size: contain; background-repeat: no-repeat; background-position: center; background-color: #fff; height: 200px; border: 1px solid #eee;">
                        </div>

                        <div class="text pt-4">
                            <p class="meta d-flex" style="font-size: 11px;">
                                <span><i class="fa fa-building mr-1"></i>{{ Str::limit($job->company_name, 12) }}</span>
                                <span><i class="fa fa-users mr-1"></i>{{ sprintf('%02d', $job->openings) }}
                                    Openings</span>
                                <span class="ml-auto text-primary"><strong>{{ $job->job_type }}</strong></span>
                            </p>
                            <h3><a href="javascript:void(0)" data-toggle="modal"
                                    data-target="#indexJobModal{{ $job->id }}">{{ $job->title }}</a></h3>
                            <p class="small text-muted">{{ Str::limit($job->description, 70) }}</p>

                            <div class="d-flex mt-4 align-items-center">
                                <p class="mb-0">
                                    <button class="btn btn-dark py-2 px-3 mr-2" style="font-size: 11px;"
                                        data-toggle="modal" data-target="#indexJobModal{{ $job->id }}">
                                        View Details
                                    </button>
                                </p>

                                <div class="mb-0">
                                    @auth
                                        @if (Auth::user()->user_type == 'candidate')
                                            @if (in_array($job->id, $appliedJobIds))
                                                <button class="btn btn-success py-2 px-3"
                                                    style="font-size: 11px; cursor: default;" disabled>
                                                    <i class="fa fa-check mr-1"></i> Applied
                                                </button>
                                            @else
                                                <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary py-2 px-3"
                                                        style="font-size: 11px;">Apply Now</button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="badge badge-light p-2">Company View</span>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary py-2 px-3"
                                            style="font-size: 11px;">Login to Apply</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL FOR INDEX PAGE --}}
                    <div class="modal fade" id="indexJobModal{{ $job->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius: 15px;">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title font-weight-bold">{{ $job->title }}</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body p-4">
                                    <h6 class="text-primary font-weight-bold mb-3">{{ $job->company_name }}</h6>
                                    <p class="text-muted small">{{ $job->description }}</p>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-muted">Positions: {{ $job->openings }}</span>
                                        <span class="small text-muted">Type: {{ $job->job_type }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No recent job circulars found.</p>
                    </div>
                @endforelse
            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-md-4 text-center">
                    <p><a href="{{ route('jobs.index') }}" class="btn btn-dark py-3 px-5"
                            style="border-radius: 50px; font-weight: bold;">View All Available Jobs</a></p>
                </div>
            </div>
        </div>
    </section>


    <section class="ftco-section ftco-consult ftco-no-pt ftco-no-pb"
        style="background-image: url({{ asset('images/feedback.jpg') }});" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-md-6 py-5 px-md-5">
                    <div class="py-md-5">
                        <div class="heading-section heading-section-white ftco-animate mb-5">
                            <h2 class="mb-4">Share Your Feedback</h2>
                            <p>Your experience matters to us. Whether you've landed a dream job or found the perfect
                                candidate, we’d love to hear your story or answer your questions.</p>
                        </div>

                        {{-- Added @csrf for Laravel Form Security --}}
                        <form action="{{ route('contact.send') }}" method="POST" class="appointment-form ftco-animate">
                            @csrf
                            <div class="d-md-flex">
                                <div class="form-group">
                                    {{-- Combining first and last name for the DB --}}
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Your Full Name" required>
                                </div>
                            </div>
                            <div class="d-md-flex">
                                <div class="form-group">
                                    <div class="select-wrap">
                                        <select name="user_type" id="user_type" class="form-control"
                                            style="color: black !important;">
                                            <option value="Guest">Are you a...</option>
                                            <option value="applicant">Job Applicant</option>
                                            <option value="company">Employer / Company</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ml-md-4">
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name="message" id="message" cols="30" rows="3" class="form-control"
                                    placeholder="Message..." required></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Submit Feedback" class="btn btn-primary py-3 px-4">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="ftco-section testimony-section">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-8 text-center heading-section ftco-animate">
                    <h2 class="mb-4">Voices of Success</h2>
                    <p>See how Hire Harmony is connecting impressive talent with world-class companies through our Unique ID
                        system.</p>
                </div>
            </div>
            <div class="row ftco-animate justify-content-center">
                <div class="col-md-12">
                    <div class="carousel-testimony owl-carousel">

                        <div class="item">
                            <div class="testimony-wrap d-flex">
                                {{-- Image path optimized for Laravel --}}
                                <div class="user-img mr-4" style="background-image: url({{ asset('images/1.jpg') }})">
                                </div>
                                <div class="text ml-2">
                                    <span class="quote d-flex align-items-center justify-content-center">
                                        <i class="fa fa-quote-left"></i>
                                    </span>
                                    <p>"The Unique ID system made my application stand out instantly. I landed a Senior Dev
                                        role within two weeks of registering!"</p>
                                    <p class="name">Racky Henderson</p>
                                    <span class="position">Software Engineer</span>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="testimony-wrap d-flex">
                                <div class="user-img mr-4" style="background-image: url({{ asset('images/2.jpg') }})">
                                </div>
                                <div class="text ml-2">
                                    <span class="quote d-flex align-items-center justify-content-center">
                                        <i class="fa fa-quote-left"></i>
                                    </span>
                                    <p>"Finding verified talent used to take months. With Hire Harmony, we can browse
                                        impressive profiles and hire with confidence."</p>
                                    <p class="name">Henry Dee</p>
                                    <span class="position">HR Manager, TechCorp</span>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="testimony-wrap d-flex">
                                <div class="user-img mr-4" style="background-image: url({{ asset('images/3.jpg') }})">
                                </div>
                                <div class="text ml-2">
                                    <span class="quote d-flex align-items-center justify-content-center">
                                        <i class="fa fa-quote-left"></i>
                                    </span>
                                    <p>"The smart notifications are a game changer. I was notified the second a matching job
                                        was posted and applied immediately."</p>
                                    <p class="name">Mark Huff</p>
                                    <span class="position">UI/UX Designer</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
