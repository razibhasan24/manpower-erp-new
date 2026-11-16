@extends('layouts.app')
@section('title', 'Daily Visitor Report')
@section('content')
<h1 class="mb-3">Daily Visitor Report — {{ $date }}</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="date" value="{{ $date }}" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Show</button></div>
</form>
<p class="text-muted">{{ $items->count() }} visitor(s) on this day.</p>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Pass</th><th>Name</th><th>Phone</th><th>Purpose</th><th>Person to Meet</th><th>Entry</th><th>Exit</th></tr></thead>
    <tbody>
    @forelse($items as $item)
        <tr><td>{{ $item->pass_number }}</td><td>{{ $item->name }}</td><td>{{ $item->phone }}</td><td>{{ $item->purpose }}</td><td>{{ $item->person_to_meet }}</td><td>{{ $item->entry_time }}</td><td>{{ $item->exit_time ?? 'Inside' }}</td></tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted py-4">No visitors on this day.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
@endsection
