@props(['item'])

<a
    href="{{ $item['url'] }}"
    class="sidebar-link {{ !empty($item['active']) ? 'is-active' : '' }}"
    @if(!empty($item['active'])) aria-current="page" @endif
    title="{{ $item['label'] }}"
>
    <span class="sidebar-link__dot" aria-hidden="true"></span>
    <span class="sidebar-link__label">{{ $item['label'] }}</span>
</a>
