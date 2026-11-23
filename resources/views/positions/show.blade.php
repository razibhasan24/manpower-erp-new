@extends('layouts.app')

@section('title', 'Position Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Position Details</h1>
        <div class="d-flex gap-2">
            @can('core.manage')
                <x-button variant="warning" size="sm" href="{{ route('positions.edit', $position) }}">Edit</x-button>
            @endcan
            <x-button variant="dark" size="sm" href="{{ route('positions.index') }}">Back to list</x-button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tbody>
                    <tr>
                        <th scope="row" style="width: 220px;">Job Title</th>
                        <td>{{ $position->title }}</td>
                    </tr>
                    <tr>
                        <th scope="row" style="width: 220px;">Company</th>
                        <td>{{ $position->company?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th scope="row" style="width: 220px;">Country</th>
                        <td>{{ $position->country?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th scope="row" style="width: 220px;">Salary Range</th>
                        <td>{{ $position->currency ?? '' }} {{ number_format((float) ($position->salary_min ?? 0), 2) }} –
                            {{ number_format((float) ($position->salary_max ?? 0), 2) }}</td>
                    </tr>
                    <tr>
                        <th scope="row" style="width: 220px;">Status</th>
                        <td><x-badge>{{ $position->status }}</x-badge></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
