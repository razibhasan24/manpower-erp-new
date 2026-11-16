@extends('layouts.guest')

@section('title', __('Reset password') . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <div class="auth-card">
        <div class="auth-card__head">
            <span class="auth-icon-circle"><x-icon name="key-round" :size="24" /></span>
            <h2>{{ __('Set a new password') }}</h2>
            <p>{{ __('Choose a strong password for your :app account.', ['app' => \App\Services\BrandingService::appName()]) }}</p>
        </div>

        @if($errors->any())
            <x-alert type="danger">
                <strong class="d-block mb-1">{{ __('Password could not be reset') }}</strong>
                {{ $errors->first() }}
            </x-alert>
        @endif

        <form method="POST" action="{{ route('password.store') }}" data-loading-form novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email address') }}</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $request->email) }}"
                    required
                    autocomplete="username"
                    inputmode="email"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="newPassword" class="form-label">{{ __('New password') }}</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="newPassword"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        autocomplete="new-password"
                        minlength="8"
                    >
                    <button
                        type="button"
                        class="btn"
                        data-password-toggle="newPassword"
                        aria-label="{{ __('Show password') }}"
                        aria-pressed="false"
                    >
                        <span data-eye-show><x-icon name="eye" :size="18" /></span>
                        <span data-eye-hide class="d-none"><x-icon name="eye-off" :size="18" /></span>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">{{ __('At least 8 characters with upper and lower case letters, a number and a symbol.') }}</div>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">{{ __('Confirm new password') }}</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="confirmPassword"
                        class="form-control"
                        required
                        autocomplete="new-password"
                        minlength="8"
                    >
                    <button
                        type="button"
                        class="btn"
                        data-password-toggle="confirmPassword"
                        aria-label="{{ __('Show password') }}"
                        aria-pressed="false"
                    >
                        <span data-eye-show><x-icon name="eye" :size="18" /></span>
                        <span data-eye-hide class="d-none"><x-icon name="eye-off" :size="18" /></span>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" data-loading-button>
                <span class="spinner-border spinner-border-sm d-none" data-loading-spinner role="status" aria-hidden="true"></span>
                <span data-loading-label>{{ __('Reset password') }}</span>
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small fw-semibold d-inline-flex align-items-center gap-1">
                <x-icon name="arrow-left" :size="14" /> {{ __('Back to sign in') }}
            </a>
        </div>
    </div>
@endsection
