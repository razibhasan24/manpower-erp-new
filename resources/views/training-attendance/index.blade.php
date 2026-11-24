@extends('layouts.app')
@section('title', __('Training Attendance'))
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3"><h1>{{ __('Training Attendance') }}</h1>@can('attendance.create')<a class="btn btn-success" href="{{ route('training-attendance.create') }}">{{ __('Add Attendance') }}</a>@endcan</div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Date') }}</th><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ __('Course') }}</th><th>{{ __('Status') }}</th><th>{{ __('Remarks') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
        @forelse ($records as $record)
            <tr><td>{{ $record->attendance_date?->format('Y-m-d') }}</td><td>{{ $record->training?->recruitment?->tracking_number ?? '—' }}</td><td>{{ $record->training?->recruitment?->passportHolder?->full_name ?? '—' }}</td><td>{{ $record->training?->course?->title ?? '—' }}</td><td><x-badge :status="$record->status" /></td><td>{{ $record->remarks ?: '—' }}</td><td class="text-nowrap">@can('attendance.update')<a class="btn btn-sm btn-warning" href="{{ route('training-attendance.edit', $record) }}">{{ __('Edit') }}</a>@endcan @can('attendance.delete')<form class="d-inline" method="POST" action="{{ route('training-attendance.destroy', $record) }}" onsubmit="return confirm('{{ __('Delete this attendance record?') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">{{ __('Delete') }}</button></form>@endcan</td></tr>
        @empty<tr><td colspan="7" class="text-center text-muted">{{ __('No attendance records found.') }}</td></tr>@endforelse
    </tbody></table></div>{{ $records->links() }}
@endsection
