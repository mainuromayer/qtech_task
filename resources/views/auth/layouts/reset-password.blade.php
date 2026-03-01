@php
    $system = \App\Models\SystemSetting::first();
@endphp

@extends('auth.app')

@section('title', 'Reset Password')

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
                    <h4 class="fw-bold text-dark">Reset Password</h4>
                    <p class="text-muted">Enter your new password below</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-3">
                        <label for="email" class="form-label text-muted">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $request->email) }}" required readonly>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-muted">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="········" required autofocus>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label text-muted">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="········" required>
                    </div>

                    <div class="d-grid mb-3">
                        <button class="btn btn-primary py-2 fw-bold" type="submit">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection