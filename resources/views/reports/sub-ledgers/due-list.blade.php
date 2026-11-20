@extends('layouts.app')
@section('title', 'Due List')
@section('content')
<h1 class="mb-3">{{ ucwords(str_replace('_', ' ', $type)) }} Due List</h1>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Party ID</th><th class="text-end">Total Debit</th><th class="text-end">Total Credit</th><th class="text-end">Balance Due</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    @forelse($rows as $row)
        <tr><td>{{ $partyNames[$row->party_id] ?? ('#'.$row->party_id) }}</td><td class="text-end">{{ number_format($row->total_debit, 2) }}</td>
            <td class="text-end">{{ number_format($row->total_credit, 2) }}</td>
            <td class="text-end">{{ number_format($row->balance, 2) }}</td>
            <td class="text-end">
                <a href="{{ route('reports.financial.sub-ledger', ['type' => $type, 'id' => $row->party_id]) }}" class="btn btn-sm btn-outline-primary">Ledger</a>
                @if($type === 'passport_holder')
                    @can('create', \App\Models\Voucher::class)
                        @if($row->balance > 0)
                            <a href="{{ route('reports.financial.sub-ledger.transaction.create', ['type' => $type, 'id' => $row->party_id, 'action' => 'receive']) }}" class="btn btn-sm btn-success">Receive</a>
                        @elseif($row->balance < 0)
                            <a href="{{ route('reports.financial.sub-ledger.transaction.create', ['type' => $type, 'id' => $row->party_id, 'action' => 'pay']) }}" class="btn btn-sm btn-outline-danger">Pay</a>
                        @endif
                    @endcan
                @endif
            </td></tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted py-4">No outstanding dues.</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>
@endsection
