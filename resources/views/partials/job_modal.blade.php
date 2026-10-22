{{-- resources/views/partials/job_modal.blade.php --}}
<div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden;">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title font-weight-bold">{{ $job->title }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                {{-- Company Info --}}
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ $job->company->profile_photo ? asset('images/profiles/' . $job->company->profile_photo) : asset('images/company-placeholder.jpg') }}"
                        style="width: 50px; height: 50px; object-fit: contain;" class="mr-3">
                    <div>
                        <h6 class="text-primary font-weight-bold mb-0">{{ $job->company_name }}</h6>
                        <small class="text-muted"><i class="fa fa-tag mr-1"></i>Category: {{ $job->category }}</small>
                    </div>
                </div>

                {{-- Key Stats --}}
                <div class="row mb-4 p-3 bg-light mx-0" style="border-radius: 10px;">
                    <div class="col-6 col-md-3 mb-2">
                        <small class="text-muted d-block">Job Type</small>
                        <strong>{{ $job->job_type }}</strong>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <small class="text-muted d-block">Openings</small>
                        <strong>{{ sprintf('%02d', $job->openings) }} Positions</strong>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <small class="text-muted d-block">Required Education</small>
                        <strong>{{ $job->educations ?? 'Not Specified' }}</strong>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="font-weight-bold">Summary</h6>
                    <p class="text-muted small">{{ $job->description }}</p>
                </div>

                <div class="mb-4">
                    <h6 class="font-weight-bold">Required Skills</h6>
                    <p class="text-muted small">{{ $job->skills_needed ?? 'No specific skills listed.' }}</p>
                </div>

                <div class="mb-4">
                    <h6 class="font-weight-bold">Full Details</h6>
                    <div class="p-3 border rounded" style="background-color: #fafafa;">
                        <p class="text-muted small mb-0" style="white-space: pre-line;">{{ $job->job_details }}</p>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small"><i class="fa fa-calendar mr-1"></i> Posted {{ $job->created_at->format('M d, Y') }}</span>
                    @auth
                        @if (Auth::user()->user_type == 'candidate')
                            @if (isset($appliedJobIds) && in_array($job->id, $appliedJobIds))
                                <button class="btn btn-success px-4" style="border-radius: 8px;" disabled>
                                    <i class="fa fa-check mr-1"></i> Applied
                                </button>
                            @else
                                <form action="{{ route('jobs.apply', $job->id) }}" method="POST" class="apply-form">
                                    @csrf
                                    <button type="submit" 
                                            id="applyBtn{{ $job->id }}"
                                            class="btn btn-orange text-white px-4" 
                                            style="background: #ff6a00; border-radius: 8px;"
                                            onclick="btnTransform(this)">
                                        Apply Now
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

