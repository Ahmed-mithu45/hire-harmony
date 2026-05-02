@extends('layouts.app')

@section('content')
    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row">
                {{-- SIDEBAR --}}
                <div class="col-lg-4">
                    {{-- 1. Profile Picture Card --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body text-center p-5">
                            <div class="profile-image-container mb-3">
                                <img src="{{ $user->profile_photo ? asset('images/profiles/' . $user->profile_photo) : asset('images/candidate-placeholder.jpg') }}"
                                    alt="Profile Picture" class="rounded-circle img-fluid"
                                    style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #f8f9fa;">

                                @if (Auth::id() === $user->id)
                                    <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                                        @csrf
                                        <input type="file" name="photo" id="photoInput" style="display:none" onchange="document.getElementById('photoForm').submit()">
                                        <a href="javascript:void(0)" onclick="document.getElementById('photoInput').click()" class="d-block mt-2 small text-primary">
                                            <i class="fa fa-camera mr-1"></i> Change Photo
                                        </a>
                                    </form>
                                @endif
                            </div>
                            <h3 class="font-weight-bold mb-0">{{ $user->name }}</h3>
                            <p class="text-muted mb-3">{{ $user->title ?? 'Professional Title' }} 
                                @if (Auth::id() === $user->id)
                                    <a href="#" data-toggle="modal" data-target="#editBasicInfoModal" class="small text-primary ml-1"><i class="fa fa-edit"></i></a>
                                @endif
                            </p>

                            <div class="cv-section mt-3">
                                @if ($user->cv_path)
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <a href="{{ asset('uploads/cvs/' . $user->cv_path) }}" target="_blank" class="btn btn-primary btn-sm px-4 mr-2" style="border-radius: 20px;">
                                            View CV <i class="fa fa-eye ml-1"></i>
                                        </a>
                                        @if (Auth::id() === $user->id)
                                            <form action="{{ route('profile.cv.delete') }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 20px;"><i class="fa fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    @if (Auth::id() === $user->id)
                                        <button class="btn btn-outline-primary btn-sm px-4" style="border-radius: 20px;" data-toggle="modal" data-target="#editBasicInfoModal">
                                            Add CV <i class="fa fa-plus ml-1"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 2. Preferred Roles --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold mb-3">Preferred Roles
                                @if (Auth::id() === $user->id)
                                    <a href="#" data-toggle="modal" data-target="#editPreferencesModal" class="float-right small text-primary"><i class="fa fa-edit"></i></a>
                                @endif
                            </h5>
                            <div class="preferences-tags">
                                @if ($user->preferred_jobs)
                                    @foreach (explode(',', $user->preferred_jobs) as $pref)
                                        <span class="badge badge-info px-2 py-1 mb-1 mr-1" style="background: #e7f3ff; color: #007bff; border: 1px solid #cce5ff;">{{ trim($pref) }}</span>
                                    @endforeach
                                @else
                                    <p class="text-muted small mb-0">No preferences set.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 3. Contact Info --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold mb-3">Contact Info
                                @if (Auth::id() === $user->id)
                                    <a href="#" data-toggle="modal" data-target="#editBasicInfoModal" class="float-right small text-primary"><i class="fa fa-edit"></i></a>
                                @endif
                            </h5>
                            <ul class="list-unstyled mb-0" style="font-size: 14px;">
                                <li class="mb-2"><i class="fa fa-envelope mr-3 text-primary"></i> {{ $user->email }}</li>
                                <li class="mb-2"><i class="fa fa-phone mr-3 text-primary"></i> {{ $user->phone ?? 'Not provided' }}</li>
                                <li class="mb-2"><i class="fa fa-map-marker mr-3 text-primary"></i> {{ $user->address ?? 'Not provided' }}</li>
                                <li class="mb-0"><i class="fa fa-calendar mr-3 text-primary"></i> Joined {{ $user->created_at->format('M Y') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="col-lg-8">
                    {{-- 4. Summary --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="font-weight-bold text-dark mb-0">Professional Summary</h4>
                                @if (Auth::id() === $user->id)
                                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 20px;" data-toggle="modal" data-target="#editSummaryModal"><i class="fa fa-edit"></i> Edit</button>
                                @endif
                            </div>
                            <p class="text-muted">{{ $user->summary ?? 'Add a professional summary describing your expertise.' }}</p>
                        </div>
                    </div>

                    {{-- 5. Experience --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="font-weight-bold text-dark mb-0">Work Experience</h4>
                                @if (Auth::id() === $user->id)
                                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 20px;" data-toggle="modal" data-target="#addExpModal"><i class="fa fa-plus"></i> Add New</button>
                                @endif
                            </div>
                            @forelse($user->experiences as $exp)
                                <div class="experience-item mb-4 pb-4 border-bottom d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="font-weight-bold text-primary mb-1 text-uppercase">{{ $exp->title }}</h6>
                                        <p class="mb-1"><strong>{{ $exp->company }}</strong> | {{ $exp->duration }}</p>
                                        <p class="text-muted small mb-0">{{ $exp->description }}</p>
                                    </div>
                                    @if (Auth::id() === $user->id)
                                        <form action="{{ route('experience.delete', $exp->id) }}" method="POST">@csrf @method('DELETE') <button type="submit" class="btn text-danger btn-sm"><i class="fa fa-trash"></i></button></form>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted small">No work experience added yet.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- 6. Education --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="font-weight-bold text-dark mb-0">Education</h4>
                                @if (Auth::id() === $user->id)
                                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 20px;" data-toggle="modal" data-target="#addEduModal"><i class="fa fa-plus"></i> Add New</button>
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
                                        <form action="{{ route('education.delete', $edu->id) }}" method="POST">@csrf @method('DELETE') <button type="submit" class="btn text-danger btn-sm"><i class="fa fa-trash"></i></button></form>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted small">No education history added yet.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- 7. Skills --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="font-weight-bold text-dark mb-0">Technical Skills</h4>
                                @if (Auth::id() === $user->id)
                                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 20px;" data-toggle="modal" data-target="#editSkillsModal"><i class="fa fa-edit"></i> Edit</button>
                                @endif
                            </div>
                            <div class="skills-tags">
                                @if ($user->skills)
                                    @foreach (explode(',', $user->skills) as $skill)
                                        <span class="badge badge-primary px-3 py-2 mb-2 mr-1 shadow-xs text-uppercase" style="background: #ff6a00; border: none; font-size: 11px;">{{ trim($skill) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">No technical skills listed.</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 8. Links --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5 text-center">
                            <h5 class="font-weight-bold mb-4">Showcase Work 
                                @if (Auth::id() === $user->id)
                                    <a href="#" data-toggle="modal" data-target="#editSocialModal" class="small text-primary ml-2"><i class="fa fa-edit"></i></a>
                                @endif
                            </h5>
                            <div class="row">
                                @php
                                    $platforms = [
                                        'github_url' => ['name' => 'GitHub', 'icon' => 'fa-github', 'color' => 'text-dark'],
                                        'linkedin_url' => ['name' => 'LinkedIn', 'icon' => 'fa-linkedin', 'color' => 'text-primary'],
                                        'portfolio_url' => ['name' => 'Portfolio', 'icon' => 'fa-globe', 'color' => 'text-danger'],
                                    ];
                                @endphp
                                @foreach ($platforms as $field => $info)
                                    <div class="col-md-4 mb-3">
                                        @if ($user->$field)
                                            <a href="{{ Str::startsWith($user->$field, 'http') ? $user->$field : 'https://' . $user->$field }}" target="_blank"
                                                class="btn btn-light btn-block p-3 border shadow-xs {{ $info['color'] }}">
                                                <i class="fa {{ $info['icon'] }} fa-2x d-block mb-2"></i> {{ $info['name'] }}
                                            </a>
                                        @else
                                            <div class="btn btn-light btn-block p-3 border shadow-xs text-muted disabled" style="opacity: 0.6;">
                                                <i class="fa {{ $info['icon'] }} fa-2x d-block mb-2"></i> No {{ $info['name'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- 9. Applied Jobs --}}
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::id() === $user->id))
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">
                                <h4 class="font-weight-bold text-dark mb-4">Applied Jobs</h4>
                                @forelse($appliedJobs as $application)
                                    <div class="applied-job-item mb-3 p-3 border rounded d-flex justify-content-between align-items-center" style="background: #fcfcfc;">
                                        <div class="d-flex align-items-center">
                                            <div class="icon mr-3 bg-white shadow-sm d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border-radius: 10px;">
                                                <i class="fa fa-briefcase text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="font-weight-bold mb-0 text-dark">{{ $application->jobCircular->title }}</h6>
                                                <p class="mb-0 small text-muted">
                                                    <span class="font-weight-bold text-secondary">{{ $application->jobCircular->company_name }}</span> • 
                                                    <span class="badge {{ $application->status == 'Pending' ? 'badge-warning' : ($application->status == 'Rejected' ? 'badge-danger' : 'badge-success') }}">
                                                        {{ $application->status }}
                                                    </span>
                                                </p>
                                                @if($application->status == 'Interview Set' && $application->interview_time)
                                                    <div class="mt-2 small text-dark p-2 rounded" style="background: #fff3cd; border: 1px solid #ffeeba; display: inline-block;">
                                                        <i class="fa fa-calendar-check-o text-warning mr-1"></i> 
                                                        <strong>Interview:</strong> {{ \Carbon\Carbon::parse($application->interview_time)->format('D, M d, Y | h:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @if (Auth::id() === $user->id)
                                            <form action="{{ route('application.remove', $application->id) }}" method="POST" onsubmit="return confirm('Withdraw?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0"><i class="fa fa-times-circle fa-lg"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-muted small text-center">No applications yet.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    {{-- 10. SMART RECOMMENDED JOBS --}}
                    @if(Auth::id() === $user->id && isset($relatedJobs) && $relatedJobs->count() > 0)
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">
                                <h4 class="font-weight-bold text-dark mb-4"><i class="fa fa-magic text-warning mr-2"></i>Recommended for You</h4>
                                
                                <div class="row">
                                    @foreach($relatedJobs as $job)
                                        <div class="col-md-4 course mb-4">
                                            <div class="img"
                                                style="background-image: url('{{ $job->company->profile_photo ? asset('images/profiles/' . $job->company->profile_photo) : asset('images/company-placeholder.jpg') }}'); background-size: contain; background-repeat: no-repeat; background-position: center; padding: 20px; background-color: #ffffff; border: 1px solid #eee; border-radius: 10px 10px 0 0; height: 180px;">
                                            </div>

                                            <div class="text pt-4 border border-top-0 px-3 pb-3" style="border-radius: 0 0 10px 10px;">
                                                <p class="meta d-flex" style="font-size: 10px;">
                                                    <span><i class="fa fa-building mr-1"></i>{{ Str::limit($job->company_name, 12) }}</span>
                                                    <span><i class="fa fa-users mr-1"></i>{{ sprintf('%02d', $job->openings) }} Openings</span>
                                                </p>
                                                
                                                <h3 style="font-size: 18px;"><a href="javascript:void(0)" data-toggle="modal"
                                                        data-target="#jobModal{{ $job->id }}">{{ $job->title }}</a></h3>
                                                
                                                {{-- NEW: Job Match Score Section --}}
                                                <div class="match-score-container mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <small class="text-muted font-weight-bold" style="font-size: 10px;">Match Score</small>
                                                        <span class="badge {{ $job->match_percentage >= 70 ? 'badge-success' : ($job->match_percentage >= 40 ? 'badge-warning' : 'badge-info') }}" style="font-size: 10px;">
                                                            {{ $job->match_percentage }}%
                                                        </span>
                                                    </div>
                                                    <div class="progress" style="height: 6px; border-radius: 10px; background-color: #eee;">
                                                        <div class="progress-bar {{ $job->match_percentage >= 70 ? 'bg-success' : ($job->match_percentage >= 40 ? 'bg-warning' : 'bg-info') }}" 
                                                             role="progressbar" 
                                                             style="width: {{ $job->match_percentage }}%" 
                                                             aria-valuenow="{{ $job->match_percentage }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>

                                                <p class="small text-muted" style="height: 40px; overflow: hidden;">
                                                    {{ Str::limit($job->description, 60) }}</p>

                                                <div class="d-flex mt-4 align-items-center">
                                                    <p class="mb-0">
                                                        <button class="btn btn-dark py-2 px-3 mr-2" style="font-size: 10px; border-radius: 5px;"
                                                            data-toggle="modal" data-target="#jobModal{{ $job->id }}">
                                                            Details
                                                        </button>
                                                    </p>

                                                    <div class="mb-0">
                                                        @if (isset($appliedJobIds) && in_array($job->id, $appliedJobIds))
                                                            <button class="btn btn-success py-2 px-3"
                                                                style="font-size: 10px; border-radius: 5px; cursor: default;" disabled>
                                                                <i class="fa fa-check mr-1"></i> Applied
                                                            </button>
                                                        @else
                                                            <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary py-2 px-3"
                                                                    style="font-size: 10px; border-radius: 5px;">Apply</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @include('partials.job_modal', ['job' => $job])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- MODALS --}}
    @if (Auth::id() === $user->id)
        {{-- Profile Edit --}}
        <div class="modal fade" id="editBasicInfoModal" tabindex="-1">
            <div class="modal-dialog"><form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">@csrf
                <div class="modal-content" style="border-radius: 15px;"><div class="modal-body p-4">
                    <h5 class="font-weight-bold mb-3">Edit Profile</h5>
                    <div class="form-group"><label class="small font-weight-bold">Full Name</label><input type="text" name="name" class="form-control" value="{{ $user->name }}" required></div>
                    <div class="form-group"><label class="small font-weight-bold">Title</label><input type="text" name="title" class="form-control" value="{{ $user->title }}"></div>
                    <div class="form-group"><label class="small font-weight-bold">Phone</label><input type="text" name="phone" class="form-control" value="{{ $user->phone }}"></div>
                    <div class="form-group"><label class="small font-weight-bold">Address</label><input type="text" name="address" class="form-control" value="{{ $user->address }}"></div>
                    <div class="form-group"><label class="small font-weight-bold">CV (PDF)</label><input type="file" name="cv" class="form-control-file border p-2 w-100"></div>
                    <button type="submit" class="btn btn-primary w-100 mt-2">Save</button>
                </div></div></form></div>
        </div>

        {{-- Summary Edit --}}
        <div class="modal fade" id="editSummaryModal" tabindex="-1">
            <div class="modal-dialog"><form action="{{ route('profile.update') }}" method="POST">@csrf
                <div class="modal-content" style="border-radius: 15px;"><div class="modal-body p-4">
                    <h5 class="font-weight-bold mb-3">Edit Summary</h5>
                    <span class="text-muted small d-block mb-2">Write a brief overview of your professional background.</span>
                    <textarea name="summary" class="form-control mb-3" rows="5">{{ $user->summary }}</textarea>
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </div></div></form></div>
        </div>

        {{-- Skills Edit --}}
        <div class="modal fade" id="editSkillsModal" tabindex="-1">
            <div class="modal-dialog"><form action="{{ route('profile.update') }}" method="POST">@csrf
                <div class="modal-content" style="border-radius: 15px;"><div class="modal-body p-4">
                    <h5 class="font-weight-bold mb-3">Technical Skills</h5>
                    <span class="text-muted small d-block mb-2">Separate skills with commas.</span>
                    <input type="text" name="skills" class="form-control mb-2" value="{{ $user->skills }}" placeholder="PHP, Laravel, MySQL">
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div></div></form></div>
        </div>

        {{-- Roles Edit --}}
        <div class="modal fade" id="editPreferencesModal" tabindex="-1">
            <div class="modal-dialog"><form action="{{ route('profile.update') }}" method="POST">@csrf
                <div class="modal-content" style="border-radius: 15px;"><div class="modal-body p-4">
                    <h5 class="font-weight-bold mb-3">Preferred Roles</h5>
                    <input type="text" name="preferred_jobs" class="form-control mb-2" value="{{ $user->preferred_jobs }}" placeholder="Web Developer, Designer">
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save</button>
                </div></div></form></div>
        </div>

        {{-- Links Edit --}}
        <div class="modal fade" id="editSocialModal" tabindex="-1">
            <div class="modal-dialog"><form action="{{ route('profile.update') }}" method="POST">@csrf
                <div class="modal-content" style="border-radius: 15px;"><div class="modal-body p-4">
                    <h5 class="font-weight-bold mb-3">Work Links</h5>
                    <div class="form-group"><label class="small font-weight-bold">GitHub</label><input type="text" name="github_url" class="form-control" value="{{ $user->github_url }}"></div>
                    <div class="form-group"><label class="small font-weight-bold">LinkedIn</label><input type="text" name="linkedin_url" class="form-control" value="{{ $user->linkedin_url }}"></div>
                    <div class="form-group"><label class="small font-weight-bold">Portfolio</label><input type="text" name="portfolio_url" class="form-control" value="{{ $user->portfolio_url }}"></div>
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </div></div></form></div>
        </div>

        {{-- Exp Add --}}
        <div class="modal fade" id="addExpModal" tabindex="-1">
            <div class="modal-dialog"><form action="{{ route('experience.add') }}" method="POST">@csrf
                <div class="modal-content" style="border-radius: 15px;"><div class="modal-body p-4">
                    <h5 class="font-weight-bold mb-3">Add Experience</h5>
                    <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>
                    <input type="text" name="company" class="form-control mb-2" placeholder="Company" required>
                    <input type="text" name="duration" class="form-control mb-2" placeholder="Duration" required>
                    <textarea name="description" class="form-control mb-3" placeholder="Description"></textarea>
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </div></div></form></div>
        </div>

        {{-- Edu Add --}}
        <div class="modal fade" id="addEduModal" tabindex="-1">
            <div class="modal-dialog"><form action="{{ route('education.add') }}" method="POST">@csrf
                <div class="modal-content" style="border-radius: 15px;"><div class="modal-body p-4">
                    <h5 class="font-weight-bold mb-3">Add Education</h5>
                    <input type="text" name="degree" class="form-control mb-2" placeholder="Degree" required>
                    <input type="text" name="institution" class="form-control mb-2" placeholder="Institution" required>
                    <input type="text" name="duration" class="form-control mb-2" placeholder="Year" required>
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </div></div></form></div>
        </div>
    @endif
@endsection