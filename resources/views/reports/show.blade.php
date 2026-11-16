{{-- Report screen — Phase 28. One generic view renders every report: filter bar, summary cards, data table, working export/print buttons. --}}
{{-- MERGE FIX 2026-11-13 (coordinator): this view used the
     x-app-layout component, but no AppLayout component or class
     exists anywhere, so the page could not render. Converted to
     the layouts.app pattern every other view uses; the header
     slot content is now a page header inside the content. --}}
@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ $title }}</h2>
            <div class="btn-group">
                @can('report.export')
                    <a href="{{ route('reports.pdf', ['report' => $report] + request()->query()) }}" class="btn btn-outline-danger btn-sm">PDF</a>
                    <a href="{{ route('reports.excel', ['report' => $report] + request()->query()) }}" class="btn btn-outline-success btn-sm">Excel</a>
                @endcan
                <a href="{{ route('reports.print', ['report' => $report] + request()->query()) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Print</a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        {{-- Filter bar --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.show', $report) }}" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label" for="date_from">{{ __('From') }}</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="date_to">{{ __('To') }}</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="country_id">{{ __('Country') }}</label>
                        <select name="country_id" id="country_id" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($filterOptions['countries'] as $country)
                                <option value="{{ $country->id }}" @selected($filters['country_id'] == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="branch_id">{{ __('Branch') }}</label>
                        <select name="branch_id" id="branch_id" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($filterOptions['branches'] as $branch)
                                <option value="{{ $branch->id }}" @selected($filters['branch_id'] == $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="agent_id">{{ __('Agent') }}</label>
                        <select name="agent_id" id="agent_id" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($filterOptions['agents'] as $agent)
                                <option value="{{ $agent->id }}" @selected($filters['agent_id'] == $agent->id)>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="status">{{ __('Status') }}</label>
                        <input type="text" name="status" id="status" class="form-control" value="{{ $filters['status'] }}" placeholder="{{ __('Status') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="search">{{ __('Search') }}</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ $filters['search'] }}" placeholder="{{ __('Search…') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                        <a href="{{ route('reports.show', $report) }}" class="btn btn-outline-secondary">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary --}}
        @if (! empty($summary))
            <div class="row mb-4">
                @foreach ($summary as $label => $value)
                    <div class="col-md-3 mb-2">
                        <div class="card">
                            <div class="card-body py-2">
                                <div class="text-muted small">{{ $label }}</div>
                                <div class="h5 mb-0">{{ is_array($value) ? collect($value)->map(fn ($v, $k) => $k.': '.$v)->implode(', ') : $value }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Data table --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>{{ $title }}</span>
                <span class="text-muted">{{ count($rows) }} {{ __('record(s)') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            @foreach ($columns as $label)
                                <th>{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                @foreach (array_keys($columns) as $key)
                                    <td>{{ $row[$key] ?? '' }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) }}" class="text-center text-muted py-4">{{ __('No records match the selected filters.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
