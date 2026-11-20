<?php if($errors->any()): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>
<form method="POST" action="<?php echo e($action); ?>">
    <?php echo csrf_field(); ?>
    <?php if($method !== 'POST'): ?> <?php echo method_field($method); ?> <?php endif; ?>
    <div class="mb-3">
        <label for="name" class="form-label">Payment Mode Name</label>
        <input id="name" name="name" class="form-control" value="<?php echo e(old('name', $item->name ?? '')); ?>" placeholder="e.g. Cash Counter, Dutch-Bangla Bank" required>
    </div>
    <div class="mb-3">
        <label for="mode_type" class="form-label">Type</label>
        <select id="mode_type" name="mode_type" class="form-select" required>
            <option value="cash" <?php if(old('mode_type', $item->mode_type ?? '') === 'cash'): echo 'selected'; endif; ?>>Cash</option>
            <option value="bank" <?php if(old('mode_type', $item->mode_type ?? '') === 'bank'): echo 'selected'; endif; ?>>Bank</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="account_id" class="form-label">Linked Cash / Bank Account</label>
        <select id="account_id" name="account_id" class="form-select" required>
            <option value="">Select chart account</option>
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($account->id); ?>" <?php if((string) old('account_id', $item->account_id ?? '') === (string) $account->id): echo 'selected'; endif; ?>><?php echo e($account->code); ?> — <?php echo e($account->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="form-text">Only active posting accounts under Cash &amp; Bank are listed. To add a new ledger account, use <a href="<?php echo e(route('accounting.accounts.create')); ?>">Add Account</a> first.</div>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select" required>
            <option value="active" <?php if(old('status', $item->status ?? 'active') === 'active'): echo 'selected'; endif; ?>>Active</option>
            <option value="inactive" <?php if(old('status', $item->status ?? '') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Payment Mode</button>
    <a href="<?php echo e(route('accounting.payment-modes.index')); ?>" class="btn btn-secondary">Cancel</a>
</form>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/payment-modes/_form.blade.php ENDPATH**/ ?>