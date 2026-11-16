{{-- Breadcrumb — automatic from the menu, or explicit items passed in. --}}
@props(['items' => null])

@php
    $trail = $items ?? \App\Support\Menu::breadcrumb(auth()->user());
@endphp

<nav class="app-breadcrumb" aria-label="{{ __('Breadcrumb') }}">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}" aria-label="{{ __('Home') }}" title="{{ __('Home') }}">
                <x-icon name="house" :size="14" />
            </a>
        </li>

        @foreach($trail as $crumb)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" @if($loop->last) aria-current="page" @endif>
                @if(! $loop->last && ! empty($crumb['url']))
                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                @else
                    {{ $crumb['label'] }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>
