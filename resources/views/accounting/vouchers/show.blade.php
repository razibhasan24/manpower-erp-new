@extends('layouts.app')

@section('title', 'Voucher ' . $item->voucher_number)

@push('styles')
    <style>
        .voucher-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .voucher-value {
            font-size: 0.95rem;
            color: #1f2937;
            font-weight: 500;
        }

        .amount-debit {
            color: #dc3545;
            font-weight: 600;
        }

        .amount-credit {
            color: #198754;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Voucher {{ $item->voucher_number }}</h1>
            <p class="text-muted small mb-0">View voucher details and journal lines.</p>
        </div>
        @can('viewAny', \App\Models\Voucher::class)
            <x-button href="{{ route('accounting.vouchers.index') }}" variant="dark" size="sm" class="btn btn-dark btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to list
            </x-button>
        @endcan
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <x-button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></x-button>
        </div>
    @endif

    {{-- ✅ Details Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                {{-- Voucher Number --}}
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Voucher Number</div>
                    <div class="voucher-value fw-bold">{{ $item->voucher_number }}</div>
                </div>

                {{-- Type --}}
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Type</div>
                    <div class="voucher-value text-capitalize">
                        {{ $item->voucher_type === 'receipt' ? 'Receive' : $item->voucher_type }}</div>
                </div>

                {{-- Date --}}
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Date</div>
                    <div class="voucher-value">{{ \Carbon\Carbon::parse($item->voucher_date)->format('d M, Y') }}</div>
                </div>

                {{-- Status --}}
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Status</div>
                    <div><x-badge :status="$item->status" /></div>
                </div>

                {{-- Branch --}}
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Branch</div>
                    <div class="voucher-value">{{ $item->branch?->name ?? '—' }}</div>
                </div>

                {{-- Payment Mode --}}
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Payment Mode</div>
                    <div class="voucher-value">{{ $item->paymentMode?->name ?? '—' }}</div>
                </div>

                {{-- Payee --}}
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Payee / Received From</div>
                    <div class="voucher-value">{{ $item->payee ?? '—' }}</div>
                </div>

                {{-- ✅ Totals Row (Debit & Credit Side-by-Side) --}}
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex gap-2">
                        {{-- Total Debit --}}
                        <div
                            class="flex-fill bg-danger bg-opacity-10 p-2 rounded border border-danger border-opacity-25 text-center">
                            <div class="voucher-label text-danger mb-1">Debit</div>
                            <div class="voucher-value amount-debit fs-6">{{ number_format($item->total_debit, 2) }}</div>
                        </div>

                        {{-- Total Credit --}}
                        <div
                            class="flex-fill bg-success bg-opacity-10 p-2 rounded border border-success border-opacity-25 text-center">
                            <div class="voucher-label text-success mb-1">Credit</div>
                            <div class="voucher-value amount-credit fs-6">{{ number_format($item->total_credit, 2) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Narration --}}
                <div class="col-12 mt-2">
                    <div class="voucher-label">Narration / Notes</div>
                    <div class="bg-light p-3 rounded border voucher-value">{{ $item->narration }}</div>
                </div>

                {{-- Attachment --}}
                @if ($item->attachment_path)
                    <div class="col-md-6">
                        <div class="voucher-label">Attachment</div>
                        <a href="{{ route('accounting.vouchers.attachment', $item) }}"
                            class="btn btn-sm btn-outline-primary mt-1">
                            <i class="bi bi-paperclip me-1"></i>
                            {{ $item->attachment_original_name ?? 'Download attachment' }}
                        </a>
                    </div>
                @endif

                {{-- Reversed By --}}
                @if ($item->reversal_voucher_id)
                    <div class="col-md-6">
                        <div class="voucher-label">Reversed By</div>
                        <x-button href="{{ route('accounting.vouchers.show', $item->reversal_voucher_id) }}" variant="info"
                            size="sm" class="btn btn-sm btn-outline-info mt-1">
                            <i class="bi bi-arrow-repeat me-1"></i> View reversal voucher
                        </x-button>
                    </div>
                @endif
            </div>

            {{-- Pending Approval Warning --}}
            @if ($item->status === 'draft')
                <div class="alert alert-warning d-flex align-items-center mt-4 mb-0 border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div><strong>Pending approval.</strong> This voucher has not affected account balances yet.</div>
                </div>
            @endif
        </div>
    </div>

    {{-- ✅ Journal Lines Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom pt-4 pb-3">
            <h5 class="mb-0 fw-semibold">
                {{ $item->status === 'draft' ? 'Draft Entries' : 'Journal Lines' }}
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">Account</th>
                        <th class="py-3 fw-semibold">Narration</th>
                        <th class="text-end py-3 fw-semibold" style="width: 150px;">Debit</th>
                        <th class="text-end pe-4 py-3 fw-semibold" style="width: 150px;">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$item->journalEntry)
                        @forelse ($item->draft_lines ?? [] as $line)
                            @php($account = $draftAccounts[$line['account_id'] ?? 0] ?? null)
                            <tr>
                                <td class="ps-4 fw-medium text-dark">
                                    <span class="text-muted small">{{ $account?->code }}</span> — {{ $account?->name }}
                                </td>
                                <td class="text-muted">{{ $line['narration'] ?? '' }}</td>
                                <td class="text-end amount-debit">{{ number_format((float) ($line['debit'] ?? 0), 2) }}
                                </td>
                                <td class="text-end pe-4 amount-credit">
                                    {{ number_format((float) ($line['credit'] ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No draft lines found.</td>
                            </tr>
                        @endforelse
                    @else
                        @forelse ($item->journalEntry->lines ?? [] as $line)
                            <tr>
                                <td class="ps-4 fw-medium text-dark">
                                    <span class="text-muted small">{{ $line->account->code ?? '' }}</span> —
                                    {{ $line->account->name ?? '' }}
                                </td>
                                <td class="text-muted">{{ $line->narration }}</td>
                                <td class="text-end amount-debit">{{ number_format($line->debit, 2) }}</td>
                                <td class="text-end pe-4 amount-credit">{{ number_format($line->credit, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No journal lines found.</td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
                {{-- Footer for Totals --}}
                @if (count($item->draft_lines ?? ($item->journalEntry->lines ?? [])) > 0)
                    <tfoot class="border-top">
                        <tr>
                            <th colspan="2" class="text-end py-3">Total</th>
                            <th class="text-end amount-debit">{{ number_format($item->total_debit, 2) }}</th>
                            <th class="text-end pe-4 amount-credit">{{ number_format($item->total_credit, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- ✅ Action Buttons (Draft) --}}
    @if ($item->status === 'draft')
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4 d-flex flex-wrap gap-3">
                @can('update', $item)
                    <a href="{{ route('accounting.vouchers.edit', $item) }}" class="btn btn-primary px-4">
                        <i class="bi bi-pencil-square me-1"></i> Edit Draft
                    </a>
                @endcan

                @can('approve', $item)
                    <form method="POST" action="{{ route('accounting.vouchers.approve', $item) }}" class="d-inline"
                        id="approveForm">
                        @csrf
                        <button class="btn btn-success px-4" type="button" id="btn-approve">
                            <i class="bi bi-check-circle me-1"></i> Approve & Post
                        </button>
                    </form>
                @endcan

                @can('reject', $item)
                    <form method="POST" action="{{ route('accounting.vouchers.reject', $item) }}" class="d-inline"
                        id="rejectForm">
                        @csrf
                        <button class="btn btn-outline-danger px-4" type="button" id="btn-reject">
                            <i class="bi bi-x-circle me-1"></i> Reject Draft
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    @endif

    {{-- ✅ Reverse Voucher (Posted) --}}
    @if ($item->status === 'posted')
        @can('reverse', $item)
            <div class="card shadow-sm border-danger border-opacity-25">
                <div
                    class="card-header bg-danger bg-opacity-10 text-danger border-bottom border-danger border-opacity-25 pt-3 pb-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-arrow-repeat me-2"></i> Reverse this voucher</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">
                        Posted vouchers cannot be edited or deleted. A reversal creates a mirror-image voucher and marks this
                        one as reversed.
                    </p>
                    <form method="POST" action="{{ route('accounting.vouchers.reverse', $item) }}" id="reverseForm">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold text-muted">Reason <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="reason" class="form-control" required maxlength="500"
                                    placeholder="Enter the reason for reversal...">
                            </div>
                            <div class="col-md-4">
                                <x-button type="button" variant="danger" class="btn btn-danger w-100 px-4"
                                    id="btn-reverse">
                                    Reverse Voucher
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
    @endif
@endsection

@push('scripts')
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Approve Button
            const btnApprove = document.getElementById('btn-approve');
            if (btnApprove) {
                btnApprove.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Approve Voucher?',
                        text: "This will post the voucher to accounts and update balances.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, approve it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('approveForm').submit();
                        }
                    });
                });
            }

            // Reject Button
            const btnReject = document.getElementById('btn-reject');
            if (btnReject) {
                btnReject.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Reject Draft?',
                        text: "Are you sure you want to reject this draft voucher?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, reject it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('rejectForm').submit();
                        }
                    });
                });
            }

            // Reverse Button
            const btnReverse = document.getElementById('btn-reverse');
            if (btnReverse) {
                btnReverse.addEventListener('click', function() {
                    const form = document.getElementById('reverseForm');
                    // Validate required reason before showing alert
                    if (!form.querySelector('input[name="reason"]').value.trim()) {
                        form.querySelector('input[name="reason"]').focus();
                        return;
                    }

                    Swal.fire({
                        title: 'Reverse Voucher?',
                        text: "This will create a mirror-image voucher. This action cannot be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, reverse it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush
