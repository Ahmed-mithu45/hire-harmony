@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-weight-bold">Admin Management Panel</h2>
            <span class="badge badge-danger p-2">Administrator Access</span>
        </div>

        {{-- Tabs Navigation --}}
        <ul class="nav nav-pills mb-4" id="adminTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#candidates"><i
                        class="fa fa-users mr-2"></i>Candidates</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#companies"><i class="fa fa-building mr-2"></i>Companies</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#feedback"><i class="fa fa-comments mr-2"></i>Feedbacks</a>
            </li>
            <li class="nav-item ml-auto">
                <a class="nav-link bg-dark text-white" data-toggle="pill" href="#settings"><i
                        class="fa fa-cog mr-2"></i>Settings</a>
            </li>
        </ul>

        <div class="tab-content">
            {{-- 1. Candidates Section --}}
            <div class="tab-pane fade show active" id="candidates">
                <div class="row">
                    @forelse($candidates as $user)
                        <div class="col-md-3 mb-4">
                            <div class="card shadow-sm border-0 text-center p-3" style="border-radius: 12px;">
                                <img src="{{ $user->profile_photo ? asset('images/profiles/' . $user->profile_photo) : asset('images/candidate-placeholder.jpg') }}"
                                    class="rounded-circle mx-auto mb-3"
                                    style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #eee;">
                                <h6 class="font-weight-bold mb-1 text-truncate">{{ $user->name }}</h6>
                                <p class="small text-muted mb-3">{{ $user->unique_id }}</p>
                                <div class="d-flex justify-content-around">
                                    {{-- Pointing to candidate profile --}}
                                    <a href="{{ route('candidate.public.view', $user->unique_id) }}"
                                        class="btn btn-sm btn-outline-dark px-3">View</a>
                                    <form action="{{ route('admin.user.delete', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this candidate permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger px-3">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No candidates registered yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 2. Companies Section --}}
            <div class="tab-pane fade" id="companies">
                <div class="row">
                    @forelse($companies as $user)
                        <div class="col-md-3 mb-4">
                            <div class="card shadow-sm border-0 text-center p-3" style="border-radius: 12px;">
                                <img src="{{ $user->profile_photo ? asset('images/profiles/' . $user->profile_photo) : asset('images/company-placeholder.jpg') }}"
                                    class="mx-auto mb-3" style="width: 80px; height: 80px; object-fit: contain;">
                                <h6 class="font-weight-bold mb-1 text-truncate">{{ $user->name }}</h6>
                                <p class="small text-muted mb-3">Company ID: {{ $user->unique_id }}</p>
                                <div class="d-flex justify-content-around mt-2">
                                    <a href="{{ route('company.public.view', $user->unique_id) }}"
                                        class="btn btn-sm btn-outline-dark px-3">Dashboard</a>
                                    <form action="{{ route('admin.user.delete', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this company and all its jobs?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger px-3">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No companies registered yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 3. Feedback Section --}}
            <div class="tab-pane fade" id="feedback">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="table-responsive p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4">Sender Info</th>
                                    <th class="border-0">Role</th>
                                    <th class="border-0">Message</th>
                                    <th class="border-0">Date Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($feedbacks as $fb)
                                    <tr>
                                        <td class="px-4">
                                            <span class="font-weight-bold text-dark">{{ $fb->name }}</span><br>
                                            <small class="text-muted">{{ $fb->email }}</small>
                                        </td>
                                        <td><span class="badge badge-soft-info p-2"
                                                style="background: #e1f5fe; color: #01579b;">{{ ucfirst($fb->user_type) }}</span>
                                        </td>
                                        <td style="max-width: 300px;">
                                            <p class="small mb-0">{{ $fb->message }}</p>
                                        </td>
                                        <td><small class="text-muted">{{ $fb->created_at->format('M d, Y h:i A') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No feedback messages found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 4. Settings Section (New) --}}
            <div class="tab-pane fade" id="settings">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 p-4" style="border-radius: 15px;">
                            <h4 class="font-weight-bold mb-4"><i class="fa fa-shield-alt mr-2 text-danger"></i>Admin
                                Security</h4>
                            <form action="{{ route('admin.settings.update') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">Update Admin Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ Auth::user()->email }}" required>
                                </div>
                                <hr>
                                <p class="small text-muted mb-3">Leave password fields blank if you don't want to change
                                    them.</p>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">New Password</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Minimum 6 characters">
                                </div>
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block py-2">Save New
                                    Credentials</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
