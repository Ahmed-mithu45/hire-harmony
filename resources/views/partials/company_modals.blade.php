{{-- MODAL: Post New Job Circular --}}
<div class="modal fade" id="postJobModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('job.store') }}" method="POST">
            @csrf
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0 p-4">
                    <h5 class="font-weight-bold mb-0">Post New Job Circular</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="form-group">
                        <label class="small font-weight-bold">Job Title</label>
                        <input type="text" name="title" class="form-control"
                            placeholder="e.g. Senior Full-Stack Developer" required>
                    </div>

                    {{-- Company Name is pre-filled from the user profile --}}
                    <div class="form-group">
                        <label class="small font-weight-bold">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ $company->name }}"
                            required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold">Job Type</label>
                            <select name="job_type" class="form-control" required>
                                <option value="Full Time">Full Time</option>
                                <option value="Part Time">Part Time</option>
                                <option value="Internship">Internship</option>
                                <option value="Contract">Contract</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold">Openings</label>
                            <input type="number" name="openings" class="form-control" value="1" min="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Category</label>
                        <input type="text" name="category" class="form-control"
                            placeholder="e.g. IT, Software, Marketing" required>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                            placeholder="Briefly describe the role and requirements..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2"
                        style="border-radius: 10px; background-color: #ff6a00; border-color: #ff6a00;">
                        Publish Job
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Edit Company Profile Info --}}
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
                        <input type="text" name="title" class="form-control" value="{{ $company->title }}"
                            placeholder="e.g. Global Tech Partner">
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Location / Address</label>
                        <input type="text" name="address" class="form-control" value="{{ $company->address }}">
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Website URL</label>
                        <input type="url" name="portfolio_url" class="form-control"
                            value="{{ $company->portfolio_url }}" placeholder="https://www.company.com">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2 py-2" style="border-radius: 10px;">
                        Update Company Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
