@extends('layouts.app')

@section('content')
    {{-- 1. Hero Section --}}
    <section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('images/about.jpg') }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-2 bread">Available Jobs</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ url('/') }}">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span>
                        <span>Available Jobs <i class="ion-ios-arrow-forward"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Job Grid --}}
    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-8 text-center heading-section ftco-animate">
                    <h2 class="mb-4"><span>Job</span> Circulars</h2>
                    <p>Discover the most demanding career opportunities verified by Hire Harmony.</p>
                </div>
            </div>

            <div class="row">
                @forelse($jobs as $job)
                    <div class="col-md-3 course ftco-animate mb-4">
                        {{-- Dynamic Logo: Uses Company Profile Photo --}}
                        <div class="img"
                            style="background-image: url('{{ $job->company->profile_photo ? asset('images/profiles/' . $job->company->profile_photo) : asset('images/company-placeholder.jpg') }}'); background-size: contain; background-repeat: no-repeat; background-position: center; padding: 20px; background-color: #ffffff; border-bottom: 1px solid #eee; height: 200px;">
                        </div>

                        <div class="text pt-4">
                            <p class="meta d-flex" style="font-size: 11px;">
                                <span><i class="fa fa-building mr-1"></i>{{ Str::limit($job->company_name, 15) }}</span>
                                <span><i class="fa fa-users mr-1"></i>{{ sprintf('%02d', $job->openings) }} Openings</span>
                                <span class="ml-auto text-primary"><strong>{{ $job->job_type }}</strong></span>
                            </p>
                            <h3><a href="javascript:void(0)" data-toggle="modal"
                                    data-target="#jobModal{{ $job->id }}">{{ $job->title }}</a></h3>
                            <p class="small text-muted" style="height: 40px; overflow: hidden;">
                                {{ Str::limit($job->description, 70) }}</p>

                            <div class="d-flex mt-4 align-items-center">
                                <p class="mb-0">
                                    <button class="btn btn-dark py-2 px-3 mr-2" style="font-size: 11px; border-radius: 5px;"
                                        data-toggle="modal" data-target="#jobModal{{ $job->id }}">
                                        View Details
                                    </button>
                                </p>

                                <div class="mb-0">
                                    @auth
                                        @if (Auth::user()->user_type == 'candidate')
                                            {{-- Check if job ID exists in the applied array passed from controller --}}
                                            @if (isset($appliedJobIds) && in_array($job->id, $appliedJobIds))
                                                <button class="btn btn-success py-2 px-3"
                                                    style="font-size: 11px; border-radius: 5px; cursor: default;" disabled>
                                                    <i class="fa fa-check mr-1"></i> Applied
                                                </button>
                                            @else
                                                <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary py-2 px-3"
                                                        style="font-size: 11px; border-radius: 5px;">Apply Now</button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-muted small font-italic">Company View</span>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary py-2 px-3"
                                            style="font-size: 11px; border-radius: 5px;">Login to Apply</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- JOB DETAILS MODAL --}}
                    <div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden;">
                                <div class="modal-header border-0 bg-light p-4">
                                    <h5 class="modal-title font-weight-bold">{{ $job->title }}</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="d-flex align-items-center mb-4">
                                        <img src="{{ $job->company->profile_photo ? asset('images/profiles/' . $job->company->profile_photo) : asset('images/company-placeholder.jpg') }}"
                                            style="width: 50px; height: 50px; object-fit: contain;" class="mr-3">
                                        <div>
                                            <h6 class="text-primary font-weight-bold mb-0">{{ $job->company_name }}</h6>
                                            <small class="text-muted">{{ $job->category }}</small>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Employment Type</small>
                                            <strong>{{ $job->job_type }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Openings</small>
                                            <strong>{{ $job->openings }} Positions</strong>
                                        </div>
                                    </div>

                                    <h6 class="font-weight-bold">Description</h6>
                                    <p class="text-muted small" style="white-space: pre-line; line-height: 1.6;">
                                        {{ $job->description }}</p>

                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="text-muted small"><i class="fa fa-calendar mr-1"></i> Posted
                                            {{ $job->created_at->format('M d, Y') }}</span>

                                        @if (Auth::check() && Auth::user()->user_type == 'candidate')
                                            @if (in_array($job->id, $appliedJobIds))
                                                <span class="text-success font-weight-bold"><i class="fa fa-check"></i>
                                                    Already Applied</span>
                                            @else
                                                <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-orange text-white px-4"
                                                        style="background: #ff6a00; border-radius: 8px;">Apply Now</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ asset('images/no-jobs.png') }}" style="width: 80px; opacity: 0.3;" class="mb-3">
                        <p class="text-muted">No career opportunities found. Check back later!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
