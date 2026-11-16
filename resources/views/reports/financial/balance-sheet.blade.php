@extends('layouts.app')
@section('title', 'Balance Sheet')
@section('content')
<h1 class="mb-3">Balance Sheet</h1>
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
    <div class="col-md-6"><div class="card mb-3"><div class="card-header">Assets</div><div class="card-body">
        @foreach($assets as $row)<div class="d-flex justify-content-between"><span>{{ $row['code'] }} — {{ $row['name'] }}</span><span>{{ number_format($row['amount'], 2) }}</span></div>@endforeach
        <hr><div class="d-flex justify-content-between fw-bold"><span>Total Assets</span><span>{{ number_format($total_assets, 2) }}</span></div>
    </div></div></div>
    <div class="col-md-6"><div class="card mb-3"><div class="card-header">Liabilities &amp; Equity</div><div class="card-body">
        @foreach($liabilities as $row)<div class="d-flex justify-content-between"><span>{{ $row['code'] }} — {{ $row['name'] }}</span><span>{{ number_format($row['amount'], 2) }}</span></div>@endforeach
        @foreach($equity as $row)<div class="d-flex justify-content-between"><span>{{ $row['code'] }} — {{ $row['name'] }}</span><span>{{ number_format($row['amount'], 2) }}</span></div>@endforeach
        <div class="d-flex justify-content-between"><span>Net Profit</span><span>{{ number_format($net_profit, 2) }}</span></div>
        <hr><div class="d-flex justify-content-between fw-bold"><span>Total Liabilities &amp; Equity</span><span>{{ number_format($total_liabilities_equity, 2) }}</span></div>
    </div></div></div>
</div>
<p>@if($balanced)<span class="badge bg-success">Balanced</span>@else<span class="badge bg-danger">Out of balance — investigate immediately</span>@endif</p>

@endsection
