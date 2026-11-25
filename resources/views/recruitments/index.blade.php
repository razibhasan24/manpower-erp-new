@extends('layouts.app')

@section('title', 'Recruitments')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">
            {{ $serviceType === 'student' ? 'Student Registrations' : ($serviceType === 'hajj_umrah' ? 'Hajj & Umrah Registrations' : 'Recruitments') }}
        </h1>
        @can('recruitment.create')
            <x-button variant="success" size="sm" href="{{ route('recruitments.create') }}">New Recruitment</x-button>
        @endcan
    </div>

    <x-filter-bar>
        <form method="GET" action="{{ route('recruitments.index') }}" class="row g-2 align-items-end">
            @if ($serviceType)
                <input type="hidden" name="service_type" value="{{ $serviceType }}">
            @endif
            <div class="col-md-4">
                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search...">
            </div>
            <div class="col-md-3">
                <select name="current_status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('current_status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('current_status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('current_status') === 'rejected')>Rejected</option>
                    <option value="returned" @selected(request('current_status') === 'returned')>Returned</option>
                    <option value="cancelled" @selected(request('current_status') === 'cancelled')>Cancelled</option>
                    <option value="completed" @selected(request('current_status') === 'completed')>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="current_stage" class="form-select">
                    <option value="">All stages</option>
                    @foreach (\App\Services\WorkflowService::STAGES as $stage)
                        <option value="{{ $stage }}" @selected(request('current_stage') === $stage)>
                            {{ ucwords(str_replace('_', ' ', $stage)) }}</option>
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
                <x-button type="submit" variant="primary" class="btn btn-primary w-100">Filter</x-button>
            </div>
            <div class="col-md-2">
                <x-button href="{{ route('recruitments.index') }}" variant="dark"
                    class="btn btn-dark w-100">Reset</x-button>
            </div>
        </form>
    </x-filter-bar>

    <p class="text-muted">{{ $items->total() }} recruitments found.</p>

    <x-table>
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Tracking Number</th>
                    <th>Type</th>
                    <th>Package / Due</th>
                    <th>Passport Holder</th>
                    <th>Agent</th>
                    <th>Country</th>
                    <th>Stage</th>
                    <th>Current Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td><a href="{{ route('recruitments.show', $item) }}">{{ $item->tracking_number }}</a></td>
                        <td>{{ match ($item->service_type) {'student' => 'Student','hajj_umrah' => 'Hajj / Umrah',default => 'Overseas'} }}
                        </td>
                        <td>{{ $item->servicePackage?->name ?? '—' }} @if ($item->quoted_amount !== null)
                                <div class="small text-muted">Due: {{ $item->quoted_currency }}
                                    {{ number_format((float) $item->remaining_due, 2) }}</div>
                            @endif
                        </td>
                        <td>{{ $item->passportHolder?->full_name ?? '—' }}</td>
                        <td>{{ $item->agent?->name ?? '—' }}</td>
                        <td>{{ $item->country?->name ?? '—' }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $item->current_stage)) }}</td>
                        <td><x-badge>{{ $item->current_status }}</x-badge></td>
                        <td class="text-nowrap">
                            @can('recruitment.view')
                                
                            <div class="btn-group btn-group-sm me-1" role="group" aria-label="Download candidate formats">

                                <a href="{{ route('recruitments.download-format', [$item, 'cv']) }}"
                                    class="btn btn-primary fw-bold shadow-sm"
                                    title="Download CV">
                                    <i class="fas fa-file-pdf me-1"></i> CV
                                </a>

                                <a href="{{ route('recruitments.download-format', [$item, 'entry-form']) }}"
                                    class="btn btn-success fw-bold shadow-sm"
                                    title="Download barcode entry form">
                                    <i class="fas fa-barcode me-1"></i> Entry
                                </a>

                                <a href="{{ route('recruitments.download-format', [$item, 'id-card']) }}"
                                    class="btn btn-warning fw-bold shadow-sm"
                                    title="Download ID card">
                                    <i class="fas fa-id-card me-1"></i> ID Card
                                </a>

                            </div>

                            @endcan
                            @if ($item->selection)
                                @can('selection.view')
                                    <x-button variant="success" href="{{ route('selections.show', $item->selection) }}"
                                        class="btn-sm btn-success" title="View Selection &amp; Grading">Selection &amp;
                                        Grading</x-button>
                                @endcan
                            @elseif ($item->current_status !== 'cancelled')
                                @can('selection.create')
                                    <x-button variant="success" href="{{ route('selections.create', ['recruitment_id' => $item->id]) }}"
                                        class="btn-sm btn-success" title="Proceed to Selection &amp; Grading">Selection &amp;
                                        Grading</x-button>
                                @endcan
                            @endif

                            @can('recruitment.view')
                                <x-button variant="primary" href="{{ route('recruitments.show', $item) }}"
                                    class="btn-sm btn-primary">View</x-button>
                            @endcan
                            @can('recruitment.edit')
                                <x-button variant="warning" href="{{ route('recruitments.edit', $item) }}"
                                    class="btn-sm btn-warning">Edit</x-button>
                            @endcan
                            @can('recruitment.delete')
                                <x-button variant='danger' type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-action="{{ route('recruitments.destroy', $item) }}">Cancel registration</x-button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No registrations found. Adjust the filters or
                            create a new one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table>

    <div class="mt-3">{{ $items->links() }}</div>

    <x-modal id="deleteModal" title="Confirm">
        <p id="deleteModalText">This record will be removed. Recruitment and passport holder records are archived or
            cancelled, never hard-deleted.</p>
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
        document.getElementById('deleteModal').addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            document.getElementById('deleteModalForm').action = button.getAttribute('data-action');
        });
    </script>
@endpush
