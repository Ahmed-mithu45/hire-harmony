{{-- 1. MODAL: Post New Job Circular --}}
<div class="modal fade" id="postJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form action="{{ route('job.store') }}" method="POST">
            @csrf
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="font-weight-bold mb-0">Post New Job Circular</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <div class="modal-body p-4 pt-2">
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 mb-3 shadow-sm" style="border-radius: 10px;">
                            <ul class="mb-0 small font-weight-bold">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fa fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <input type="hidden" name="company_name" value="{{ $company->name }}">

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="small font-weight-bold text-muted">Job Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Senior Full-Stack Developer" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold text-muted">Job Type</label>
                            <select name="job_type" class="form-control" required>
                                <option value="Full Time">Full Time</option>
                                <option value="Part Time">Part Time</option>
                                <option value="Internship">Internship</option>
                                <option value="Contract">Contract</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold text-muted">Openings</label>
                            <input type="number" name="openings" class="form-control" value="{{ old('openings', 1) }}" min="1" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label class="small font-weight-bold text-muted">Education Requirements</label>
                            <input type="text" name="educations" class="form-control" value="{{ old('educations') }}" placeholder="e.g. BSc in CSE" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label class="small font-weight-bold text-muted">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}" placeholder="e.g. IT, Software" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label class="small font-weight-bold text-muted">Skills Needed</label>
                            <input type="text" name="skills_needed" class="form-control" value="{{ old('skills_needed') }}" placeholder="e.g. Laravel, React" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label class="small font-weight-bold text-muted">Short Description</label>
                            <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="col-md-12 form-group">
                            <label class="small font-weight-bold text-muted">Detailed Job Responsibilities</label>
                            <textarea name="job_details" class="form-control" rows="5" required>{{ old('job_details') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2" style="border-radius: 10px; background-color: #ff6a00; border-color: #ff6a00; font-weight: bold;">
                        Publish Job Circular
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 2. MODAL: Edit Company Profile (THE PART THAT WAS MISSING) --}}
<div class="modal fade" id="editCompanyProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0 p-4">
                    <h5 class="font-weight-bold mb-0">Edit Company Information</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="form-group">
                        <label class="small font-weight-bold">Company Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $company->name }}">
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Professional Title / Industry</label>
                        <input type="text" name="title" class="form-control" value="{{ $company->title }}" placeholder="e.g. Software Development Firm">
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Location / Address</label>
                        <input type="text" name="address" class="form-control" value="{{ $company->address }}">
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Website URL</label>
                        <input type="url" name="portfolio_url" class="form-control" value="{{ $company->portfolio_url }}" placeholder="https://www.company.com">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2 py-2" style="border-radius: 10px;">
                        Update Company Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>