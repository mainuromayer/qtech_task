@php
    $system = \App\Models\SystemSetting::first();
@endphp

@extends('auth.app')

@section('title', 'Login')

@section('content')
<div class="col-md-6 col-lg-4 mx-auto">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="auth-logo">
                    <a href="{{ route('login') }}">
                        <img src="{{ $system->logo ?? asset('backend/images/logo-dark-3.png') }}" alt="Logo" height="50" class="mb-3">
                    </a>
                </div>
                <h4 class="fw-bold text-dark">Welcome Back</h4>
                <p class="text-muted">Sign in to continue to your dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label text-muted">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label text-muted mb-0" for="password">Password</label>
                        <a href="{{ route('password.request') }}" class="text-primary small">Forgot password?</a>
                    </div>
                    <div class="input-group">
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="········" required>
                        <button class="btn btn-outline-light border text-muted" type="button" id="togglePassword">
                            <i class="fa fa-eye-slash"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted small" for="remember-me">Keep me logged in</label>
                </div>

                <div class="d-grid mb-3">
                    <button class="btn btn-primary py-2 fw-bold" type="submit">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            password.type = 'password';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    });
</script>
@endpush