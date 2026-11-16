@extends('layouts.app')
@section('title', 'Attendance Summary')
@section('content')
<h1 class="mb-3">Attendance Summary — {{ $employee->user->name ?? $employee->employee_code }} ({{ $summary['month'] }})</h1>
<div class="card"><div class="card-body">
<table class="table table-bordered mb-0">
    <tr><th>Present</th><td>{{ $summary['present'] }}</td></tr>
    <tr><th>Late</th><td>{{ $summary['late'] }}</td></tr>
    <tr><th>Absent</th><td>{{ $summary['absent'] }}</td></tr>
    <tr><th>Leave</th><td>{{ $summary['leave'] }}</td></tr>
    <tr><th>Total Recorded Days</th><td>{{ $summary['total_recorded'] }}</td></tr>
</table>
</div></div>
@endsection
