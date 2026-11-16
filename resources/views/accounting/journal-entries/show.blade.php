@extends('layouts.app')
@section('title', 'Journal Entry')
@section('content')
<h1 class="mb-3">Journal Entry — {{ $item->voucher->voucher_number ?? '' }}</h1>
<div class="card mb-3"><div class="card-body">
<table class="table table-bordered mb-0">
    <tr><th style="width:200px">Entry Date</th><td>{{ $item->entry_date }}</td></tr>
    <tr><th>Voucher</th><td>{{ $item->voucher->voucher_number ?? '' }} ({{ $item->voucher->voucher_type ?? '' }})</td></tr>
    <tr><th>Narration</th><td>{{ $item->narration }}</td></tr>
</table>
</div></div>
<div class="card"><div class="card-header">Lines</div><div class="table-responsive">
<table class="table mb-0">
    <thead><tr><th>Account</th><th>Sub-ledger</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th></tr></thead>
    <tbody>
    @foreach($item->lines as $line)
        <tr><td>{{ $line->account->code ?? '' }} — {{ $line->account->name ?? '' }}</td>
            <td>{{ $line->sub_ledger_type ? $line->sub_ledger_type . ' #' . $line->sub_ledger_id : '—' }}</td>
            <td>{{ $line->narration }}</td>
            <td class="text-end">{{ number_format($line->debit, 2) }}</td>
            <td class="text-end">{{ number_format($line->credit, 2) }}</td></tr>
    @endforeach
    </tbody>
</table>
</div></div>
@endsection
