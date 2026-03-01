@php
    $system = \App\Models\SystemSetting::first();
@endphp

@extends('auth.app')

@section('title', 'Verify Email')

@section('content')
    <div class="col-md-6 col-lg-4 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <div class="auth-logo">
                        <a href="{{ route('login') }}">
                            <img src="{{ $system->logo ?? asset('backend/images/logo-dark-3.png') }}" alt="Logo" height="50"
                                class="mb-3">
                        </a>
                    </div>
                    <h4 class="fw-bold text-dark">Verify Your Email</h4>
                    <p class="text-muted small">
                        Thanks for signing up! Before getting started, could you verify your email address by clicking on
                        the link we just emailed to you?
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success small mb-4" role="alert">
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                <div class="d-grid gap-2 mb-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button class="btn btn-primary py-2 fw-bold w-100" type="submit">Resend Verification Email</button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-link text-muted small text-decoration-none" type="submit">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection