@php
    $system = \App\Models\SystemSetting::first();
@endphp

@extends('auth.app')

@section('title', 'Confirm Password')

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
                <h4 class="fw-bold text-dark">Confirm Password</h4>
                <p class="text-muted small">This is a secure area. Please confirm your password before continuing.</p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="mb-4">
                    <label for="password" class="form-label text-muted">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="········" required autocomplete="current-password" autofocus>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid mb-3">
                    <button class="btn btn-primary py-2 fw-bold" type="submit">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
