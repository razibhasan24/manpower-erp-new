@can('post-arrival.create')<div class="d-flex justify-content-end mb-3"><a class="btn btn-primary" href="{{ $createUrl }}">{{ __('Create') }}</a></div>@endcan
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search tracking or candidate') }}"></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">{{ __('All statuses') }}</option>@foreach(['pending','arrived','settled','issue_reported','completed'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ __(ucwords(str_replace('_', ' ', $status))) }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-primary">{{ __('Filter') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ $clearUrl }}">{{ __('Clear') }}</a></div>
</form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Arrival date') }}</th><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ $valueLabel }}</th><th>{{ __('Status') }}</th><th>{{ __('Action') }}</th></tr></thead><tbody>
@forelse($records as $record)<tr><td>{{ $record->arrival_date?->format('Y-m-d') ?? '—' }}</td><td>{{ $record->recruitment?->tracking_number ?? '—' }}</td><td>{{ $record->recruitment?->passportHolder?->full_name ?? '—' }}</td><td>{{ $value($record) ?: '—' }}</td><td><x-badge :status="$record->status" /></td><td><x-button href="{{ route('post-arrivals.show', $record) }}" variant="secondary" size="sm">{{ __('View') }}</x-button></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">{{ $emptyMessage }}</td></tr>@endforelse
</tbody></table></div>{{ $records->links() }}
