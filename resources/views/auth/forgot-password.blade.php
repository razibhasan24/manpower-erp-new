@extends('layouts.guest')

@section('title', __('Forgot password') . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <div class="auth-card">
        <div class="auth-card__head">
            <span class="auth-icon-circle"><x-icon name="mail" :size="24" /></span>
            <h2>{{ __('Forgot your password?') }}</h2>
            <p>{{ __('Enter your work email and we will send a secure reset link if the account exists.') }}</p>
        </div>

        @if(session('status'))
            <x-alert type="success">{{ session('status') }}</x-alert>
        @endif

        @if($errors->any())
            <x-alert type="danger">{{ $errors->first() }}</x-alert>
        @endif

        <form method="POST" action="{{ route('password.email') }}" data-loading-form novalidate>
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email address') }}</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    inputmode="email"
                    placeholder="name@company.com"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" data-loading-button>
                <span class="spinner-border spinner-border-sm d-none" data-loading-spinner role="status" aria-hidden="true"></span>
                <span data-loading-label>{{ __('Email password reset link') }}</span>
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small fw-semibold d-inline-flex align-items-center gap-1">
                <x-icon name="arrow-left" :size="14" /> {{ __('Back to sign in') }}
            </a>
        </div>

        <p class="auth-help text-center mt-3 mb-0">
            {{ __('For security, this page shows the same message whether or not the email is registered.') }}
        </p>
    </div>
@endsection
