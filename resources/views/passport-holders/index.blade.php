@extends('layouts.app')

@section('title', 'Passport Holders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Passport Holders</h1>
    @can('passport-holder.create')
        <x-button href="{{ route('passport-holders.create') }}">New Passport Holder</x-button>
    @endcan
</div>

<x-filter-bar>
    <form method="GET" action="{{ route('passport-holders.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search...">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="branch_id" class="form-select">
                        <option value="">All branches</option>
                        @foreach ($filterBranches as $branch)
                            <option value="{{ $branch->id }}" @selected((string) request('branch_id') === (string) $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="country_id" class="form-select">
                        <option value="">All countries</option>
                        @foreach ($filterCountries as $country)
                            <option value="{{ $country->id }}" @selected((string) request('country_id') === (string) $country->id)>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="agent_id" class="form-select">
                        <option value="">All agents</option>
                        @foreach ($filterAgents as $agent)
                            <option value="{{ $agent->id }}" @selected((string) request('agent_id') === (string) $agent->id)>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('passport-holders.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
    </form>
</x-filter-bar>

<p class="text-muted">{{ $items->total() }} passport holders found.</p>

<x-table>
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr><th>Full Name</th><th>Passport Number</th><th>Phone</th><th>Agent</th><th>Country</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td><a href="{{ route('passport-holders.show', $item) }}">{{ $item->full_name }}</a></td><td>{{ $item->passport_number }}</td><td>{{ $item->phone ?? '—' }}</td><td>{{ $item->agent?->name ?? '—' }}</td><td>{{ $item->country?->name ?? '—' }}</td><td><x-badge>{{ $item->status }}</x-badge></td>
                    <td class="text-nowrap">
                        @can('passport-holder.view')
                            <x-button href="{{ route('passport-holders.show', $item) }}" class="btn-sm btn-outline-primary">View</x-button>
                        @endcan
                        @can('passport-holder.edit')
                            <x-button href="{{ route('passport-holders.edit', $item) }}" class="btn-sm btn-outline-secondary">Edit</x-button>
                        @endcan
                        @can('passport-holder.delete')
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-action="{{ route('passport-holders.destroy', $item) }}">Archive</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No passport holders found. Adjust the filters or create a new one.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-table>

<div class="mt-3">{{ $items->links() }}</div>

<x-modal id="deleteModal" title="Confirm">
    <p id="deleteModalText">This record will be removed. Recruitment and passport holder records are archived or cancelled, never hard-deleted.</p>
    <form id="deleteModalForm" method="POST" action="">
        @csrf
        @method('DELETE')
        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep it</button>
            <button type="submit" class="btn btn-danger">Confirm</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    document.getElementById('deleteModalForm').action = button.getAttribute('data-action');
});
</script>
@endpush
