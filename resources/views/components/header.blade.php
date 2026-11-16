{{-- Dashboard header: sidebar toggle, brand, breadcrumb, theme, notifications, profile. --}}
@php
    $user = auth()->user();
    $profile = \App\Services\BrandingService::profile();
    $appName = \App\Services\BrandingService::appName();
    $shortName = \App\Services\BrandingService::shortName();
    $mark = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($shortName !== '' ? $shortName : $appName, 0, 2));
@endphp

<header class="app-header">
    <div class="app-header__start">
        <button
            type="button"
            class="header-icon-btn"
            data-sidebar-toggle
            aria-controls="appSidebar"
            aria-expanded="true"
            aria-label="{{ __('Toggle navigation') }}"
        >
            <x-icon name="menu" :size="19" />
        </button>

        <a href="{{ route('dashboard') }}" class="app-header__brand d-none d-sm-inline-flex" aria-label="{{ $appName }}">
            <span class="brand-mark">
                @if($profile !== null && $profile->logo_path)
                    <img src="{{ route('branding.logo') }}" alt="">
                @else
                    {{ $mark }}
                @endif
            </span>
            <span>{{ $appName }}</span>
        </a>

        <x-breadcrumb />
    </div>

    <div class="app-header__end">
        <button
            type="button"
            class="header-icon-btn"
            data-theme-toggle
            aria-pressed="false"
            aria-label="{{ __('Toggle theme') }}"
            title="{{ __('Toggle light / dark theme') }}"
        >
            <span class="theme-icon-light d-inline-flex"><x-icon name="moon" :size="18" /></span>
            <span class="theme-icon-dark"><x-icon name="sun" :size="18" /></span>
        </button>

        <x-notification-dropdown />
        <x-user-dropdown :user="$user" />
    </div>
</header>
