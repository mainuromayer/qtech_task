@php
    $users = null;
    if (auth()->check()) {
        $userId = \Illuminate\Support\Facades\Auth::id();

        // Query the users table to get the user with the given ID
        $users = \App\Models\User::where('id', $userId)->first();
    }
@endphp

@push('styles')
    <style>
        #waveIcon {
            animation: wave 2s infinite;
            transform-origin: 70% 70%;
        }

        @keyframes wave {
            0% {
                transform: rotate(0deg);
            }

            10% {
                transform: rotate(14deg);
            }

            20% {
                transform: rotate(-8deg);
            }

            30% {
                transform: rotate(14deg);
            }

            40% {
                transform: rotate(-4deg);
            }

            50% {
                transform: rotate(10deg);
            }

            60% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }
    </style>
@endpush

@extends('backend.app')

@section('title', 'Dashboard Page')

@section('content')
    <div class="container-fluid">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
            </div>
        </div>

        <!-- Start Main Widgets -->

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 rounded-4 shadow"
                    style="background: linear-gradient(135deg, #3b82f6, #1e3a8a); box-shadow: 0 12px 30px rgba(0,0,0,0.25);">
                    <div class="card-body d-flex flex-column align-items-center text-center p-5">

                        <!-- Animated SVG -->
                        <div class="mb-4">
                            <svg id="waveIcon" width="80" height="80" viewBox="0 0 64 64"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="32" cy="32" r="30" fill="#ffffff22" />
                                <path fill="#ffffff" d="M27 20h4v24h-4V20zm6 4h4v20h-4V24zm6 4h4v16h-4V28z" />
                            </svg>
                        </div>

                        <!-- Welcome Text -->
                        <h2 class="fw-bold text-white mb-2" style="font-size: 2rem;">
                            Welcome back, <span class="text-warning">{{ $users->name }}</span>
                        </h2>
                        <p class="text-light mb-0" style="font-size: 1.1rem;">
                            Wishing you a successful and productive day ahead!
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Widgets -->


        <!-- Start Stats Widgets -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="p-2 bg-primary-subtle rounded-3 me-3 text-primary">
                                <i data-feather="users" class="icon-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted fs-14 fw-medium">Total Users</p>
                                <h3 class="mb-0 fs-24 fw-bold text-dark">{{ $total_user }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="p-2 bg-success-subtle rounded-3 me-3 text-success">
                                <i data-feather="briefcase" class="icon-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted fs-14 fw-medium">Total Jobs</p>
                                <h3 class="mb-0 fs-24 fw-bold text-dark">{{ $total_job }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="p-2 bg-info-subtle rounded-3 me-3 text-info">
                                <i data-feather="file-text" class="icon-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted fs-14 fw-medium">Applications</p>
                                <h3 class="mb-0 fs-24 fw-bold text-dark">{{ $total_application }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Stats Widgets -->

    </div>
@endsection