@extends('layouts.app')

@section('title', __('Profile') . ' — ' . \App\Services\BrandingService::appName())

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Profile') }}</li>
@endsection

@section('content')
    <h1 class="h4 mb-3">{{ __('My Profile') }}</h1>

    @if($user->must_change_password)
        <div class="alert alert-warning" role="alert">{{ __('Please change your password — it was set by an administrator or generated during setup.') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">{{ __('Profile information') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email address') }}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <p class="text-muted small">{{ __('Roles') }}: {{ $user->getRoleNames()->implode(', ') ?: __('None') }}</p>
                        <button type="submit" class="btn btn-primary">{{ __('Save profile') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card" id="password">
                <div class="card-header">{{ __('Change password') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Current password') }}</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required autocomplete="current-password">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('New password') }}</label>
                            <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm new password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Change password') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
