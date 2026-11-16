@extends('layouts.app')

@section('title', 'Country Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Country Details</h1>
    <div class="d-flex gap-2">
        @can('core.manage')
            <x-button href="{{ route('countries.edit', $country) }}">Edit</x-button>
        @endcan
        <x-button href="{{ route('countries.index') }}" class="btn-outline-secondary">Back to list</x-button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tbody>
        <tr><th scope="row" style="width: 220px;">Name</th><td>{{ $country->name }}</td></tr>
        <tr><th scope="row" style="width: 220px;">ISO Code</th><td>{{ $country->code }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Status</th><td><x-badge>{{ $country->status }}</x-badge></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Companies in this country</div>
    <div class="card-body">
        @forelse ($country->companies as $company)
            <div><a href="{{ route('companies.show', $company) }}">{{ $company->name }}</a></div>
        @empty
            <p class="text-muted mb-0">No companies in this country yet.</p>
        @endforelse
    </div>
</div>
@endsection
