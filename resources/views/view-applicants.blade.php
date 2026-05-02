@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="font-weight-bold mb-4">Applicants for: {{ $job->title }}</h3>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Candidate</th>
                            <th>Status & Interview Schedule</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applicants as $app)
                            <tr>
                                <td class="align-middle">
                                    <strong>{{ $app->user->name }}</strong><br>
                                    <small>{{ $app->user->email }}</small>
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('application.status', $app->id) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <div class="mr-2">
                                            <select name="status" class="form-control form-control-sm w-auto status-select" data-app-id="{{ $app->id }}">
                                                <option value="Pending" {{ $app->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Interview Set" {{ $app->status == 'Interview Set' ? 'selected' : '' }}>Interview Set</option>
                                                <option value="Rejected" {{ $app->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>

                                        {{-- Date Time Picker: Only shows importance if status is Interview Set --}}
                                        <div id="timeInput{{ $app->id }}" class="{{ $app->status == 'Interview Set' ? '' : 'd-none' }}">
                                            <input type="datetime-local" name="interview_time" 
                                                   value="{{ $app->interview_time ? \Carbon\Carbon::parse($app->interview_time)->format('Y-m-d\TH:i') : '' }}"
                                                   class="form-control form-control-sm">
                                        </div>

                                        <button type="submit" class="btn btn-sm btn-primary ml-2">Update</button>
                                    </form>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('candidate.public.view', $app->user->unique_id) }}"
                                        class="btn btn-sm btn-dark">View Profile</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection