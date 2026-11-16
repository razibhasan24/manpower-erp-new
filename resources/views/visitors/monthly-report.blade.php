@extends('layouts.app')
@section('title', 'Monthly Visitor Report')
@section('content')
<h1 class="mb-3">Monthly Visitor Report — {{ $month }}</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="month" name="month" value="{{ $month }}" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Show</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Day</th><th class="text-end">Visitors</th></tr></thead>
    <tbody>
    @forelse($rows as $row)
        <tr><td>{{ $row->day }}</td><td class="text-end">{{ $row->total }}</td></tr>
    @empty
        <tr><td colspan="2" class="text-center text-muted py-4">No visitors this month.</td></tr>
    @endforelse
    </tbody>
    <tfoot><tr><th>Total</th><th class="text-end">{{ $rows->sum('total') }}</th></tr></tfoot>
</table>
</div></div>
@endsection
