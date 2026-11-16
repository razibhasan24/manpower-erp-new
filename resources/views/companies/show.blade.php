@extends('layouts.app')

@section('title', 'Company Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Company Details</h1>
    <div class="d-flex gap-2">
        @can('core.manage')
            <x-button href="{{ route('companies.edit', $company) }}">Edit</x-button>
        @endcan
        <x-button href="{{ route('companies.index') }}" class="btn-outline-secondary">Back to list</x-button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tbody>
        <tr><th scope="row" style="width: 220px;">Name</th><td>{{ $company->name }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Country</th><td>{{ $company->country?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Status</th><td><x-badge>{{ $company->status }}</x-badge></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Positions at this company</div>
    <div class="card-body">
        @forelse ($company->positions as $position)
            <div><a href="{{ route('positions.show', $position) }}">{{ $position->title }}</a> - {{ $position->country?->name }}</div>
        @empty
            <p class="text-muted mb-0">No positions at this company yet.</p>
        @endforelse
    </div>
</div>
@endsection
