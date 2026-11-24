{{--
    x-stat-card
    Props:
      - label (or legacy alias title): card title
      - value: big number/value (required)
      - sub: small sub-text under the value (optional)
      - icon: Lucide icon name shown in a soft brand tile (optional)
--}}
@props(['label' => null, 'title' => null, 'value', 'sub' => null, 'icon' => null])

@php
    $heading = $label ?? $title;
@endphp

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm h-100 stat-card-hover']) }}>
    <div class="card-body p-4 d-flex justify-content-between align-items-center gap-3">
        <div class="min-w-0">
            @if ($heading !== null && $heading !== '')
                <div class="text-muted small text-uppercase fw-semibold mb-1 text-truncate">{{ $heading }}</div>
            @endif

            <div class="fs-2 fw-bold text-dark mb-1 lh-1">{{ $value }}</div>

            @if ($sub)
                <div class="text-muted small mt-1">{{ $sub }}</div>
            @endif
        </div>

        @if ($icon)
            <div class="stat-card__icon bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center flex-shrink-0"
                aria-hidden="true" style="width: 56px; height: 56px;">
                <x-icon :name="$icon" :size="24" />
            </div>
        @endif
    </div>
</div>
