<?php $__env->startSection('title', ($package->exists ? __('Edit') : __('Add')).' '.$title); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-4"><h1><?php echo e($package->exists ? __('Edit') : __('Add' )); ?> <?php echo e($title); ?></h1></div>
<div class="card"><div class="card-body"><form method="POST" action="<?php echo e($package->exists ? route('service-packages.update', [$type, $package]) : route('service-packages.store', $type)); ?>"><?php echo csrf_field(); ?> <?php if($package->exists): ?><?php echo method_field('PUT'); ?><?php endif; ?>
<div class="row g-3">
<div class="col-md-4"><label class="form-label" for="code"><?php echo e(__('Package Code')); ?> *</label><input id="code" name="code" class="form-control" maxlength="40" required value="<?php echo e(old('code', $package->code)); ?>"><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-8"><label class="form-label" for="name"><?php echo e(__('Package Name')); ?> *</label><input id="name" name="name" class="form-control" required value="<?php echo e(old('name', $package->name)); ?>"><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-4"><label class="form-label" for="duration_days"><?php echo e(__('Duration (days)')); ?></label><input id="duration_days" type="number" min="1" max="3650" name="duration_days" class="form-control" value="<?php echo e(old('duration_days', $package->duration_days)); ?>"><?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-4"><label class="form-label" for="currency"><?php echo e(__('Currency')); ?> *</label><input id="currency" name="currency" class="form-control text-uppercase" minlength="3" maxlength="3" required value="<?php echo e(old('currency', $package->currency ?: 'BDT')); ?>"><?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-4"><label class="form-label" for="price"><?php echo e(__('Package Price')); ?> *</label><input id="price" type="number" min="0" step="0.01" name="price" class="form-control" required value="<?php echo e(old('price', $package->price ?? '0.00')); ?>"><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-12"><label class="form-label" for="description"><?php echo e(__('Description')); ?></label><textarea id="description" name="description" rows="3" class="form-control"><?php echo e(old('description', $package->description)); ?></textarea><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-12"><label class="form-label" for="inclusions"><?php echo e(__('Package Includes')); ?></label><textarea id="inclusions" name="inclusions" rows="4" class="form-control" placeholder="<?php echo e(__('List services or inclusions, one per line')); ?>"><?php echo e(old('inclusions', $package->inclusions)); ?></textarea><?php $__errorArgs = ['inclusions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-12"><input type="hidden" name="is_active" value="0"><div class="form-check"><input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input" <?php if(old('is_active', $package->is_active ?? true)): echo 'checked'; endif; ?>><label for="is_active" class="form-check-label"><?php echo e(__('Active and available to agents')); ?></label></div><?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
</div><div class="d-flex gap-2 mt-4"><button class="btn btn-primary"><?php echo e($package->exists ? __('Save Changes') : __('Add Package')); ?></button><a class="btn btn-outline-secondary" href="<?php echo e(route('service-packages.index', $type)); ?>"><?php echo e(__('Cancel')); ?></a></div>
</form></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/service-packages/form.blade.php ENDPATH**/ ?>