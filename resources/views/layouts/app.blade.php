<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ in_array(request()->cookie('theme'), ['light', 'dark'], true) ? request()->cookie('theme') : (in_array(session('theme'), ['light', 'dark'], true) ? session('theme') : 'light') }}">
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
    @stack('styles')
</head>
<body class="app-body">
<a class="skip-link" href="#mainContent">{{ __('Skip to main content') }}</a>

<div class="app-shell" id="appShell">
    <x-sidebar />

    <div class="app-main">
        <x-header />

        <main class="app-content" id="mainContent" tabindex="-1">
            @if(session('success'))
                <x-alert type="success" dismissible autodismiss>{{ session('success') }}</x-alert>
            @endif
            @if(session('error'))
                <x-alert type="danger" dismissible autodismiss>{{ session('error') }}</x-alert>
            @endif
            @if(session('status'))
                <x-alert type="info" dismissible>{{ session('status') }}</x-alert>
            @endif
            @if($errors->any())
                <x-alert type="danger" dismissible>
                    <strong class="d-block mb-1">{{ __('Please fix the following:') }}</strong>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            @yield('content')
        </main>

        <footer class="app-footer">
            <span>{{ \App\Services\BrandingService::appName() }} &copy; {{ now()->year }}</span>
            <span>{{ now()->format('Y-m-d H:i') }} &middot; {{ \App\Services\BrandingService::timezone() }}</span>
        </footer>
    </div>
</div>

<div class="sidebar-backdrop" data-sidebar-backdrop aria-hidden="true"></div>
<div class="sidebar-tooltip" id="sidebarTooltip" role="tooltip" hidden></div>

<script>
    // Apply the persisted desktop rail state before the sidebar paints.
    (function () {
        try {
            if (window.matchMedia('(min-width: 992px)').matches
                && window.localStorage.getItem('erp.sidebar.collapsed') === '1') {
                document.getElementById('appShell')?.classList.add('is-collapsed');
            }
        } catch (error) {
            /* localStorage can be unavailable */
        }
    })();
</script>

@stack('scripts')
</body>
</html>
