@extends('layouts.app')

@section('title', 'Recruitments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Recruitments</h1>
    @can('recruitment.create')
        <x-button href="{{ route('recruitments.create') }}">New Recruitment</x-button>
    @endcan
</div>

<x-filter-bar>
    <form method="GET" action="{{ route('recruitments.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search...">
                </div>
                <div class="col-md-3">
                    <select name="current_status" class="form-select">
                        <option value="">All statuses</option>
                        <option value="pending" @selected(request('current_status') === 'pending')>Pending</option><option value="approved" @selected(request('current_status') === 'approved')>Approved</option><option value="rejected" @selected(request('current_status') === 'rejected')>Rejected</option><option value="returned" @selected(request('current_status') === 'returned')>Returned</option><option value="cancelled" @selected(request('current_status') === 'cancelled')>Cancelled</option><option value="completed" @selected(request('current_status') === 'completed')>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="current_stage" class="form-select">
                        <option value="">All stages</option>
                        @foreach(\App\Services\WorkflowService::STAGES as $stage)
                            <option value="{{ $stage }}" @selected(request('current_stage') === $stage)>{{ ucwords(str_replace('_', ' ', $stage)) }}</option>
                        @endforeach
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
                    <a href="{{ route('recruitments.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
    </form>
</x-filter-bar>

<p class="text-muted">{{ $items->total() }} recruitments found.</p>

<x-table>
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr><th>Tracking Number</th><th>Passport Holder</th><th>Agent</th><th>Country</th><th>Stage</th><th>Current Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td><a href="{{ route('recruitments.show', $item) }}">{{ $item->tracking_number }}</a></td><td>{{ $item->passportHolder?->full_name ?? '—' }}</td><td>{{ $item->agent?->name ?? '—' }}</td><td>{{ $item->country?->name ?? '—' }}</td><td>{{ ucwords(str_replace('_', ' ', $item->current_stage)) }}</td><td><x-badge>{{ $item->current_status }}</x-badge></td>
                    <td class="text-nowrap">
                        @if ($item->selection)
                            @can('selection.view')
                                <x-button href="{{ route('selections.show', $item->selection) }}" class="btn-sm btn-outline-success" title="View Selection &amp; Grading">Selection &amp; Grading</x-button>
                            @endcan
                        @elseif ($item->current_status !== 'cancelled')
                            @can('selection.create')
                                <x-button href="{{ route('selections.create', ['recruitment_id' => $item->id]) }}" class="btn-sm btn-success" title="Proceed to Selection &amp; Grading">Selection &amp; Grading</x-button>
                            @endcan
                        @endif
                        
                        @can('recruitment.view')
                            <x-button href="{{ route('recruitments.show', $item) }}" class="btn-sm btn-outline-primary">View</x-button>
                        @endcan
                        @can('recruitment.edit')
                            <x-button href="{{ route('recruitments.edit', $item) }}" class="btn-sm btn-outline-secondary">Edit</x-button>
                        @endcan
                        @can('recruitment.delete')
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-action="{{ route('recruitments.destroy', $item) }}">Cancel registration</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No recruitments found. Adjust the filters or create a new one.</td></tr>
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
