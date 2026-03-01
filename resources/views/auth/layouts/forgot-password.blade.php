@php
    $system = \App\Models\SystemSetting::first();
@endphp

@extends('auth.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="col-md-6 col-lg-4 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="auth-logo">
                        <a href="{{ route('login') }}">
                            <img src="{{ $system->logo ?? asset('backend/images/logo-dark-3.png') }}" alt="Logo" height="50"
                                class="mb-3">
                        </a>
                    </div>
                    <h4 class="fw-bold text-dark">Forgot Password?</h4>
                    <p class="text-muted small">No problem. Just let us know your email address and we will email you a
                        password reset link.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success small mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label text-muted">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button class="btn btn-primary py-2 fw-bold" type="submit">Email Password Reset Link</button>
                    </div>
                </form>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-muted small"><i class="fa fa-arrow-left me-1"></i> Back to
                        Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection