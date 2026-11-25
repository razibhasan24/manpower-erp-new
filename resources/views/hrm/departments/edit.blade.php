@extends('layouts.app')
@section('title', 'Edit Department')
@section('content')
    <h1 class="mb-3">
        Edit Department</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('hrm.departments.update', $item) }}">
                @csrf
                @method('PUT')
                <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name"
                        value="{{ old('name', $item->name ?? '') }}" class="form-control">
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select">
                        <option value="active" {{ old('status', $item->status ?? '') == 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive" {{ old('status', $item->status ?? '') == 'inactive' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                    @error('status')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <x-button variant="primary" type="submit" class="btn btn-primary">Save</x-button>
                <x-button variant="danger" href="{{ route('hrm.departments.index') }}"
                    class="btn btn-danger">Cancel</x-button>
            </form>
        </div>
    </div>
@endsection
