@extends('layouts.app')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0" style="border-radius: 15px;">
                    <div class="card-body p-5">
                        <h3 class="font-weight-bold text-center mb-4">Admin Login</h3>
                        <form action="{{ route('admin.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Admin Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block py-3">Login to Dashboard</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
