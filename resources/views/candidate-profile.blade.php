@extends('layouts.app')

@section('content')
    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row">
                {{-- SIDEBAR --}}
                <div class="col-lg-4">
                    {{-- Profile Card --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body text-center p-5">
                            <div class="profile-image-container mb-3">
                                {{-- Changed to $user --}}
                                <img src="{{ $user->profile_photo ? asset('images/profiles/' . $user->profile_photo) : asset('images/candidate-placeholder.jpg') }}"
                                    alt="Profile Picture" class="rounded-circle img-fluid"
                                    style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #f8f9fa;">

                                {{-- Only show Change Photo if viewing own profile --}}
                                @if (Auth::id() === $user->id)
                                    <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data"
                                        id="photoForm">
                                        @csrf
                                        <input type="file" name="photo" id="photoInput" style="display:none"
                                            onchange="document.getElementById('photoForm').submit()">
                                        <a href="javascript:void(0)" onclick="document.getElementById('photoInput').click()"
                                            class="d-block mt-2 small text-primary">
                                            <i class="fa fa-camera mr-1"></i> Change Photo
                                        </a>
                                    </form>
                                @endif
                            </div>
                            {{-- Changed to $user --}}
                            <h3 class="font-weight-bold mb-0">{{ $user->name }}</h3>
                            <p class="text-muted mb-3">
                                {{ $user->title ?? 'Professional Title' }}
                                @if (Auth::id() === $user->id)
                                    <a href="#" data-toggle="modal" data-target="#editBasicInfoModal"
                                        class="small text-primary ml-1"><i class="fa fa-edit"></i></a>
                                @endif
                            </p>

                            {{-- CV Section --}}
                            <div class="cv-section mt-3">
                                @if ($user->cv_path)
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <a href="{{ asset('uploads/cvs/' . $user->cv_path) }}" target="_blank"
                                            class="btn btn-primary btn-sm px-4 mr-2" style="border-radius: 20px;">
                                            View CV <i class="fa fa-eye ml-1"></i>
                                        </a>
                                        @if (Auth::id() === $user->id)
                                            <form action="{{ route('profile.cv.delete') }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    style="border-radius: 20px;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    @if (Auth::id() === $user->id)
                                        <button class="btn btn-outline-primary btn-sm px-4" style="border-radius: 20px;"
                                            data-toggle="modal" data-target="#editBasicInfoModal">
                                            Add CV <i class="fa fa-plus ml-1"></i>
                                        </button>
                                    @else
                                        <span class="text-muted small">No CV uploaded.</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold mb-3">Contact Info
                                @if (Auth::id() === $user->id)
                                    <a href="#" data-toggle="modal" data-target="#editBasicInfoModal"
                                        class="float-right small text-primary"><i class="fa fa-edit"></i></a>
                                @endif
                            </h5>
                            <ul class="list-unstyled mb-0" style="font-size: 14px;">
                                <li class="mb-2"><i class="fa fa-envelope mr-3 text-primary"></i> {{ $user->email }}</li>
                                <li class="mb-2"><i class="fa fa-phone mr-3 text-primary"></i>
                                    {{ $user->phone ?? 'Not provided' }}</li>
                                <li class="mb-2"><i class="fa fa-map-marker mr-3 text-primary"></i>
                                    {{ $user->address ?? 'Not provided' }}</li>
                                <li class="mb-0"><i class="fa fa-calendar mr-3 text-primary"></i> Joined
                                    {{ $user->created_at->format('M Y') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="col-lg-8">
                    {{-- Professional Summary --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="font-weight-bold text-dark mb-0">Professional Summary</h4>
                                @if (Auth::id() === $user->id)
                                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 20px;"
                                        data-toggle="modal" data-target="#editSummaryModal"><i class="fa fa-edit"></i>
                                        Edit</button>
                                @endif
                            </div>
                            <p class="text-muted">
                                {{ $user->summary ?? 'Professional summary describes expertise and passion.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Work Experience --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="font-weight-bold text-dark mb-0">Work Experience</h4>
                                @if (Auth::id() === $user->id)
                                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 20px;"
                                        data-toggle="modal" data-target="#addExpModal"><i class="fa fa-plus"></i> Add
                                        New</button>
                                @endif
                            </div>
                            @forelse($user->experiences as $exp)
                                <div
                                    class="experience-item mb-4 pb-4 border-bottom d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="font-weight-bold text-primary mb-1">{{ $exp->title }}</h6>
                                        <p class="mb-1"><strong>{{ $exp->company }}</strong> | {{ $exp->duration }}</p>
                                        <p class="text-muted small mb-0">{{ $exp->description }}</p>
                                    </div>
                                    @if (Auth::id() === $user->id)
                                        <form action="{{ route('experience.delete', $exp->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn text-danger btn-sm"><i
                                                    class="fa fa-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted small">No work experience added yet.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Education --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="font-weight-bold text-dark mb-0">Education</h4>
                                @if (Auth::id() === $user->id)
                                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 20px;"
                                        data-toggle="modal" data-target="#addEduModal"><i class="fa fa-plus"></i> Add
                                        New</button>
                                @endif
                            </div>
                            @forelse($user->educations as $edu)
                                <div class="education-item mb-3 d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="font-weight-bold text-primary mb-1">{{ $edu->degree }}</h6>
                                        <p class="mb-0 text-dark">{{ $edu->institution }}</p>
                                        <p class="text-muted small">{{ $edu->duration }}</p>
                                    </div>
                                    @if (Auth::id() === $user->id)
                                        <form action="{{ route('education.delete', $edu->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn text-danger btn-sm"><i
                                                    class="fa fa-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted small">No education history added yet.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Technical Skills --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="font-weight-bold text-dark mb-0">Technical Skills</h4>
                                @if (Auth::id() === $user->id)
                                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 20px;"
                                        data-toggle="modal" data-target="#editSkillsModal"><i class="fa fa-edit"></i>
                                        Edit</button>
                                @endif
                            </div>
                            <div class="skills-tags">
                                @if ($user->skills)
                                    @foreach (explode(',', $user->skills) as $skill)
                                        <span
                                            class="badge badge-primary px-3 py-2 mb-2 mr-1 shadow-xs">{{ trim($skill) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">No technical skills listed.</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Showcase Work --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5 text-center">
                            <h5 class="font-weight-bold mb-4">Showcase Work
                                @if (Auth::id() === $user->id)
                                    <a href="#" data-toggle="modal" data-target="#editSocialModal"
                                        class="small text-primary ml-2"><i class="fa fa-edit"></i></a>
                                @endif
                            </h5>
                            <div class="row">
                                @php
                                    $platforms = [
                                        'github_url' => [
                                            'name' => 'GitHub',
                                            'icon' => 'fa-github',
                                            'color' => 'text-dark',
                                        ],
                                        'linkedin_url' => [
                                            'name' => 'LinkedIn',
                                            'icon' => 'fa-linkedin',
                                            'color' => 'text-primary',
                                        ],
                                        'portfolio_url' => [
                                            'name' => 'Portfolio',
                                            'icon' => 'fa-globe',
                                            'color' => 'text-danger',
                                        ],
                                    ];
                                @endphp

                                @foreach ($platforms as $field => $info)
                                    <div class="col-md-4 mb-3">
                                        @if ($user->$field)
                                            @php
                                                $url = $user->$field;
                                                $fullUrl = Str::startsWith($url, 'http') ? $url : 'https://' . $url;
                                            @endphp
                                            <a href="{{ $fullUrl }}" target="_blank"
                                                class="btn btn-light btn-block p-3 border shadow-xs {{ $info['color'] }}">
                                                <i class="fa {{ $info['icon'] }} fa-2x d-block mb-2"></i>
                                                {{ $info['name'] }}
                                            </a>
                                        @else
                                            <div class="btn btn-light btn-block p-3 border shadow-xs text-muted disabled"
                                                style="opacity: 0.6;">
                                                <i class="fa {{ $info['icon'] }} fa-2x d-block mb-2"></i> No
                                                {{ $info['name'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Applied Jobs Section --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="font-weight-bold text-dark mb-4">Applied Jobs</h4>

                            @forelse($appliedJobs as $application)
                                <div class="applied-job-item mb-3 p-3 border rounded d-flex justify-content-between align-items-center"
                                    style="background: #fcfcfc;">
                                    <div class="d-flex align-items-center">
                                        <div class="icon mr-3 bg-white shadow-sm d-flex justify-content-center align-items-center"
                                            style="width: 50px; height: 50px; border-radius: 10px;">
                                            <i class="fa fa-briefcase text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold mb-0 text-dark">
                                                {{ $application->jobCircular->title }}</h6>
                                            <p class="mb-0 small text-muted">{{ $application->jobCircular->company_name }}
                                                •
                                                <span
                                                    class="badge {{ $application->status == 'Pending' ? 'badge-warning' : ($application->status == 'Rejected' ? 'badge-danger' : 'badge-success') }}">
                                                    {{ $application->status }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    @if (Auth::id() === $user->id)
                                        <form action="{{ route('application.remove', $application->id) }}" method="POST"
                                            onsubmit="return confirm('Withdraw this application?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0"
                                                title="Withdraw Application">
                                                <i class="fa fa-times-circle fa-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-3">
                                    <p class="text-muted small mb-0">No job applications yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Only Render Modals for the profile owner --}}
    @if (Auth::id() === $user->id)
        {{-- Edit Basic Info Modal --}}
        <div class="modal fade" id="editBasicInfoModal" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content" style="border-radius: 15px;">
                        <div class="modal-body p-4">
                            <h5 class="font-weight-bold mb-3">Edit Basic Information</h5>
                            <div class="form-group">
                                <label class="small font-weight-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Professional Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $user->title }}">
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold">Upload CV (PDF/Doc)</label>
                                <input type="file" name="cv" class="form-control-file border p-2 w-100">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-2">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection
