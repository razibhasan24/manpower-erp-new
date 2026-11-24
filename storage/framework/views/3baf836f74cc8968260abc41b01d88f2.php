<?php ($isReceipt = $action === 'receive'); ?>
<?php ($dueAmount = $isReceipt ? max((float) $balance, 0) : max((float) -$balance, 0)); ?>
<?php ($partyTypeLabel = ['agent' => 'Agent', 'passport_holder' => 'Passport Holder', 'vendor' => 'Vendor'][$type] ?? 'Party'); ?>
<?php ($partyName = $type === 'passport_holder' ? $party->full_name : $party->name); ?>

<?php $__env->startSection('title', ($isReceipt ? 'Receive from ' : 'Pay ') . $partyTypeLabel); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1"><?php echo e($isReceipt ? 'Receive from ' : 'Pay '); ?><?php echo e($partyTypeLabel); ?></h1>
            <p class="text-muted small mb-0">
                <i class="bi bi-person me-1"></i> <?php echo e($partyName); ?>

                <span class="mx-2">·</span>
                Current balance: <span
                    class="fw-semibold <?php echo e($balance > 0 ? 'text-danger' : 'text-success'); ?>"><?php echo e(number_format($balance, 2)); ?></span>
            </p>
        </div>
        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('reports.financial.sub-ledger.due-list', ['type' => $type])).'','variant' => 'dark','class' => 'btn btn-dark btn-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.financial.sub-ledger.due-list', ['type' => $type])).'','variant' => 'dark','class' => 'btn btn-dark btn-sm']); ?>
            <i class="bi bi-arrow-left me-1"></i> Back to Due List
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

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
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

    
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST"
                action="<?php echo e(route('reports.financial.sub-ledger.transaction.store', ['type' => $type, 'id' => $id, 'action' => $action])); ?>">
                <?php echo csrf_field(); ?>

                <div class="row g-4">
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e($partyTypeLabel); ?></label>
                        <input class="form-control bg-light" value="<?php echo e($partyName); ?>" readonly>
                    </div>

                    
                    <div class="col-md-6">
                        <label
                            class="form-label fw-semibold text-muted small text-uppercase"><?php echo e($isReceipt ? 'Current Due Amount' : 'Current Credit Balance'); ?></label>
                        <input id="current_due" type="text"
                            class="form-control bg-light fw-bold <?php echo e($dueAmount > 0 ? 'text-danger' : 'text-dark'); ?>"
                            value="<?php echo e(number_format($dueAmount, 2, '.', '')); ?>" readonly>
                    </div>

                    
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-semibold small text-uppercase">
                            <?php echo e($isReceipt ? 'Received Amount' : 'Paid Amount'); ?> <span class="text-danger">*</span>
                        </label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-white fw-bold">৳</span>
                            <input id="amount" name="amount" type="number" min="0.01"
                                max="<?php echo e(number_format($amountAvailable, 2, '.', '')); ?>" step="0.01"
                                class="form-control form-control-lg fw-bold <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('amount')); ?>" placeholder="0.00" required>
                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-text small">Maximum allowed:
                            <strong><?php echo e(number_format($amountAvailable, 2)); ?></strong>
                        </div>
                    </div>

                    
                    <?php if($isReceipt): ?>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted small text-uppercase">Due After Receipt</label>
                            <input id="due_after_receipt" type="text" class="form-control bg-light fw-bold text-success"
                                value="<?php echo e(number_format($dueAmount, 2, '.', '')); ?>" readonly>
                        </div>
                    <?php endif; ?>

                    
                    <div class="col-md-6">
                        <label for="payment_mode_id" class="form-label fw-semibold small text-uppercase">
                            <?php echo e($isReceipt ? 'Received Into (Account)' : 'Paid From (Account)'); ?> <span
                                class="text-danger">*</span>
                        </label>
                        <select id="payment_mode_id" name="payment_mode_id"
                            class="form-select <?php $__errorArgs = ['payment_mode_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Select payment mode --</option>
                            <?php $__currentLoopData = $paymentModes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($mode->id); ?>" <?php if(old('payment_mode_id') == $mode->id): echo 'selected'; endif; ?>>
                                    <?php echo e($mode->name); ?> (<?php echo e(ucfirst($mode->mode_type)); ?>) — <?php echo e($mode->account?->code); ?>

                                    <?php echo e($mode->account?->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['payment_mode_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <?php if($paymentModes->isEmpty()): ?>
                            <div class="form-text small text-danger">
                                <i class="bi bi-exclamation-circle me-1"></i> No active payment mode found. Add one from <a
                                    href="<?php echo e(route('accounting.payment-modes.index')); ?>" class="fw-medium">Accounting →
                                    Payment Modes</a>.
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="col-md-6">
                        <label for="voucher_date" class="form-label fw-semibold small text-uppercase">
                            <?php echo e($isReceipt ? 'Receipt Date' : 'Payment Date'); ?> <span class="text-danger">*</span>
                        </label>
                        <input id="voucher_date" name="voucher_date" type="date"
                            class="form-control <?php $__errorArgs = ['voucher_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('voucher_date', now()->toDateString())); ?>" required>
                        <?php $__errorArgs = ['voucher_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="col-12">
                        <label for="narration" class="form-label fw-semibold small text-uppercase">Text / Narration</label>
                        <textarea id="narration" name="narration" rows="2" class="form-control <?php $__errorArgs = ['narration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Enter any additional notes (optional)..."><?php echo e(old('narration')); ?></textarea>
                        <?php $__errorArgs = ['narration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('reports.financial.sub-ledger.due-list', ['type' => $type])).'','variant' => 'danger','class' => 'btn btn-danger px-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.financial.sub-ledger.due-list', ['type' => $type])).'','variant' => 'danger','class' => 'btn btn-danger px-4']); ?>
                        Cancel
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
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => ''.e($isReceipt ? 'success' : 'danger').'','class' => 'btn '.e($isReceipt ? 'btn-success' : 'btn-danger').' px-4 shadow-sm','type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => ''.e($isReceipt ? 'success' : 'danger').'','class' => 'btn '.e($isReceipt ? 'btn-success' : 'btn-danger').' px-4 shadow-sm','type' => 'submit']); ?>
                        <i class="bi <?php echo e($isReceipt ? 'bi-box-arrow-in-down' : 'bi-box-arrow-up'); ?> me-1"></i>
                        <?php echo e($isReceipt ? 'Create Receipt Voucher' : 'Create Payment Voucher'); ?>

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
            </form>
        </div>
    </div>

    
    <?php if($isReceipt): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const amount = document.getElementById('amount');
                const due = parseFloat(document.getElementById('current_due').value) || 0;
                const remaining = document.getElementById('due_after_receipt');

                if (amount && remaining) {
                    amount.addEventListener('input', function() {
                        remaining.value = Math.max(0, due - (parseFloat(this.value) || 0)).toFixed(2);
                    });
                }
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/sub-ledgers/transaction.blade.php ENDPATH**/ ?>