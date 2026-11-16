@props(['user'])

@php
    $role = $user->getRoleNames()->first() ?? __('User');
    $initials = collect(preg_split('/\s+/', trim((string) $user->name)) ?: [])
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');
@endphp

<div class="dropdown">
    <button
        type="button"
        class="user-menu-button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        aria-label="{{ __('User menu') }}"
    >
        <span class="header-avatar" aria-hidden="true">{{ $initials !== '' ? $initials : '?' }}</span>
        <span class="d-none d-md-flex flex-column">
            <span class="user-name">{{ $user->name }}</span>
            <span class="user-role">{{ $role }}</span>
        </span>
        <span class="d-none d-md-inline-flex text-secondary"><x-icon name="chevron-down" :size="14" /></span>
    </button>

    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 250px;">
        <li class="user-menu-header">
            <div class="fw-semibold text-truncate">{{ $user->name }}</div>
            <div class="small text-secondary text-truncate">{{ $user->email }}</div>
            <span class="badge text-bg-light mt-2">{{ $role }}</span>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                <x-icon name="circle-user-round" :size="16" /> {{ __('Profile Settings') }}
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}#password">
                <x-icon name="key-round" :size="16" /> {{ __('Change Password') }}
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                    <x-icon name="log-out" :size="16" /> {{ __('Log out') }}
                </button>
            </form>
        </li>
    </ul>
</div>
