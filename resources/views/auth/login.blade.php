@extends('layouts.guest')

@section('title', __('Log in') . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <div class="auth-card">
        <div class="auth-card__head">
            <span class="auth-icon-circle"><x-icon name="lock-keyhole" :size="24" /></span>
            <h2>{{ __('Welcome back') }}</h2>
            <p>{{ __('Sign in to :app to continue.', ['app' => \App\Services\BrandingService::appName()]) }}</p>
        </div>

        @if(session('status'))
            <x-alert type="info" dismissible>{{ session('status') }}</x-alert>
        @endif

        @if($errors->any())
            <x-alert type="danger">
                <strong class="d-block mb-1">{{ __('Sign-in failed') }}</strong>
                {{ $errors->first() }}
            </x-alert>
        @endif

        <form method="POST" action="{{ route('login') }}" data-loading-form novalidate>
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email address') }}</label>
                <input
                    type="text"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    inputmode="text"
                    placeholder="name@example.com"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="loginPassword" class="form-label">{{ __('Password') }}</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="loginPassword"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        autocomplete="current-password"
                        placeholder="{{ __('Enter your password') }}"
                    >
                    <button
                        type="button"
                        class="btn"
                        data-password-toggle="loginPassword"
                        aria-label="{{ __('Show password') }}"
                        aria-pressed="false"
                        title="{{ __('Show password') }}"
                    >
                        <span data-eye-show><x-icon name="eye" :size="18" /></span>
                        <span data-eye-hide class="d-none"><x-icon name="eye-off" :size="18" /></span>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="auth-meta">
                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input" @checked(old('remember'))>
                    <label for="remember" class="form-check-label">{{ __('Remember me') }}</label>
                </div>
                <a href="{{ route('password.request') }}" class="fw-semibold">{{ __('Forgot password?') }}</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" data-loading-button>
                <span class="spinner-border spinner-border-sm d-none" data-loading-spinner role="status" aria-hidden="true"></span>
                <span data-loading-label>{{ __('Sign in') }}</span>
                <span class="d-none" data-loading-text>{{ __('Signing in…') }}</span>
            </button>
        </form>

        <p class="auth-help text-center mt-3 mb-0">
            {{ __('Use the email address registered to your account. Contact your administrator if you cannot sign in.') }}
        </p>
    </div>
@endsection
