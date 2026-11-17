@extends('layouts.app')
@section('title', __('Medical Centers'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Medical Centers') }}</h1>
    @can('medical-centre.create')
        <x-button href="{{ route('medical-centres.create') }}">{{ __('Add Medical Center') }}</x-button>
    @endcan
</div>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5"><input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search by name') }}"></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">{{ __('All statuses') }}</option><option value="active" @selected(request('status') === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(request('status') === 'inactive')>{{ __('Inactive') }}</option></select></div>
    <div class="col-auto"><button class="btn btn-outline-primary">{{ __('Filter') }}</button></div>
</form>
<div class="table-responsive"><table class="table table-striped align-middle">
    <thead><tr><th>{{ __('Name') }}</th><th>{{ __('Phone') }}</th><th>{{ __('Email') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
    <tbody>
    @forelse($centres as $centre)
        <tr><td>{{ $centre->name }}</td><td>{{ $centre->phone ?: '—' }}</td><td>{{ $centre->email ?: '—' }}</td><td><x-badge :status="$centre->status" /></td><td class="text-nowrap">
            @can('medical-centre.edit')<x-button href="{{ route('medical-centres.edit', $centre) }}" variant="secondary" size="sm">{{ __('Edit') }}</x-button>@endcan
            @can('medical-centre.delete')<form method="POST" action="{{ route('medical-centres.destroy', $centre) }}" class="d-inline" onsubmit="return confirm('{{ __('Delete this medical centre? Existing medical records will be kept without a centre.') }}')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">{{ __('Delete') }}</button></form>@endcan
        </td></tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted">{{ __('No medical centres found.') }}</td></tr>
    @endforelse
    </tbody>
</table></div>
{{ $centres->links() }}
@endsection
