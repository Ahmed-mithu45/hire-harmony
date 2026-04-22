@extends('layouts.app')

@section('content')
    <div class="container py-5">
        {{-- Header Section --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; overflow: hidden;">
            {{-- Dynamic Cover Photo --}}
            <div class="position-relative"
                style="height: 200px; background: {{ $company->cover_photo ? 'url(' . asset('images/covers/' . $company->cover_photo) . ')' : '#00a2ff' }}; background-size: cover; background-position: center;">

                {{-- ONLY SHOW IF OWNER (PRIVATE VIEW) --}}
                @if (!isset($isPublic))
                    <form action="{{ route('profile.cover') }}" method="POST" enctype="multipart/form-data" id="coverForm">
                        @csrf
                        <input type="file" name="cover" id="coverInput" style="display:none"
                            onchange="document.getElementById('coverForm').submit()">
                        <button type="button" onclick="document.getElementById('coverInput').click()"
                            class="btn btn-sm btn-light position-absolute"
                            style="top: 15px; right: 15px; opacity: 0.7; border-radius: 20px;">
                            <i class="fa fa-camera"></i> Change Cover
                        </button>
                    </form>
                @endif
            </div>

            <div class="card-body p-4 pt-0">
                <div class="d-flex align-items-start flex-wrap">
                    {{-- Dynamic Logo Container --}}
                    <div class="bg-white p-2 shadow-sm rounded mb-3"
                        style="margin-top: -60px; width: 140px; height: 140px; z-index: 5;">
                        <div class="position-relative h-100">
                            <img src="{{ $company->profile_photo ? asset('images/profiles/' . $company->profile_photo) : asset('images/candidate-placeholder.jpg') }}"
                                class="img-fluid w-100 h-100" style="object-fit: contain;">

                            {{-- ONLY SHOW PENCIL IF OWNER --}}
                            @if (!isset($isPublic))
                                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data"
                                    id="logoForm">
                                    @csrf
                                    <input type="file" name="photo" id="logoInput" style="display:none"
                                        onchange="document.getElementById('logoForm').submit()">
                                    <button type="button" onclick="document.getElementById('logoInput').click()"
                                        class="position-absolute border-0 shadow-sm"
                                        style="bottom: 0; right: 0; background: #f8f9fa; border-radius: 50%; width: 30px; height: 30px;">
                                        <i class="fa fa-pencil text-primary"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="ml-md-4 mt-2 flex-grow-1">
                        <h3 class="font-weight-bold text-dark mb-1">{{ $company->name }}</h3>
                        <p class="text-muted mb-3" style="font-size: 15px;">
                            <i class="fa fa-map-marker mr-1 text-warning"></i> {{ $company->address ?? 'Location not set' }}
                            <span class="mx-2">·</span>
                            <i class="fa fa-globe mr-1 text-warning"></i>
                            @if ($company->portfolio_url)
                                <a href="{{ Str::startsWith($company->portfolio_url, 'http') ? $company->portfolio_url : 'https://' . $company->portfolio_url }}"
                                    target="_blank" class="text-muted">
                                    {{ str_replace(['https://', 'http://', 'www.'], '', $company->portfolio_url) }}
                                </a>
                            @else
                                <span class="text-muted">No website set</span>
                            @endif
                        </p>

                        {{-- OWNER ACTION BUTTONS --}}
                        @if (!isset($isPublic))
                            <div class="d-flex flex-wrap">
                                <button class="btn btn-outline-primary px-4 py-2 mr-2" style="border-radius: 8px;"
                                    data-toggle="modal" data-target="#editCompanyProfileModal">
                                    <i class="fa fa-edit"></i> Edit Profile
                                </button>
                                <button class="btn btn-orange text-white px-4 py-2"
                                    style="border-radius: 8px; background-color: #ff6a00; border-color: #ff6a00;"
                                    data-toggle="modal" data-target="#postJobModal">
                                    <i class="fa fa-plus"></i> Post New Job
                                </button>
                            </div>
                        @else
                            {{-- VISITOR ACTION BUTTON
                        <button class="btn btn-outline-primary px-4 py-2" style="border-radius: 8px;">
                            <i class="fa fa-envelope"></i> Contact Company
                        </button> --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row text-center mb-5">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                    <h2 class="font-weight-bold" style="color: #ff6a00;">{{ sprintf('%02d', $stats['active_jobs']) }}</h2>
                    <p class="text-muted small font-weight-bold mb-0">ACTIVE JOBS</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                    <h2 class="font-weight-bold text-success">{{ !isset($isPublic) ? $stats['total_applicants'] : '---' }}
                    </h2>
                    <p class="text-muted small font-weight-bold mb-0">TOTAL APPLICANTS</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                    <h2 class="font-weight-bold text-info">{{ !isset($isPublic) ? $stats['interviews'] : '---' }}</h2>
                    <p class="text-muted small font-weight-bold mb-0">INTERVIEWS SET</p>
                </div>
            </div>
        </div>

        {{-- Posted Circulars --}}
        <h4 class="font-weight-bold mb-4">{{ !isset($isPublic) ? 'Manage Posted Circulars' : 'Available Openings' }}</h4>
        <div class="row">
            @forelse($jobs as $job)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm p-4 h-100 position-relative" style="border-radius: 15px;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge badge-success px-3 py-1"
                                style="border-radius: 5px;">{{ $job->job_type }}</span>
                            @if (!isset($isPublic))
                                <form action="{{ route('job.delete', $job->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this job circular?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0"><i
                                            class="fa fa-trash"></i></button>
                                </form>
                            @endif
                        </div>

                        <h5 class="font-weight-bold mb-1">{{ $job->title }}</h5>
                        <p class="text-muted small mb-4"
                            style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $job->description }}
                        </p>

                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            @if (!isset($isPublic))
                                <a href="{{ route('job.applicants', $job->id) }}" class="btn btn-dark px-3 py-2"
                                    style="border-radius: 8px;">View Applicants</a>
                            @else
                                @auth
                                    @if (Auth::user()->user_type == 'candidate')
                                        <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary px-3 py-2"
                                                style="border-radius: 8px;">Apply Now</button>
                                        </form>
                                    @else
                                        <span class="badge badge-light text-muted">Company View</span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary px-3 py-2"
                                        style="border-radius: 8px;">Login to Apply</a>
                                @endauth
                            @endif
                            <span class="text-muted small"
                                style="font-size: 11px;">{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No circulars currently posted by this company.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Only Load Modals for the Owner --}}
    @if (!isset($isPublic))
        @include('partials.company_modals')
    @endif

@endsection
