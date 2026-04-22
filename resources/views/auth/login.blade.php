@extends('layouts.app')

@section('content')
    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-wrap p-4 p-md-5 shadow-sm bg-white" style="border-radius: 10px;">
                        <div class="text-center mb-4">
                            <h3 class="font-weight-bold">Hire Harmony</h3>
                        </div>

                        {{-- Tab Navigation --}}
                        <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-login-tab" data-toggle="pill" href="#pills-login"
                                    role="tab">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-register-tab" data-toggle="pill" href="#pills-register"
                                    role="tab">Register</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            {{-- 1. LOGIN FORM --}}
                            <div class="tab-pane fade show active" id="pills-login" role="tabpanel">
                                {{-- FIXED: Points to 'login.post' or 'login' --}}
                                <form action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Gmail Address" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Password"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block py-3">Log In</button>
                                    </div>
                                </form>
                            </div>

                            {{-- 2. REGISTER FORM --}}
                            <div class="tab-pane fade" id="pills-register" role="tabpanel">
                                {{-- FIXED: Changed '#' to '{{ route('register') }}' --}}
                                <form action="{{ route('register') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Full Name / Company Name" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Gmail Address" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <select name="user_type" class="form-control" required
                                            style="height: 52px !important; border: 1px solid #ced4da;">
                                            <option value="" disabled selected>Register as a...</option>
                                            <option value="candidate">Job Applicant / Candidate</option>
                                            <option value="company">Company</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Create Password" required>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block py-3"
                                            style="background-color: #ff6600; border-color: #ff6600;">Register Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
