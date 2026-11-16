@props(['type' => 'info', 'dismissible' => false, 'autodismiss' => false])

@php
    $icons = [
        'success' => 'circle-check-big',
        'danger' => 'circle-alert',
        'warning' => 'triangle-alert',
        'info' => 'info',
    ];
    $icon = $icons[$type] ?? 'info';
@endphp

<div
    class="alert alert-{{ $type }} d-flex align-items-start gap-2 {{ $dismissible ? 'alert-dismissible fade show' : '' }}"
    role="alert"
    @if($autodismiss) data-autodismiss @endif
>
    <span class="flex-shrink-0 mt-1"><x-icon :name="$icon" :size="17" /></span>
    <div class="flex-grow-1">{{ $slot }}</div>
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
    @endif
</div>
