@extends('layouts.app')

@section('title', 'Branch Details')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-building text-primary"></i> Branch Details
            </h1>
            <p class="text-muted mb-0">View complete branch information</p>
        </div>

        <div class="d-flex gap-2">
            @can('branch.edit')
                <x-button href="{{ route('branches.edit', $branch) }}" variant="warning" size="sm">
                    <i class="fas fa-edit"></i> Edit
                </x-button>
            @endcan

            <x-button href="{{ route('branches.index') }}" variant="secondary" size="sm">
                <i class="fas fa-arrow-left"></i> Back to list
            </x-button>
        </div>
    </div>

    {{-- Branch Information --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-info-circle text-primary"></i>
                Branch Information
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 220px;">Branch Name</th>
                            <td>{{ $branch->name }}</td>
                        </tr>
                        <tr>
                            <th>Branch Code</th>
                            <td>{{ $branch->code }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $branch->address ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $branch->phone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $branch->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Branch Manager</th>
                            <td>{{ $branch->manager?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($branch->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <x-stat-card title="Agents" :value="$branch->agents()->count()" />
        </div>

        <div class="col-md-4">
            <x-stat-card title="Registrations" :value="$branch->recruitments()->count()" />
        </div>

        <div class="col-md-4">
            <x-stat-card title="Users" :value="$branch->users()->count()" />
        </div>

    </div>

    {{-- Agents List --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-users text-primary"></i>
                Agents in this Branch
            </h5>
        </div>

        <div class="card-body">

            @forelse ($branch->agents as $agent)
                <div class="d-flex justify-content-between align-items-center
                        border-bottom py-3">

                    <div>
                        <a href="{{ route('agents.show', $agent) }}" class="fw-semibold text-decoration-none">
                            {{ $agent->name }}
                        </a>

                        <div class="text-muted small">
                            <i class="fas fa-phone"></i>
                            {{ $agent->phone ?? 'No phone number' }}
                        </div>
                    </div>

                    <span class="badge bg-info text-dark">
                        Agent
                    </span>

                </div>

            @empty

                <div class="text-center py-4">
                    <i class="fas fa-users fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">
                        No agents in this branch yet.
                    </p>
                </div>
            @endforelse

        </div>
    </div>

@endsection
