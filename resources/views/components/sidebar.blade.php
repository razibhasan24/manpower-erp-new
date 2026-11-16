{{-- Sidebar — grouped, permission filtered, 22 required groups. --}}
@php
    $groups = \App\Support\Menu::groups(auth()->user());
    $profile = \App\Services\BrandingService::profile();
    $appName = \App\Services\BrandingService::appName();
    $shortName = \App\Services\BrandingService::shortName();
    $mark = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($shortName !== '' ? $shortName : $appName, 0, 2));
@endphp

<aside id="appSidebar" class="app-sidebar" aria-label="{{ __('Main navigation') }}">
    <a href="{{ route('dashboard') }}" class="app-sidebar__brand" aria-label="{{ $appName }}">
        <span class="brand-mark">
            @if($profile !== null && $profile->logo_path)
                <img src="{{ route('branding.logo') }}" alt="">
            @else
                {{ $mark }}
            @endif
        </span>
        <span class="brand-text">
            <strong title="{{ $appName }}">{{ $shortName !== '' ? $shortName : $appName }}</strong>
            <small>{{ __('Navigation') }}</small>
        </span>
    </a>

    <nav class="app-sidebar__nav">
        @forelse($groups as $group)
            <x-sidebar-group :group="$group" />
        @empty
            <p class="px-2 py-3 small text-white-50">{{ __('No menu items are available for your role.') }}</p>
        @endforelse
    </nav>

    <div class="app-sidebar__footer">
        {{ \App\Services\BrandingService::currency() }} &middot; {{ \App\Services\BrandingService::timezone() }}
        <span class="d-block opacity-75">{{ $appName }}</span>
    </div>
</aside>
