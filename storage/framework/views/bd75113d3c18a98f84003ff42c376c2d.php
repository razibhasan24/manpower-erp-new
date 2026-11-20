<?php $__env->startSection('title', 'New Voucher'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">New Voucher</h1>
<?php if($errors->any()): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-3"><div class="card-header">Financial Operation (§21.G)</div><div class="card-body">
            <form method="POST" action="<?php echo e(route('accounting.vouchers.operation')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3"><label class="form-label">Operation</label>
                    <select name="operation" class="form-select" required>
                        <?php $__currentLoopData = $operations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($op); ?>"><?php echo e(ucwords(str_replace('_', ' ', $op))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></div>
                <div class="mb-3"><label class="form-label">Amount</label><input type="number" step="0.01" min="0.01" name="amount" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Date</label><input type="date" name="voucher_date" value="<?php echo e(now()->toDateString()); ?>" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Debit Account</label>
                    <select name="debit_account_id" class="form-select"><?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($a->id); ?>"><?php echo e($a->code); ?> — <?php echo e($a->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="mb-3"><label class="form-label">Credit Account</label>
                    <select name="credit_account_id" class="form-select"><?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($a->id); ?>"><?php echo e($a->code); ?> — <?php echo e($a->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="mb-3"><label class="form-label">Narration</label><textarea name="narration" class="form-control" rows="2"></textarea></div>
                <button type="submit" class="btn btn-primary">Post Operation</button>
            </form>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card"><div class="card-header">Manual Journal Voucher</div><div class="card-body">
            <form method="POST" action="<?php echo e(route('accounting.vouchers.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3"><label class="form-label">Voucher Type</label>
                    <select name="voucher_type" class="form-select" required>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t); ?>"><?php echo e(ucfirst($t)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></div>
                <div class="mb-3"><label class="form-label">Date</label><input type="date" name="voucher_date" value="<?php echo e(now()->toDateString()); ?>" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Narration</label><textarea name="narration" class="form-control" rows="2"></textarea></div>
                <p class="text-muted small">Add at least two lines. Total debit must equal total credit.</p>
                <?php for($i = 0; $i < 2; $i++): ?>
                <div class="row g-2 mb-2">
                    <div class="col-6"><select name="lines[<?php echo e($i); ?>][account_id]" class="form-select" required><option value="">— Account —</option><?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($a->id); ?>"><?php echo e($a->code); ?> — <?php echo e($a->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                    <div class="col-3"><input type="number" step="0.01" min="0" name="lines[<?php echo e($i); ?>][debit]" class="form-control" placeholder="Debit"></div>
                    <div class="col-3"><input type="number" step="0.01" min="0" name="lines[<?php echo e($i); ?>][credit]" class="form-control" placeholder="Credit"></div>
                </div>
                <?php endfor; ?>
                <button type="submit" class="btn btn-primary">Post Voucher</button>
            </form>
        </div></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/vouchers/create.blade.php ENDPATH**/ ?>