{{--
    x-icon — inline Lucide SVG from config/icons.php.
    Usage: <x-icon name="users" :size="18" />
--}}
@props(['name' => 'circle-help', 'size' => 18, 'stroke' => 1.8])

@php
    $icons = config('icons', []);
    $inner = $icons[$name] ?? $icons['circle-help'] ?? '';
@endphp

<svg
    {{ $attributes->merge(['class' => 'icon']) }}
    xmlns="http://www.w3.org/2000/svg"
    width="{{ $size }}"
    height="{{ $size }}"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $stroke }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
    focusable="false"
>{!! $inner !!}</svg>
