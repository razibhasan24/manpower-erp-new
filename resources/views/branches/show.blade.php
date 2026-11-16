@extends('layouts.app')

@section('title', 'Branch Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Branch Details</h1>
    <div class="d-flex gap-2">
        @can('branch.edit')
            <x-button href="{{ route('branches.edit', $branch) }}">Edit</x-button>
        @endcan
        <x-button href="{{ route('branches.index') }}" class="btn-outline-secondary">Back to list</x-button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tbody>
        <tr><th scope="row" style="width: 220px;">Name</th><td>{{ $branch->name }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Code</th><td>{{ $branch->code }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Address</th><td>{{ $branch->address ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Phone</th><td>{{ $branch->phone ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Email</th><td>{{ $branch->email ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Branch Manager</th><td>{{ $branch->manager?->name ?? "—" }}</td></tr>
        <tr><th scope="row" style="width: 220px;">Status</th><td><x-badge>{{ $branch->status }}</x-badge></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4"><x-stat-card title="Agents" :value="$branch->agents()->count()" /></div>
    <div class="col-md-4"><x-stat-card title="Registrations" :value="$branch->recruitments()->count()" /></div>
    <div class="col-md-4"><x-stat-card title="Users" :value="$branch->users()->count()" /></div>
</div>
<div class="card mt-4">
    <div class="card-header">Agents in this branch</div>
    <div class="card-body">
        @forelse ($branch->agents as $agent)
            <div><a href="{{ route('agents.show', $agent) }}">{{ $agent->name }}</a> - {{ $agent->phone }}</div>
        @empty
            <p class="text-muted mb-0">No agents in this branch yet.</p>
        @endforelse
    </div>
</div>
@endsection
