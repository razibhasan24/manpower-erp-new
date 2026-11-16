@props(['title', 'subtitle' => null])

<div class="page-head">
    <div>
        <h1>{{ $title }}</h1>
        @if($subtitle)
            <p class="page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="d-flex flex-wrap align-items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
