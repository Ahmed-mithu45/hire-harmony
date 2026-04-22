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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applicants as $app)
                            <tr>
                                <td>
                                    <strong>{{ $app->user->name }}</strong><br>
                                    <small>{{ $app->user->email }}</small>
                                </td>
                                <td>
                                    <form action="{{ route('application.status', $app->id) }}" method="POST">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()"
                                            class="form-control form-control-sm w-auto">
                                            <option value="Pending" {{ $app->status == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="Interview Set"
                                                {{ $app->status == 'Interview Set' ? 'selected' : '' }}>Interview Set
                                            </option>
                                            <option value="Rejected" {{ $app->status == 'Rejected' ? 'selected' : '' }}>
                                                Rejected</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
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
