{{--
    x-filter-bar
    Two supported shapes:
      1. Blank/slot form: <x-filter-bar> ...your <form> markup... </x-filter-bar>
      2. Declarative: <x-filter-bar :statuses="..." :filters="..." />
--}}
@props(['action' => null, 'method' => 'GET', 'filters' => [], 'search' => true, 'statuses' => []])

@php
    $hasSlot = trim((string) $slot) !== '';
    $hasStatusFilter = collect($filters)->contains(fn($filter) => ($filter['name'] ?? null) === 'status');
@endphp

@if ($hasSlot)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            {{ $slot }}
        </div>
    </div>
@else
    <form method="{{ $method }}" action="{{ $action ?? url()->current() }}" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">

                {{-- Search Input --}}
                @if ($search)
                    <div class="col-md-4 col-lg-4">
                        <label for="filter-search"
                            class="form-label small fw-semibold text-muted">{{ __('Search') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" name="search" id="filter-search"
                                class="form-control border-start-0 ps-0" value="{{ request('search') }}"
                                placeholder="{{ __('Search...') }}">
                        </div>
                    </div>
                @endif

                {{-- Status Dropdown (if provided and not overridden by filters) --}}
                @if (!empty($statuses) && !$hasStatusFilter)
                    <div class="col-md-3 col-lg-2">
                        <label for="filter-status"
                            class="form-label small fw-semibold text-muted">{{ __('Status') }}</label>
                        <select name="status" id="filter-status" class="form-select">
                            <option value="">{{ __('All statuses') }}</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Dynamic Filters --}}
                @foreach ($filters as $filter)
                    <div class="col-md-3 col-lg-2">
                        <label for="filter-{{ $filter['name'] }}"
                            class="form-label small fw-semibold text-muted">{{ $filter['label'] }}</label>
                        @if (($filter['type'] ?? 'text') === 'select')
                            <select name="{{ $filter['name'] }}" id="filter-{{ $filter['name'] }}"
                                class="form-select">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($filter['options'] ?? [] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ (string) request($filter['name'], $filter['value'] ?? '') === (string) $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $filter['type'] ?? 'text' }}" name="{{ $filter['name'] }}"
                                id="filter-{{ $filter['name'] }}" class="form-control"
                                value="{{ request($filter['name'], $filter['value'] ?? '') }}">
                        @endif
                    </div>
                @endforeach

                {{-- Action Buttons --}}
                <div class="col-auto ms-auto d-flex gap-2">
                    <x-button type="submit" variant="primary" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> {{ __('Filter') }}
                    </x-button>
                    <x-button href="{{ $action ?? url()->current() }}" variant="danger" class="btn btn-danger px-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('Clear') }}
                    </x-button>
                </div>
            </div>
        </div>
    </form>
@endif
