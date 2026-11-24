<?php $__env->startSection('title', 'Voucher ' . $item->voucher_number); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Voucher <?php echo e($item->voucher_number); ?></h1>
            <p class="text-muted small mb-0">View voucher details and journal lines.</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Voucher::class)): ?>
            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('accounting.vouchers.index')).'','variant' => 'dark','size' => 'sm','class' => 'btn btn-dark btn-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('accounting.vouchers.index')).'','variant' => 'dark','size' => 'sm','class' => 'btn btn-dark btn-sm']); ?>
                <i class="bi bi-arrow-left me-1"></i> Back to list
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?php echo e(session('success')); ?></div>
            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','class' => 'btn-close','dataBsDismiss' => 'alert','ariaLabel' => 'Close']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','class' => 'btn-close','data-bs-dismiss' => 'alert','aria-label' => 'Close']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Voucher Number</div>
                    <div class="voucher-value fw-bold"><?php echo e($item->voucher_number); ?></div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Type</div>
                    <div class="voucher-value text-capitalize">
                        <?php echo e($item->voucher_type === 'receipt' ? 'Receive' : $item->voucher_type); ?></div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Date</div>
                    <div class="voucher-value"><?php echo e(\Carbon\Carbon::parse($item->voucher_date)->format('d M, Y')); ?></div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Status</div>
                    <div><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $item->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Branch</div>
                    <div class="voucher-value"><?php echo e($item->branch?->name ?? '—'); ?></div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Payment Mode</div>
                    <div class="voucher-value"><?php echo e($item->paymentMode?->name ?? '—'); ?></div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="voucher-label">Payee / Received From</div>
                    <div class="voucher-value"><?php echo e($item->payee ?? '—'); ?></div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex gap-2">
                        
                        <div
                            class="flex-fill bg-danger bg-opacity-10 p-2 rounded border border-danger border-opacity-25 text-center">
                            <div class="voucher-label text-danger mb-1">Debit</div>
                            <div class="voucher-value amount-debit fs-6"><?php echo e(number_format($item->total_debit, 2)); ?></div>
                        </div>

                        
                        <div
                            class="flex-fill bg-success bg-opacity-10 p-2 rounded border border-success border-opacity-25 text-center">
                            <div class="voucher-label text-success mb-1">Credit</div>
                            <div class="voucher-value amount-credit fs-6"><?php echo e(number_format($item->total_credit, 2)); ?></div>
                        </div>
                    </div>
                </div>

                
                <div class="col-12 mt-2">
                    <div class="voucher-label">Narration / Notes</div>
                    <div class="bg-light p-3 rounded border voucher-value"><?php echo e($item->narration); ?></div>
                </div>

                
                <?php if($item->attachment_path): ?>
                    <div class="col-md-6">
                        <div class="voucher-label">Attachment</div>
                        <a href="<?php echo e(route('accounting.vouchers.attachment', $item)); ?>"
                            class="btn btn-sm btn-outline-primary mt-1">
                            <i class="bi bi-paperclip me-1"></i>
                            <?php echo e($item->attachment_original_name ?? 'Download attachment'); ?>

                        </a>
                    </div>
                <?php endif; ?>

                
                <?php if($item->reversal_voucher_id): ?>
                    <div class="col-md-6">
                        <div class="voucher-label">Reversed By</div>
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('accounting.vouchers.show', $item->reversal_voucher_id)).'','variant' => 'info','size' => 'sm','class' => 'btn btn-sm btn-outline-info mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('accounting.vouchers.show', $item->reversal_voucher_id)).'','variant' => 'info','size' => 'sm','class' => 'btn btn-sm btn-outline-info mt-1']); ?>
                            <i class="bi bi-arrow-repeat me-1"></i> View reversal voucher
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if($item->status === 'draft'): ?>
                <div class="alert alert-warning d-flex align-items-center mt-4 mb-0 border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div><strong>Pending approval.</strong> This voucher has not affected account balances yet.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom pt-4 pb-3">
            <h5 class="mb-0 fw-semibold">
                <?php echo e($item->status === 'draft' ? 'Draft Entries' : 'Journal Lines'); ?>

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
                    <?php if(!$item->journalEntry): ?>
                        <?php $__empty_1 = true; $__currentLoopData = $item->draft_lines ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php ($account = $draftAccounts[$line['account_id'] ?? 0] ?? null); ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark">
                                    <span class="text-muted small"><?php echo e($account?->code); ?></span> — <?php echo e($account?->name); ?>

                                </td>
                                <td class="text-muted"><?php echo e($line['narration'] ?? ''); ?></td>
                                <td class="text-end amount-debit"><?php echo e(number_format((float) ($line['debit'] ?? 0), 2)); ?>

                                </td>
                                <td class="text-end pe-4 amount-credit">
                                    <?php echo e(number_format((float) ($line['credit'] ?? 0), 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No draft lines found.</td>
                            </tr>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php $__empty_1 = true; $__currentLoopData = $item->journalEntry->lines ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark">
                                    <span class="text-muted small"><?php echo e($line->account->code ?? ''); ?></span> —
                                    <?php echo e($line->account->name ?? ''); ?>

                                </td>
                                <td class="text-muted"><?php echo e($line->narration); ?></td>
                                <td class="text-end amount-debit"><?php echo e(number_format($line->debit, 2)); ?></td>
                                <td class="text-end pe-4 amount-credit"><?php echo e(number_format($line->credit, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No journal lines found.</td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                </tbody>
                
                <?php if(count($item->draft_lines ?? ($item->journalEntry->lines ?? [])) > 0): ?>
                    <tfoot class="border-top">
                        <tr>
                            <th colspan="2" class="text-end py-3">Total</th>
                            <th class="text-end amount-debit"><?php echo e(number_format($item->total_debit, 2)); ?></th>
                            <th class="text-end pe-4 amount-credit"><?php echo e(number_format($item->total_credit, 2)); ?></th>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>

    
    <?php if($item->status === 'draft'): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4 d-flex flex-wrap gap-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $item)): ?>
                    <a href="<?php echo e(route('accounting.vouchers.edit', $item)); ?>" class="btn btn-primary px-4">
                        <i class="bi bi-pencil-square me-1"></i> Edit Draft
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $item)): ?>
                    <form method="POST" action="<?php echo e(route('accounting.vouchers.approve', $item)); ?>" class="d-inline"
                        id="approveForm">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-success px-4" type="button" id="btn-approve">
                            <i class="bi bi-check-circle me-1"></i> Approve & Post
                        </button>
                    </form>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reject', $item)): ?>
                    <form method="POST" action="<?php echo e(route('accounting.vouchers.reject', $item)); ?>" class="d-inline"
                        id="rejectForm">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-outline-danger px-4" type="button" id="btn-reject">
                            <i class="bi bi-x-circle me-1"></i> Reject Draft
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($item->status === 'posted'): ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reverse', $item)): ?>
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
                    <form method="POST" action="<?php echo e(route('accounting.vouchers.reverse', $item)); ?>" id="reverseForm">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold text-muted">Reason <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="reason" class="form-control" required maxlength="500"
                                    placeholder="Enter the reason for reversal...">
                            </div>
                            <div class="col-md-4">
                                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','variant' => 'danger','class' => 'btn btn-danger w-100 px-4','id' => 'btn-reverse']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'danger','class' => 'btn btn-danger w-100 px-4','id' => 'btn-reverse']); ?>
                                    Reverse Voucher
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/vouchers/show.blade.php ENDPATH**/ ?>