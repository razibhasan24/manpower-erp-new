@extends('layouts.app')
@section('title', 'Income Statement')
@section('content')
<h1 class="mb-3">Income Statement</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" placeholder="From"></div>
    <div class="col-md-2"><input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" placeholder="To"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
    <div class="col-auto">
        <a class="btn btn-outline-primary" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">Export PDF</a>
        <a class="btn btn-outline-primary" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">Export Excel</a>
    </div>
</form>

<div class="row">
    <div class="col-md-6"><div class="card mb-3"><div class="card-header">Income</div><div class="card-body">
        @foreach($income as $row)<div class="d-flex justify-content-between"><span>{{ $row['code'] }} — {{ $row['name'] }}</span><span>{{ number_format($row['amount'], 2) }}</span></div>@endforeach
        <hr><div class="d-flex justify-content-between fw-bold"><span>Total Income</span><span>{{ number_format($total_income, 2) }}</span></div>
    </div></div></div>
    <div class="col-md-6"><div class="card mb-3"><div class="card-header">Expenses</div><div class="card-body">
        @foreach($expenses as $row)<div class="d-flex justify-content-between"><span>{{ $row['code'] }} — {{ $row['name'] }}</span><span>{{ number_format($row['amount'], 2) }}</span></div>@endforeach
        <hr><div class="d-flex justify-content-between fw-bold"><span>Total Expenses</span><span>{{ number_format($total_expenses, 2) }}</span></div>
    </div></div></div>
</div>
<div class="card"><div class="card-body d-flex justify-content-between fw-bold"><span>Net Profit</span><span>{{ number_format($net_profit, 2) }}</span></div></div>

@endsection
