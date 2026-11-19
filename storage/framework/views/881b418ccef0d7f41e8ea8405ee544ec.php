<?php $__env->startSection('name', 'New Designation'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">New Designation</h1>
<div class="card"><div class="card-body">
<form method="POST" action="<?php echo e(route('hrm.designations.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="title" value="<?php echo e(old('name', $item->name ?? '')); ?>" class="form-control"><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div><div class="mb-3"><label class="form-label">Department</label><select name="department_id" class="form-select"><option value="">— Select —</option><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($opt->id); ?>" <?php echo e((string) old('department_id', $item->department_id ?? '') === (string) $opt->id ? 'selected' : ''); ?>><?php echo e($opt->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['department_id'];
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
    <a href="<?php echo e(route('hrm.designations.index')); ?>" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/hrm/designations/create.blade.php ENDPATH**/ ?>