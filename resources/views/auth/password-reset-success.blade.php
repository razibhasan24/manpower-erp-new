@extends('layouts.guest')

@section('title', __('Password updated') . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <div class="auth-card">
        <div class="auth-card__head">
            <span class="auth-icon-circle text-success"><x-icon name="circle-check-big" :size="26" /></span>
            <h2>{{ __('Password updated') }}</h2>
            <p>{{ __('Your password has been changed successfully.') }}</p>
        </div>

        <x-alert type="success">
            {{ session('status') ?: __('You can now sign in with your new password.') }}
        </x-alert>

        <ul class="auth-success-list">
            <li><x-icon name="shield-check" :size="16" /> {{ __('The reset link can no longer be used.') }}</li>
            <li><x-icon name="log-out" :size="16" /> {{ __('Other remembered sign-ins for this account were revoked.') }}</li>
            <li><x-icon name="clock" :size="16" /> {{ __('Reset links expire automatically after :minutes minutes.', ['minutes' => config('auth.passwords.users.expire', 60)]) }}</li>
        </ul>

        <a href="{{ route('login') }}" class="btn btn-primary w-100 mt-4">{{ __('Sign in with the new password') }}</a>

        <p class="auth-help text-center mt-3 mb-0">
            {{ __('If you did not request this change, contact your administrator immediately.') }}
        </p>
    </div>
@endsection
