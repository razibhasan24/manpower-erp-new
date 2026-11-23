@extends('layouts.app')

@section('title', 'Passport Holders')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Passport Holders</h1>

        @can('passport-holder.create')
            <x-button href="{{ route('passport-holders.create') }}" variant="success" size="sm">
                <i class="fas fa-plus me-1"></i> New Passport Holder
            </x-button>
        @endcan
    </div>

    <x-filter-bar>
        <form method="GET" action="{{ route('passport-holders.index') }}" class="row g-2 align-items-end">

            <div class="col-md-4">
                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search...">
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
                        <option value="{{ $branch->id }}" @selected((string) request('branch_id') === (string) $branch->id)>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="country_id" class="form-select">
                    <option value="">All countries</option>
                    @foreach ($filterCountries as $country)
                        <option value="{{ $country->id }}" @selected((string) request('country_id') === (string) $country->id)>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="agent_id" class="form-select">
                    <option value="">All agents</option>
                    @foreach ($filterAgents as $agent)
                        <option value="{{ $agent->id }}" @selected((string) request('agent_id') === (string) $agent->id)>
                            {{ $agent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>

            <div class="col-md-2">
                <a href="{{ route('passport-holders.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
            </div>

        </form>
    </x-filter-bar>

    <p class="text-muted">{{ $items->total() }} passport holders found.</p>

    <x-table>
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Passport Number</th>
                    <th>Phone</th>
                    <th>Agent</th>
                    <th>Country</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            <a href="{{ route('passport-holders.show', $item) }}">
                                {{ $item->full_name }}
                            </a>
                        </td>

                        <td>{{ $item->passport_number }}</td>
                        <td>{{ $item->phone ?? '—' }}</td>
                        <td>{{ $item->agent?->name ?? '—' }}</td>
                        <td>{{ $item->country?->name ?? '—' }}</td>

                        <td>
                            <x-badge>{{ $item->status }}</x-badge>
                        </td>

                        <td class="text-nowrap">

                            @can('passport-holder.view')
                                <x-button href="{{ route('passport-holders.show', $item) }}" variant="success" size="sm">
                                    <i class="fas fa-eye me-1"></i> View
                                </x-button>
                            @endcan

                            @can('passport-holder.edit')
                                <x-button href="{{ route('passport-holders.edit', $item) }}" variant="warning" size="sm">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </x-button>
                            @endcan

                            @can('passport-holder.delete')
                                <button type="button" class="btn btn-sm btn-danger archive-passport-holder-btn"
                                    data-action="{{ route('passport-holders.destroy', $item) }}"
                                    data-name="{{ $item->full_name }}">
                                    <i class="fas fa-archive me-1"></i> Archive
                                </button>
                            @endcan

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No passport holders found. Adjust the filters or create a new one.
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

            document.querySelectorAll('.archive-passport-holder-btn').forEach(function(button) {

                button.addEventListener('click', function() {

                    const action = this.dataset.action;
                    const holderName = this.dataset.name;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to archive "' + holderName + '"?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Archive',
                        cancelButtonText: 'Cancel',
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
                            csrfInput.value = '{{ csrf_token() }}';

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
