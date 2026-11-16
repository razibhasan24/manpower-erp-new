<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ in_array(request()->cookie('theme'), ['light', 'dark'], true) ? request()->cookie('theme') : 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', \App\Services\BrandingService::appName())</title>
    @if(isset($branding) && $branding?->favicon_path)
        <link rel="icon" href="{{ route('branding.logo') }}">
    @endif
    @php
        $primaryColor = \App\Services\BrandingService::primaryColor();
        $primaryRgb = sscanf($primaryColor, '#%02x%02x%02x') ?: [13, 110, 253];
    @endphp
    <style>
        :root {
            --bs-primary: {{ $primaryColor }};
            --bs-primary-rgb: {{ implode(', ', $primaryRgb) }};
            --bs-secondary: {{ \App\Services\BrandingService::secondaryColor() }};
            --brand-primary: {{ $primaryColor }};
            --brand-secondary: {{ \App\Services\BrandingService::secondaryColor() }};
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
<a class="skip-link" href="#authContent">{{ __('Skip to content') }}</a>

<button
    type="button"
    class="header-icon-btn auth-theme-toggle"
    data-theme-toggle
    aria-pressed="false"
    aria-label="{{ __('Toggle theme') }}"
    title="{{ __('Toggle light / dark theme') }}"
>
    <span class="theme-icon-light d-inline-flex"><x-icon name="moon" :size="18" /></span>
    <span class="theme-icon-dark"><x-icon name="sun" :size="18" /></span>
</button>

<div class="auth-shell">
    <section class="auth-brand" aria-label="{{ __('About') }} {{ \App\Services\BrandingService::appName() }}">
        <div class="auth-brand__top">
            <a href="{{ url('/') }}" class="auth-brand__logo">
                <span class="brand-mark">
                    @if(isset($branding) && $branding?->logo_path)
                        <img src="{{ route('branding.logo') }}" alt="">
                    @else
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(\App\Services\BrandingService::appName(), 0, 2)) }}
                    @endif
                </span>
                <span>
                    <span class="d-block fw-semibold">{{ \App\Services\BrandingService::appName() }}</span>
                    <span class="d-block small opacity-75">{{ \App\Services\BrandingService::timezone() }}</span>
                </span>
            </a>

            <h1>{{ __('Recruitment operations, from registration to arrival.') }}</h1>
            <p>{{ __('A single secure workspace for manpower agencies: candidates, agents, visas, flights, accounts and people.') }}</p>

            <ul class="auth-brand__points">
                <li><x-icon name="list-checks" :size="17" /> {{ __('One workflow across every recruitment stage') }}</li>
                <li><x-icon name="wallet" :size="17" /> {{ __('Agent and candidate ledgers stay reconciled') }}</li>
                <li><x-icon name="shield-check" :size="17" /> {{ __('Role, branch and country scoped access') }}</li>
            </ul>
        </div>

        <div class="auth-brand__bottom">
            <span>&copy; {{ now()->year }} {{ \App\Services\BrandingService::appName() }}</span>
            <span>{{ \App\Services\BrandingService::currency() }} &middot; {{ now()->format('d M Y') }}</span>
        </div>
    </section>

    <main class="auth-panel" id="authContent">
        @yield('content')

        <div class="auth-footer">
            {{ \App\Services\BrandingService::appName() }} &middot; {{ __('Secure sign-in') }}
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>
