@extends('layouts.app')
@section('title', 'University Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1>University Management</h1>@can('student-package.create')<a class="btn btn-primary" href="{{ route('universities.create') }}">Add University</a>@endcan</div>
<form class="row g-2 mb-3"><div class="col-md-4"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search university"></div><div class="col-auto"><button class="btn btn-outline-primary">Search</button></div></form>
<div class="card"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Code</th><th>University</th><th>Country</th><th>City</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse($universities as $university)<tr><td>{{ $university->code }}</td><td>{{ $university->name }}</td><td>{{ $university->country ?? '—' }}</td><td>{{ $university->city ?? '—' }}</td><td>{{ $university->is_active ? 'Active' : 'Inactive' }}</td><td class="text-nowrap">@can('student-package.edit')<a class="btn btn-sm btn-outline-primary" href="{{ route('universities.edit', $university) }}">Edit</a>@endcan @can('student-package.delete')<form class="d-inline" method="POST" action="{{ route('universities.destroy', $university) }}" onsubmit="return confirm('Archive this university?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Archive</button></form>@endcan</td></tr>@empty<tr><td colspan="6" class="text-center py-4 text-muted">No universities yet.</td></tr>@endforelse
</tbody></table></div><div class="card-body">{{ $universities->links() }}</div></div>
@endsection
