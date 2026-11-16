{{--
    x-stat-card
    Props:
      - label (or legacy alias title): card title
      - value: big number/value (required)
      - sub: small sub-text under the value (optional)
      - icon: Lucide icon name shown in a soft brand tile (optional)
    Both `label` and legacy `title` are accepted so older screens keep working.
--}}
@props(['label' => null, 'title' => null, 'value', 'sub' => null, 'icon' => null])

@php
    $heading = $label ?? $title;
@endphp

<div {{ $attributes->merge(['class' => 'card h-100']) }}>
    <div class="card-body d-flex justify-content-between align-items-start gap-3">
        <div class="min-w-0">
            @if($heading !== null && $heading !== '')
                <div class="small text-secondary text-truncate">{{ $heading }}</div>
            @endif
            <div class="h3 mb-0">{{ $value }}</div>
            @if($sub)<div class="small text-secondary mt-1">{{ $sub }}</div>@endif
        </div>
        @if($icon)
            <div class="stat-card__icon" aria-hidden="true">
                <x-icon :name="$icon" :size="20" />
            </div>
        @endif
    </div>
</div>
