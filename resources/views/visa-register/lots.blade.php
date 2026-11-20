@extends('layouts.app')
@section('title', __('Visa Lots'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0">{{ __('Visa Lots') }}</h1>@can('visa.create')<a class="btn btn-primary" href="{{ route('visa-register.lots.create') }}">{{ __('Create Lot') }}</a>@endcan</div>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search lot number') }}"></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">{{ __('All statuses') }}</option>@foreach(['open','submitted','closed','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ __(ucfirst($status)) }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-primary">{{ __('Filter') }}</button></div>
    <div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('visa-register.lots') }}">{{ __('Clear') }}</a></div>
</form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Lot number') }}</th><th>{{ __('Country') }}</th><th>{{ __('Status') }}</th><th>{{ __('Visas') }}</th><th>{{ __('Remarks') }}</th></tr></thead><tbody>
@forelse($records as $record)<tr><td>{{ $record->lot_number }}</td><td>{{ $record->country?->name ?? '—' }}</td><td><x-badge :status="$record->status" /></td><td>{{ $record->visas_count }}</td><td>{{ $record->remarks ?: '—' }}</td></tr>@empty<tr><td colspan="5" class="text-center text-muted">{{ __('No records found.') }}</td></tr>@endforelse
</tbody></table></div>{{ $records->links() }}
@endsection
