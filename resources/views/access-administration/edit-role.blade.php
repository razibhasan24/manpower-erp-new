@extends('layouts.app')
@section('title', __('Edit role permissions'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">{{ __('Edit permissions: :role', ['role' => $role->name]) }}</h1>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">{{ __('Back to roles') }}</a>
</div>
<div class="alert alert-info">{{ __('Only permissions available to your own account can be assigned. Super Admin role permissions are protected.') }}</div>
<form method="POST" action="{{ route('roles.update', $role) }}">
    @csrf
    @method('PUT')
    <div class="row g-3 mb-3">
        @foreach($permissions->groupBy(fn ($permission) => explode('.', $permission->name, 2)[0]) as $module => $modulePermissions)
            <div class="col-lg-4 col-md-6">
                <div class="border rounded p-3 h-100">
                    <strong>{{ \Illuminate\Support\Str::headline($module) }}</strong>
                    <div class="mt-2">
                        @foreach($modulePermissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission_ids[]"
                                    id="permission_{{ $permission->id }}" value="{{ $permission->id }}"
                                    @checked(in_array((string) $permission->id, array_map('strval', old('permission_ids', $role->permissions->pluck('id')->all())), true))>
                                <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @error('permission_ids')<div class="text-danger mb-2">{{ $message }}</div>@enderror
    <button class="btn btn-primary" type="submit">{{ __('Save permissions') }}</button>
    <a class="btn btn-outline-secondary" href="{{ route('roles.index') }}">{{ __('Cancel') }}</a>
</form>
@endsection
