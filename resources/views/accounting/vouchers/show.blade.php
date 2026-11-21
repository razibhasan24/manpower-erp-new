@extends('layouts.app')
@section('title', 'Voucher ' . $item->voucher_number)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1>{{ $item->voucher_number }}</h1>@can('viewAny', \App\Models\Voucher::class)<a href="{{ route('accounting.vouchers.index') }}" class="btn btn-secondary">Back to list</a>@endcan</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body"><table class="table table-bordered mb-0">
    <tr><th style="width:200px">Voucher Number</th><td>{{ $item->voucher_number }}</td></tr>
    <tr><th>Type</th><td>{{ ucfirst($item->voucher_type) }}</td></tr>
    <tr><th>Date</th><td>{{ $item->voucher_date }}</td></tr>
    <tr><th>Branch</th><td>{{ $item->branch?->name ?? '—' }}</td></tr>
    @if($item->paymentMode)<tr><th>Payment Mode</th><td>{{ $item->paymentMode->name }}</td></tr>@endif
    <tr><th>Status</th><td><x-badge :status="$item->status" /></td></tr>
    @if($item->status === 'draft')<tr><th>Approval</th><td class="text-warning">Pending approval. This voucher has not affected account balances.</td></tr>@endif
    <tr><th>Total Debit</th><td>{{ number_format($item->total_debit, 2) }}</td></tr>
    <tr><th>Total Credit</th><td>{{ number_format($item->total_credit, 2) }}</td></tr>
    <tr><th>Narration</th><td>{{ $item->narration }}</td></tr>
    @if($item->payee)<tr><th>Payee / Received From</th><td>{{ $item->payee }}</td></tr>@endif
    @if($item->attachment_path)<tr><th>Attachment</th><td><a href="{{ route('accounting.vouchers.attachment', $item) }}">{{ $item->attachment_original_name ?? 'Download attachment' }}</a></td></tr>@endif
    @if($item->reversal_voucher_id)<tr><th>Reversed By</th><td><a href="{{ route('accounting.vouchers.show', $item->reversal_voucher_id) }}">View reversal voucher</a></td></tr>@endif
</table></div></div>
<div class="card mb-3"><div class="card-header">{{ $item->status === 'draft' ? 'Draft Entries' : 'Journal Lines' }}</div><div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Account</th><th>Narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th></tr></thead><tbody>
    @if(!$item->journalEntry)
        @foreach($item->draft_lines ?? [] as $line)
            @php($account = $draftAccounts[$line['account_id'] ?? 0] ?? null)
            <tr><td>{{ $account?->code }} - {{ $account?->name }}</td><td>{{ $line['narration'] ?? '' }}</td><td class="text-end">{{ number_format((float)($line['debit'] ?? 0), 2) }}</td><td class="text-end">{{ number_format((float)($line['credit'] ?? 0), 2) }}</td></tr>
        @endforeach
    @else
        @foreach($item->journalEntry->lines ?? [] as $line)
            <tr><td>{{ $line->account->code ?? '' }} - {{ $line->account->name ?? '' }}</td><td>{{ $line->narration }}</td><td class="text-end">{{ number_format($line->debit, 2) }}</td><td class="text-end">{{ number_format($line->credit, 2) }}</td></tr>
        @endforeach
    @endif
    </tbody></table></div></div>
@if($item->status === 'draft')
<div class="d-flex flex-wrap gap-2 mb-3">
    @can('update', $item)<a href="{{ route('accounting.vouchers.edit', $item) }}" class="btn btn-outline-primary">Edit Draft</a>@endcan
    @can('approve', $item)<form method="POST" action="{{ route('accounting.vouchers.approve', $item) }}" onsubmit="return confirm('Approve and post this voucher to accounts?')">@csrf<button class="btn btn-success" type="submit">Approve &amp; Post</button></form>@endcan
    @can('reject', $item)<form method="POST" action="{{ route('accounting.vouchers.reject', $item) }}" onsubmit="return confirm('Reject this draft voucher?')">@csrf<button class="btn btn-outline-danger" type="submit">Reject Draft</button></form>@endcan
</div>
@endif
@if($item->status === 'posted')
    @can('reverse', $item)<div class="card"><div class="card-header">Reverse this voucher</div><div class="card-body"><p class="text-muted">Posted vouchers cannot be edited or deleted. A reversal creates a mirror-image voucher and marks this one reversed.</p><form method="POST" action="{{ route('accounting.vouchers.reverse', $item) }}" onsubmit="return confirm('Reverse voucher {{ $item->voucher_number }}? This cannot be undone.')">@csrf<div class="mb-3"><label class="form-label">Reason (required)</label><input type="text" name="reason" class="form-control" required maxlength="500"></div><button type="submit" class="btn btn-danger">Reverse Voucher</button></form></div></div>@endcan
@endif
@endsection
