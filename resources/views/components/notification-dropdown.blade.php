@php
    $notifications = app(\App\Services\HeaderNotificationService::class)->for(auth()->user());
    $count = count($notifications);
    $toneClasses = [
        'warning' => 'text-bg-warning',
        'danger' => 'text-bg-danger',
        'info' => 'text-bg-info',
    ];
@endphp

<div class="dropdown">
    <button
        type="button"
        class="header-icon-btn position-relative"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false"
        aria-label="{{ __('Notifications') }}"
        title="{{ __('Notifications') }}"
    >
        <x-icon name="bell" :size="18" />
        @if($count > 0)
            <span class="notification-badge" aria-hidden="true"></span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end notification-menu">
        <div class="notification-menu__header">
            <strong>{{ __('Notifications') }}</strong>
            @if($count > 0)
                <span class="badge text-bg-primary">{{ $count }}</span>
            @endif
        </div>

        <div class="notification-menu__list">
            @forelse($notifications as $notification)
                <a href="{{ $notification['url'] }}" class="notification-item">
                    <span class="notification-item__icon">
                        <x-icon :name="$notification['icon']" :size="17" />
                    </span>
                    <span class="notification-item__text">
                        {{ $notification['label'] }}
                        <span class="notification-item__meta">{{ __('Open the related list') }}</span>
                    </span>
                </a>
            @empty
                <div class="notification-empty">
                    <x-icon name="circle-check-big" :size="26" />
                    {{ __('You are all caught up.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
