@extends('layouts.app')
@section('title', __('Permission Management'))
@section('content')
<h1 class="mb-3">{{ __('Permissions') }}</h1>
<div class="alert alert-info">{{ __('This is a read-only catalogue of permissions and their assigned roles. Permission changes are not available in this screen.') }}</div>
<form method="GET" class="row g-2 mb-3"><div class="col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search permission name') }}"></div><div class="col-auto"><button class="btn btn-primary">{{ __('Search') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('permissions.index') }}">{{ __('Clear') }}</a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Permission') }}</th><th>{{ __('Module') }}</th><th>{{ __('Action') }}</th><th>{{ __('Guard') }}</th><th>{{ __('Assigned roles') }}</th></tr></thead><tbody>
@forelse($permissions as $permission)@php($parts = array_pad(explode('.', $permission->name, 2), 2, '—'))<tr><td>{{ $permission->name }}</td><td>{{ \Illuminate\Support\Str::headline($parts[0]) }}</td><td>{{ \Illuminate\Support\Str::headline($parts[1]) }}</td><td>{{ $permission->guard_name }}</td><td>{{ $permission->roles->pluck('name')->join(', ') ?: '—' }}</td></tr>@empty<tr><td colspan="5" class="text-center text-muted">{{ __('No permissions found.') }}</td></tr>@endforelse
</tbody></table></div>{{ $permissions->links() }}
@endsection
