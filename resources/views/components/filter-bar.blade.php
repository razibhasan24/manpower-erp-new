{{--
    x-filter-bar
    Two supported shapes:
      1. Blank/slot form: <x-filter-bar> ...your <form> markup... </x-filter-bar>
         (the component only provides the card wrapper; used by CRUD lists)
      2. Declarative: <x-filter-bar :statuses="..." :filters="..." />
         (the component builds the GET form; used by pipeline lists)

    Props:
      - action, method, filters, statuses, search
--}}
@props(['action' => null, 'method' => 'GET', 'filters' => [], 'search' => true, 'statuses' => []])

@php
    $hasSlot = trim((string) $slot) !== '';
    $hasStatusFilter = collect($filters)->contains(fn ($filter) => ($filter['name'] ?? null) === 'status');
@endphp

@if($hasSlot)
    <div class="card mb-3">
        <div class="card-body">
            {{ $slot }}
        </div>
    </div>
@else
    <form method="{{ $method }}" action="{{ $action ?? url()->current() }}" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                @if($search)
                    <div class="col-md-3">
                        <label for="filter-search" class="form-label">{{ __('Search') }}</label>
                        <input type="search" name="search" id="filter-search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('Search...') }}">
                    </div>
                @endif

                @if(! empty($statuses) && ! $hasStatusFilter)
                    <div class="col-md-3">
                        <label for="filter-status" class="form-label">{{ __('Status') }}</label>
                        <select name="status" id="filter-status" class="form-select">
                            <option value="">{{ __('All statuses') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @foreach($filters as $filter)
                    <div class="col-md-3">
                        <label for="filter-{{ $filter['name'] }}" class="form-label">{{ $filter['label'] }}</label>
                        @if(($filter['type'] ?? 'text') === 'select')
                            <select name="{{ $filter['name'] }}" id="filter-{{ $filter['name'] }}" class="form-select">
                                <option value="">{{ __('All') }}</option>
                                @foreach($filter['options'] ?? [] as $value => $label)
                                    <option value="{{ $value }}" {{ (string) request($filter['name'], $filter['value'] ?? '') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $filter['type'] ?? 'text' }}" name="{{ $filter['name'] }}" id="filter-{{ $filter['name'] }}" class="form-control" value="{{ request($filter['name'], $filter['value'] ?? '') }}">
                        @endif
                    </div>
                @endforeach

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                    <a href="{{ $action ?? url()->current() }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
                </div>
            </div>
        </div>
    </form>
@endif
