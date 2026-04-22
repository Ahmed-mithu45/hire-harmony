<div class="bg-top navbar-light mx-5">
    <div class="container-fluid mr-auto">
        <div class="row no-gutters d-flex align-items-center align-items-stretch">
            <div class="col-md-4 d-flex align-items-center py-4">
                <a class="navbar-brand" href="{{ url('/') }}">Hire <span>Harmony</span></a>
            </div>
            <div class="col-lg-8 d-block">
                <div class="row d-flex">
                    {{-- Email Section --}}
                    <div class="col-md d-flex topper align-items-center align-items-stretch py-md-4">
                        <div class="icon d-flex justify-content-center align-items-center"><span
                                class="fa fa-paper-plane"></span></div>
                        <div class="text">
                            <span>Email</span>
                            <span>hireharmony@gmail.com</span>
                        </div>
                    </div>
                    {{-- Call Section --}}
                    <div class="col-md d-flex topper align-items-center align-items-stretch py-md-4">
                        <div class="icon d-flex justify-content-center align-items-center"><span
                                class="fa fa-phone"></span></div>
                        <div class="text">
                            <span>Call</span>
                            <span>+880 1568-505325</span>
                        </div>
                    </div>

                    {{-- Dynamic User Section --}}
                    <div class="col-md topper d-flex align-items-center justify-content-end">
                        <p class="mb-0">
                            @if (Auth::check())
                                <div class="dropdown">
                                    <a href="#"
                                        class="btn py-2 px-3 btn-primary dropdown-toggle d-flex align-items-center justify-content-center"
                                        data-toggle="dropdown" style="border-radius: 30px;">
                                        <i class="fa fa-user-circle mr-2" style="font-size: 20px;"></i>
                                        <span>{{ Auth::user()->name }}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                        @if (Auth::user()->user_type == 'company')
                                            <a class="dropdown-item" href="{{ url('/company-dashboard') }}">
                                                <i class="fa fa-th-large mr-2"></i> Dashboard
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{ url('/profile') }}">
                                                <i class="fa fa-user mr-2"></i> My Profile
                                            </a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fa fa-sign-out mr-2"></i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}"
                                    class="btn py-2 px-3 btn-primary d-flex align-items-center justify-content-center">
                                    <span>Login/Register</span>
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container-fluid px-md-5">
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Request::is('/') ? 'active' : '' }}"><a href="{{ url('/') }}"
                        class="nav-link pl-0">Home</a></li>
                <li class="nav-item {{ Request::is('about') ? 'active' : '' }}"><a href="{{ url('/about') }}"
                        class="nav-link">About</a></li>
                <li class="nav-item {{ Request::is('jobs') ? 'active' : '' }}"><a href="{{ url('/jobs') }}"
                        class="nav-link">Available Jobs</a></li>
                <li class="nav-item {{ Request::is('associated') ? 'active' : '' }}"><a
                        href="{{ url('/associated') }}" class="nav-link">Associate Companies</a></li>
                <li class="nav-item {{ Request::is('contact') ? 'active' : '' }}"><a href="{{ url('/contact') }}"
                        class="nav-link">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
