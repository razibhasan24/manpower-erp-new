@extends('layouts.app')
@section('title', __('Country User Permissions'))
@section('content')
<h1 class="mb-3">{{ __('Country User Permissions') }}</h1>
<div class="alert alert-info">{{ __('This page shows the country access currently assigned to each user. Assignments are managed through user administration.') }}</div>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search user name or email') }}"></div>
    <div class="col-md-4"><select name="country_id" class="form-select"><option value="">{{ __('All countries') }}</option>@foreach($countries as $country)<option value="{{ $country->id }}" @selected((string) request('country_id') === (string) $country->id)>{{ $country->name }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-primary">{{ __('Filter') }}</button></div>
    <div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('country-user-permissions.index') }}">{{ __('Clear') }}</a></div>
</form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('User') }}</th><th>{{ __('Email') }}</th><th>{{ __('Branch') }}</th><th>{{ __('Roles') }}</th><th>{{ __('Assigned countries') }}</th><th>{{ __('Status') }}</th></tr></thead><tbody>
@forelse($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->branch?->name ?? '—' }}</td><td>{{ $user->roles->pluck('name')->map(fn ($role) => __(\Illuminate\Support\Str::headline($role)))->join(', ') ?: '—' }}</td><td>{{ $user->countries->pluck('name')->join(', ') ?: __('No countries assigned') }}</td><td><x-badge :status="$user->status ?? 'active'" /></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">{{ __('No users found.') }}</td></tr>@endforelse
</tbody></table></div>{{ $users->links() }}
@endsection
