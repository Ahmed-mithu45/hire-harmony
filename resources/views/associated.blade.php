@extends('layouts.app')

@section('content')
    {{-- 1. Hero Section --}}
    <section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('images/about.jpg') }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-2 bread">Associate Companies</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ url('/') }}">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span>
                        <span>Partners <i class="ion-ios-arrow-forward"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Partner Companies Grid --}}
    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-2">
                <div class="col-md-8 text-center heading-section ftco-animate">
                    <h2 class="mb-4">Our <span>Associated</span> Partners</h2>
                    <p>Hire Harmony connects you with the most trusted industry leaders in Bangladesh and beyond.</p>
                </div>
            </div>

            <div class="row">
                @forelse($companies as $company)
                    <div class="col-md-6 col-lg-3 ftco-animate mb-4">
                        <div class="staff card shadow-sm border-0"
                            style="border-radius: 10px; overflow: hidden; background: #fff;">
                            <div class="img-wrap d-flex align-items-stretch" style="height: 180px; padding: 20px;">
                                <div class="img align-self-stretch"
                                    style="background-image: url('{{ $company->profile_photo ? asset('images/profiles/' . $company->profile_photo) : asset('images/company-logo-placeholder.png') }}');
                            width: 100%; background-size: contain; background-repeat: no-repeat; background-position: center;">
                                </div>
                            </div>
                            <div class="text pt-3 px-3 pb-4 text-center">
                                <h3 class="mb-2" style="font-size: 20px; font-weight: 700;">{{ $company->name }}</h3>
                                <span class="position d-block mb-3 text-primary"
                                    style="text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">
                                    {{ $company->title ?? 'Industry Partner' }}
                                </span>
                                <p class="small text-muted"><i
                                        class="fa fa-map-marker mr-2"></i>{{ $company->address ?? 'Bangladesh' }}</p>
                                <hr>
                                {{-- Public View Route --}}
                                <p><a href="{{ route('company.public.view', $company->unique_id) }}"
                                        class="btn btn-primary btn-outline-primary px-4" style="border-radius: 20px;">Visit
                                        Page</a></p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">No associated partners found at this time.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
