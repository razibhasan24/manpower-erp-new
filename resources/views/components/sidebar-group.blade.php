@props(['group'])

<div
    class="sidebar-group {{ $group['active'] ? 'is-active' : '' }} {{ $group['open'] ? 'is-open' : '' }}"
    data-menu-group="{{ $group['key'] }}"
    data-open="{{ $group['open'] ? 'true' : 'false' }}"
>
    <button
        type="button"
        class="sidebar-group__toggle"
        data-sidebar-group="{{ $group['key'] }}"
        aria-expanded="{{ $group['open'] ? 'true' : 'false' }}"
        aria-controls="menu-{{ $group['key'] }}"
        aria-label="{{ $group['label'] }}"
    >
        <span class="sidebar-group__icon"><x-icon :name="$group['icon']" :size="18" /></span>
        <span class="sidebar-group__label">{{ $group['label'] }}</span>
        <span class="sidebar-group__chevron"><x-icon name="chevron-down" :size="14" /></span>
    </button>

    <div id="menu-{{ $group['key'] }}" class="sidebar-submenu" role="group" aria-label="{{ $group['label'] }}">
        <div class="sidebar-submenu__inner">
            @foreach($group['items'] as $item)
                <x-sidebar-link :item="$item" />
            @endforeach
        </div>
    </div>
</div>
