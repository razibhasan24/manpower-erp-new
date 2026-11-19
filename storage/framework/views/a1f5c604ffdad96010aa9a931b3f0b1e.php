<?php $__env->startSection('title', 'New Account'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">New Account</h1>
<div class="card"><div class="card-body">
<form method="POST" action="<?php echo e(route('accounting.accounts.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3"><label class="form-label">Code</label><input type="text" name="code" value="<?php echo e(old('code', $item->code ?? '')); ?>" class="form-control"><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div><div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" value="<?php echo e(old('name', $item->name ?? '')); ?>" class="form-control"><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div><div class="mb-3"><label class="form-label">Parent Account</label><select name="parent_id" class="form-select"><option value="">— Select —</option><?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($opt->id); ?>" <?php echo e((string) old('parent_id', $item->parent_id ?? '') === (string) $opt->id ? 'selected' : ''); ?>><?php echo e($opt->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div><div class="mb-3"><label class="form-label">Account Type</label><select name="account_type_id" class="form-select"><option value="">— Select —</option><?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($opt->id); ?>" <?php echo e((string) old('account_type_id', $item->account_type_id ?? '') === (string) $opt->id ? 'selected' : ''); ?>><?php echo e($opt->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['account_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div><div class="mb-3"><label class="form-label">Normal Balance</label><select name="normal_balance" class="form-select"><option value="debit" <?php echo e(old('normal_balance', $item->normal_balance ?? '') == 'debit' ? 'selected' : ''); ?>>Debit</option><option value="credit" <?php echo e(old('normal_balance', $item->normal_balance ?? '') == 'credit' ? 'selected' : ''); ?>>Credit</option></select><?php $__errorArgs = ['normal_balance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div><div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" <?php echo e(old('status', $item->status ?? '') == 'active' ? 'selected' : ''); ?>>Active</option><option value="inactive" <?php echo e(old('status', $item->status ?? '') == 'inactive' ? 'selected' : ''); ?>>Inactive</option></select><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="<?php echo e(route('accounting.accounts.index')); ?>" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/accounts/create.blade.php ENDPATH**/ ?>