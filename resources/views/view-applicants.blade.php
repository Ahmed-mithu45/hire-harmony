@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="font-weight-bold mb-0">Applicants for: {{ $job->title }}</h3>
            <span class="badge badge-primary px-3 py-2" style="border-radius: 20px;">
                Total Applicants: {{ sprintf('%02d', $applicants->count()) }}
            </span>
        </div>

        {{-- NEW: Custom Percentage Filter Form --}}
        <div class="mb-4 d-flex justify-content-end">
            <form action="{{ route('job.applicants', $job->id) }}" method="GET" class="form-inline">
                <label for="min_score" class="mr-2 font-weight-bold">Minimum NLP Match (%):</label>
                <div class="input-group mr-2">
                    <input type="number" name="min_score" id="min_score" class="form-control" 
                           style="border-radius: 5px 0 0 5px; width: 100px;" 
                           placeholder="e.g. 85" 
                           min="0" max="100" 
                           value="{{ $selectedScore ?? '' }}">
                    <div class="input-group-append">
                        <span class="input-group-text" style="border-radius: 0 5px 5px 0; background-color: #f8f9fa;">%</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark" style="border-radius: 5px;">Filter</button>
                @if(!empty($selectedScore))
                    <a href="{{ route('job.applicants', $job->id) }}" class="btn btn-outline-danger ml-2" style="border-radius: 5px;">Clear</a>
                @endif
            </form>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Candidate</th>
                            <th class="py-3">Smart Match Score</th>
                            <th class="py-3">Status & Interview Schedule</th>
                            <th class="py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applicants as $app)
                            <tr>
                                <td class="align-middle px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <img src="{{ $app->user->profile_photo ? asset('images/profiles/' . $app->user->profile_photo) : asset('images/candidate-placeholder.jpg') }}"
                                                 alt="" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                        </div>
                                        <div>
                                            <strong class="text-dark">{{ $app->user->name }}</strong><br>
                                            <small class="text-muted">{{ $app->user->email }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="progress mr-2" style="height: 8px; width: 60px; border-radius: 10px; background-color: #eee;">
                                            <div class="progress-bar {{ $app->match_score >= 70 ? 'bg-success' : ($app->match_score >= 40 ? 'bg-warning' : 'bg-info') }}"
                                                 role="progressbar"
                                                 style="width: {{ $app->match_score }}%">
                                            </div>
                                        </div>
                                        <span class="font-weight-bold {{ $app->match_score >= 70 ? 'text-success' : ($app->match_score >= 40 ? 'text-warning' : 'text-info') }}" style="font-size: 14px;">
                                            {{ $app->match_score }}%
                                        </span>
                                    </div>
                                    <small class="text-muted" style="font-size: 10px;">NLP Analyzed</small>
                                </td>

                                <td class="align-middle">
                                    <form action="{{ route('application.status', $app->id) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <div class="mr-2">
                                            <select name="status" class="form-control form-control-sm w-auto status-select" data-app-id="{{ $app->id }}" style="border-radius: 5px;">
                                                <option value="Pending" {{ $app->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Interview Set" {{ $app->status == 'Interview Set' ? 'selected' : '' }}>Interview Set</option>
                                                <option value="Rejected" {{ $app->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>

                                        <div id="timeInput{{ $app->id }}" class="{{ $app->status == 'Interview Set' ? '' : 'd-none' }}">
                                            <input type="datetime-local" name="interview_time"
                                                   value="{{ $app->interview_time ? \Carbon\Carbon::parse($app->interview_time)->format('Y-m-d\TH:i') : '' }}"
                                                   class="form-control form-control-sm" style="border-radius: 5px;">
                                        </div>

                                        <button type="submit" class="btn btn-sm btn-primary ml-2 shadow-sm" style="border-radius: 5px;">Update</button>
                                    </form>
                                </td>

                                <td class="align-middle text-right px-4">
                                    <a href="{{ route('candidate.public.view', $app->user->unique_id) }}"
                                        class="btn btn-sm btn-dark px-3" style="border-radius: 5px;">View Profile</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($applicants->isEmpty())
                <div class="text-center p-5">
                    <i class="fa fa-users fa-3x text-light mb-3"></i>
                    <p class="text-muted">No candidates meet the requested match percentage.</p>
                </div>
            @endif
        </div>
    </div>
@endsection