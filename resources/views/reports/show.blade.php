{{-- Report screen — Design Update Only --}}
@extends('layouts.app')

@section('content')

    <style>
        .report-page {
            padding: 20px 0 30px;
            color: #263445;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 22px;
        }

        .report-title {
            font-size: 23px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .report-subtitle {
            color: #64748b;
            font-size: 13px;
            margin-top: 5px;
        }

        .report-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .report-page .btn {
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 15px;
            transition: all .2s ease;
        }

        .report-page .btn:hover {
            transform: translateY(-1px);
        }

        .report-page .card {
            border: 1px solid #e5eaf0;
            border-radius: 11px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .045);
            overflow: hidden;
            background: #fff;
        }

        .report-filter-card {
            margin-bottom: 22px;
        }

        .report-section-header {
            background: #fff;
            border-bottom: 1px solid #edf0f4;
            padding: 16px 20px;
            font-size: 15px;
            font-weight: 700;
            color: #263445;
        }

        .report-page .card-body {
            padding: 20px;
        }

        .report-page .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 7px;
        }

        .report-page .form-control,
        .report-page .form-select {
            min-height: 40px;
            border: 1px solid #dce2e9;
            border-radius: 7px;
            font-size: 13px;
            color: #334155;
            box-shadow: none;
        }

        .report-page .form-control:focus,
        .report-page .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
        }

        .report-filter-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .report-summary-card {
            height: 100%;
            border-left: 4px solid #3b82f6 !important;
            transition: all .2s ease;
        }

        .report-summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(15, 23, 42, .08);
        }

        .report-summary-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 9px;
        }

        .report-summary-value {
            font-size: 21px;
            font-weight: 700;
            color: #1e293b;
            word-break: break-word;
        }

        .report-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 17px 20px;
            background: #fff;
            border-bottom: 1px solid #e9edf2;
        }

        .report-table-title {
            font-size: 15px;
            font-weight: 700;
            color: #263445;
            margin: 0;
        }

        .report-record-count {
            font-size: 12px;
            color: #64748b;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .report-page .table {
            margin-bottom: 0;
        }

        .report-page .table thead th {
            background: #f1f5f9;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            padding: 14px 15px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .report-page .table tbody td {
            padding: 13px 15px;
            font-size: 13px;
            color: #475569;
            vertical-align: middle;
            border-color: #edf0f4;
        }

        .report-page .table tbody tr:hover {
            background: #f8fafc;
        }

        .report-empty {
            padding: 45px 15px !important;
            color: #94a3b8 !important;
            font-size: 14px !important;
        }

        .report-filter-actions {
            display: flex;
            flex-wrap: nowrap;
            gap: 8px;
            width: 100%;
        }

        .report-filter-actions .btn {
            flex: 1;
            white-space: nowrap;
        }

        @media (max-width: 767px) {
            .report-page {
                padding-top: 12px;
            }

            .report-title {
                font-size: 19px;
            }

            .report-header {
                align-items: flex-start;
            }

            .report-actions {
                width: 100%;
            }

            .report-actions .btn {
                flex: 1;
            }

            .report-page .card-body {
                padding: 15px;
            }

            .report-summary-value {
                font-size: 17px;
            }

            .report-page .table {
                min-width: 600px;
            }

            .report-filter-actions .btn {
                flex: 1;
            }
        }
    </style>

    <div class="container-fluid report-page">

        {{-- Page Header --}}
        <div class="report-header">
            <div>
                <h2 class="report-title">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    {{ $title }}
                </h2>
                <div class="report-subtitle">
                    Report Management / {{ $title }}
                </div>
            </div>

            <div class="report-actions">
                @can('report.export')
                    <a href="{{ route('reports.pdf', ['report' => $report] + request()->query()) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </a>

                    <a href="{{ route('reports.excel', ['report' => $report] + request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>
                @endcan

                <a href="{{ route('reports.print', ['report' => $report] + request()->query()) }}" target="_blank"
                    class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> Print
                </a>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="card report-filter-card">

            <div class="report-section-header">
                <i class="fas fa-filter text-primary me-2"></i>
                Report Filters
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('reports.show', $report) }}" class="row g-3">

                    <div class="col-md-2">
                        <label class="form-label" for="date_from">{{ __('From') }}</label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="{{ $filters['date_from'] }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="date_to">{{ __('To') }}</label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="{{ $filters['date_to'] }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="country_id">{{ __('Country') }}</label>
                        <select name="country_id" id="country_id" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($filterOptions['countries'] as $country)
                                <option value="{{ $country->id }}" @selected($filters['country_id'] == $country->id)>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="branch_id">{{ __('Branch') }}</label>
                        <select name="branch_id" id="branch_id" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($filterOptions['branches'] as $branch)
                                <option value="{{ $branch->id }}" @selected($filters['branch_id'] == $branch->id)>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="agent_id">{{ __('Agent') }}</label>
                        <select name="agent_id" id="agent_id" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($filterOptions['agents'] as $agent)
                                <option value="{{ $agent->id }}" @selected($filters['agent_id'] == $agent->id)>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="status">{{ __('Status') }}</label>
                        <input type="text" name="status" id="status" class="form-control"
                            value="{{ $filters['status'] }}" placeholder="{{ __('Status') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="search">{{ __('Search') }}</label>
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ $filters['search'] }}" placeholder="{{ __('Search…') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <div class="report-filter-actions w-100">
                            <x-button variant="success" type="submit" class="btn btn-success">
                                <i class="fas fa-search me-1"></i> {{ __('Filter') }}
                            </x-button>

                            <x-button variant="dark" href="{{ route('reports.show', $report) }}" class="btn btn-dark">
                                <i class="fas fa-redo me-1"></i> {{ __('Reset') }}
                            </x-button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        {{-- Summary Cards --}}
        @if (!empty($summary))
            <div class="row mb-4">
                @foreach ($summary as $label => $value)
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card report-summary-card">
                            <div class="card-body">
                                <div class="report-summary-label">
                                    <i class="fas fa-chart-line text-primary me-1"></i>
                                    {{ $label }}
                                </div>

                                <div class="report-summary-value">
                                    {{ is_array($value) ? collect($value)->map(fn($v, $k) => $k . ': ' . $v)->implode(', ') : $value }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Data Table --}}
        <div class="card">

            <div class="report-table-header">
                <h5 class="report-table-title">
                    <i class="fas fa-table text-primary me-2"></i>
                    {{ $title }}
                </h5>

                <span class="report-record-count">
                    <i class="fas fa-database me-1"></i>
                    {{ count($rows) }} {{ __('record(s)') }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">

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
                                <td colspan="{{ count($columns) }}" class="text-center report-empty">
                                    <i class="fas fa-folder-open fa-2x d-block mb-3"></i>
                                    {{ __('No records match the selected filters.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>
@endsection
