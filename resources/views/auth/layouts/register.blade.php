@php
    $system = \App\Models\SystemSetting::first();
@endphp

@extends('auth.app')

@section('title', 'Register')

@section('content')
    <div class="col-md-6 col-lg-5 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="auth-logo">
                        <a href="{{ route('login') }}">
                            <img src="{{ $system->logo ?? asset('backend/images/logo-dark-3.png') }}" alt="Logo" height="50"
                                class="mb-3">
                        </a>
                    </div>
                    <h4 class="fw-bold text-dark">Create Account</h4>
                    <p class="text-muted">Start your journey with Ediz Phactory</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label text-muted">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-muted">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label text-muted">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="········" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label text-muted">Confirm</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="········" required>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button class="btn btn-primary py-2 fw-bold" type="submit">Register</button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="text-muted small mb-0">Already have an account? <a href="{{ route('login') }}"
                            class="text-primary fw-bold">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection