@extends('layouts.app')

@section('title', 'Agents')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Agents</h1>

        @can('agent.create')
            <x-button href="{{ route('agents.create') }}" variant="primary">
                <i class="fas fa-plus me-1"></i> New Agent
            </x-button>
        @endcan
    </div>

    <x-filter-bar>
        <form method="GET" action="{{ route('agents.index') }}" class="row g-2 align-items-end">

            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search...">
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Branch</label>
                <select name="branch_id" class="form-select">
                    <option value="">All branches</option>
                    @foreach ($filterBranches as $branch)
                        <option value="{{ $branch->id }}" @selected((string) request('branch_id') === (string) $branch->id)>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>

            <div class="col-md-1">
                <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
            </div>

        </form>
    </x-filter-bar>

    <p class="text-muted my-3">
        {{ $items->total() }} agents found.
    </p>

    <x-table>
        <table class="table table-striped table-hover mb-0">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Branch</th>
                    <th>Countries</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>

                        <td>{{ $item->phone ?? '—' }}</td>

                        <td>{{ $item->branch?->name ?? '—' }}</td>

                        <td>
                            @foreach ($item->countries as $country)
                                <x-badge>{{ $country->code }}</x-badge>
                            @endforeach
                        </td>

                        <td>
                            <x-badge>{{ $item->status }}</x-badge>
                        </td>

                        <td class="text-nowrap">

                            @can('agent.view')
                                <x-button href="{{ route('agents.show', $item) }}" variant="primary" size="sm">
                                    <i class="fas fa-eye me-1"></i> View
                                </x-button>
                            @endcan

                            @can('agent.edit')
                                <x-button href="{{ route('agents.edit', $item) }}" variant="warning" size="sm">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </x-button>
                            @endcan

                            @can('agent.delete')
                                <button type="button" class="btn btn-sm btn-danger archive-agent-btn"
                                    data-action="{{ route('agents.destroy', $item) }}" data-name="{{ $item->name }}">
                                    <i class="fas fa-archive me-1"></i> Archive
                                </button>
                            @endcan

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No agents found. Adjust the filters or create a new one.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </x-table>

    <div class="mt-3">
        {{ $items->links() }}
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.archive-agent-btn').forEach(function(button) {

                button.addEventListener('click', function() {

                    const action = this.dataset.action;
                    const agentName = this.dataset.name;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to archive agent "' + agentName + '"?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-archive"></i> Yes, Archive',
                        cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                        reverseButtons: true,
                        focusCancel: true
                    }).then(function(result) {

                        if (result.isConfirmed) {

                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = action;

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value =
                                document.querySelector('meta[name="csrf-token"]')
                                ?.content ||
                                '{{ csrf_token() }}';

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';

                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);

                            document.body.appendChild(form);
                            form.submit();
                        }

                    });

                });

            });

        });
    </script>
@endpush
