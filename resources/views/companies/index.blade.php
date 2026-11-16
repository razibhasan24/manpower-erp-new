@extends('layouts.app')

@section('title', 'Companies')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Companies</h1>
    @can('core.manage')
        <x-button href="{{ route('companies.create') }}">New Company</x-button>
    @endcan
</div>

<x-filter-bar>
    <form method="GET" action="{{ route('companies.index') }}" class="row g-2 align-items-end">
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
                    <select name="country_id" class="form-select">
                        <option value="">All countries</option>
                        @foreach ($filterCountries as $country)
                            <option value="{{ $country->id }}" @selected((string) request('country_id') === (string) $country->id)>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
    </form>
</x-filter-bar>

<p class="text-muted">{{ $items->total() }} companies found.</p>

<x-table>
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr><th>Name</th><th>Country</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->name }}</td><td>{{ $item->country?->name ?? '—' }}</td><td><x-badge>{{ $item->status }}</x-badge></td>
                    <td class="text-nowrap">
                        @can('core.view')
                            <x-button href="{{ route('companies.show', $item) }}" class="btn-sm btn-outline-primary">View</x-button>
                        @endcan
                        @can('core.manage')
                            <x-button href="{{ route('companies.edit', $item) }}" class="btn-sm btn-outline-secondary">Edit</x-button>
                        @endcan
                        @can('core.manage')
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-action="{{ route('companies.destroy', $item) }}">Delete</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">No companies found. Adjust the filters or create a new one.</td></tr>
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
