@extends('layouts.app')

@section('title', 'Agents')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Agents</h1>
    @can('agent.create')
        <x-button href="{{ route('agents.create') }}">New Agent</x-button>
    @endcan
</div>

<x-filter-bar>
    <form method="GET" action="{{ route('agents.index') }}" class="row g-2 align-items-end">
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
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
    </form>
</x-filter-bar>

<p class="text-muted">{{ $items->total() }} agents found.</p>

<x-table>
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr><th>Name</th><th>Phone</th><th>Branch</th><th>Countries</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->name }}</td><td>{{ $item->phone ?? '—' }}</td><td>{{ $item->branch?->name ?? '—' }}</td><td>@foreach ($item->countries as $country)<x-badge>{{ $country->code }}</x-badge> @endforeach</td><td><x-badge>{{ $item->status }}</x-badge></td>
                    <td class="text-nowrap">
                        @can('agent.view')
                            <x-button href="{{ route('agents.show', $item) }}" class="btn-sm btn-outline-primary">View</x-button>
                        @endcan
                        @can('agent.edit')
                            <x-button href="{{ route('agents.edit', $item) }}" class="btn-sm btn-outline-secondary">Edit</x-button>
                        @endcan
                        @can('agent.delete')
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-action="{{ route('agents.destroy', $item) }}">Archive</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No agents found. Adjust the filters or create a new one.</td></tr>
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
