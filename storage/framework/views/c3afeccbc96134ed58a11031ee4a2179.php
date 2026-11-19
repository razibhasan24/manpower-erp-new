<?php $__env->startSection('title', 'Register Visitor'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">Register Visitor</h1>
<div class="card"><div class="card-body">
<form method="POST" action="<?php echo e(route('visitors.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control" required><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control"><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <div class="mb-3"><label class="form-label">Purpose</label><textarea name="purpose" class="form-control" rows="2" required><?php echo e(old('purpose')); ?></textarea><?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <div class="mb-3"><label class="form-label">Person to Meet</label><input type="text" name="person_to_meet" value="<?php echo e(old('person_to_meet')); ?>" class="form-control"><?php $__errorArgs = ['person_to_meet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    <button type="submit" class="btn btn-primary">Register &amp; Issue Pass</button>
    <a href="<?php echo e(route('visitors.index')); ?>" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/visitors/create.blade.php ENDPATH**/ ?>