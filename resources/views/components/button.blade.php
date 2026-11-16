{{--
    x-button
    Props:
      - type: 'submit' | 'button' | 'reset' (default 'submit')
      - variant: Bootstrap colour — 'primary' | 'secondary' | 'success' | 'danger' | 'warning' | 'info' | 'light' | 'dark' | 'outline-*' (default 'primary')
      - href: when set, renders an <a> instead of a <button>
      - size: 'sm' | 'lg' | null
      - confirm: when set, shows a JS confirm dialog with this message before submitting/navigating
    Slot: the button label (HTML allowed).
--}}
@props(['type' => 'submit', 'variant' => 'warning', 'href' => null, 'size' => null, 'confirm' => null])

@php
    $classes = 'btn btn-' . $variant . ($size ? ' btn-' . $size : '');
    $attrs = $confirm ? 'onclick="return confirm(' . json_encode($confirm) . ')"' : '';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}
        {!! $attrs !!}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}
        {!! $attrs !!}>{{ $slot }}</button>
@endif
