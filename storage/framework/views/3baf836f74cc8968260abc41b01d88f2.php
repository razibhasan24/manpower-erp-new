<?php ($isReceipt = $action === 'receive'); ?>
<?php ($dueAmount = max((float) $balance, 0)); ?>
<?php $__env->startSection('title', $isReceipt ? 'Receive from Passport Holder' : 'Pay Passport Holder'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h1 class="mb-1"><?php echo e($isReceipt ? 'Receive from Passport Holder' : 'Pay Passport Holder'); ?></h1>
        <div class="text-muted"><?php echo e($party->full_name); ?> · Current balance: <?php echo e(number_format($balance, 2)); ?></div>
    </div>
    <a href="<?php echo e(route('reports.financial.sub-ledger.due-list', ['type' => 'passport_holder'])); ?>" class="btn btn-outline-secondary">Back to Due List</a>
</div>
<?php if($errors->any()): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>
<div class="card"><div class="card-body">
    <form method="POST" action="<?php echo e(route('reports.financial.sub-ledger.transaction.store', ['type' => $type, 'id' => $id, 'action' => $action])); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Passport Holder</label>
            <input class="form-control" value="<?php echo e($party->full_name); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Due Amount</label>
            <input id="current_due" type="text" class="form-control" value="<?php echo e(number_format($dueAmount, 2, '.', '')); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label"><?php echo e($isReceipt ? 'Received Amount' : 'Paid Amount'); ?></label>
            <input id="amount" name="amount" type="number" min="0.01" max="<?php echo e(number_format($amountAvailable, 2, '.', '')); ?>" step="0.01" class="form-control" value="<?php echo e(old('amount')); ?>" required>
            <div class="form-text">Maximum amount: <?php echo e(number_format($amountAvailable, 2)); ?></div>
        </div>
        <?php if($isReceipt): ?>
        <div class="mb-3">
            <label class="form-label">Due After Receipt</label>
            <input id="due_after_receipt" type="text" class="form-control" value="<?php echo e(number_format($dueAmount, 2, '.', '')); ?>" readonly>
        </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="payment_mode_id" class="form-label"><?php echo e($isReceipt ? 'Received Into (Account / Payment Mode)' : 'Paid From (Account / Payment Mode)'); ?></label>
            <select id="payment_mode_id" name="payment_mode_id" class="form-select" required>
                <option value="">Select payment mode</option>
                <?php $__currentLoopData = $paymentModes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($mode->id); ?>" <?php if(old('payment_mode_id') == $mode->id): echo 'selected'; endif; ?>><?php echo e($mode->name); ?> (<?php echo e(ucfirst($mode->mode_type)); ?>) — <?php echo e($mode->account?->code); ?> <?php echo e($mode->account?->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php if($paymentModes->isEmpty()): ?>
                <div class="form-text">No active payment mode found. Add one from <a href="<?php echo e(route('accounting.payment-modes.index')); ?>">Accounting → Payment Modes</a>.</div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="voucher_date" class="form-label"><?php echo e($isReceipt ? 'Receipt Date' : 'Payment Date'); ?></label>
            <input id="voucher_date" name="voucher_date" type="date" class="form-control" value="<?php echo e(old('voucher_date', now()->toDateString())); ?>" required>
        </div>
        <div class="mb-3">
            <label for="narration" class="form-label">Text / Narration</label>
            <textarea id="narration" name="narration" class="form-control" rows="2"><?php echo e(old('narration')); ?></textarea>
        </div>
        <button class="btn <?php echo e($isReceipt ? 'btn-success' : 'btn-danger'); ?>" type="submit"><?php echo e($isReceipt ? 'Create Receipt Voucher' : 'Create Payment Voucher'); ?></button>
        <a href="<?php echo e(route('reports.financial.sub-ledger.due-list', ['type' => $type])); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div></div>
<?php if($isReceipt): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const amount = document.getElementById('amount');
    const due = parseFloat(document.getElementById('current_due').value) || 0;
    const remaining = document.getElementById('due_after_receipt');
    amount.addEventListener('input', function () {
        remaining.value = Math.max(0, due - (parseFloat(this.value) || 0)).toFixed(2);
    });
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/sub-ledgers/transaction.blade.php ENDPATH**/ ?>