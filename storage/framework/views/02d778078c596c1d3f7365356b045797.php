<?php $__env->startSection('title', __('Create Airline')); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0"><?php echo e(__('Create Airline')); ?></h1>
    <a class="btn btn-outline-secondary" href="<?php echo e(route('airlines.index')); ?>"><?php echo e(__('Back to Airlines')); ?></a>
</div>

<div class="card"><div class="card-body">
    <form method="POST" action="<?php echo e(route('airlines.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label"><?php echo e(__('Airline Name')); ?> *</label>
                <input id="name" name="name" value="<?php echo e(old('name')); ?>" maxlength="255" required class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label for="code" class="form-label"><?php echo e(__('Airline Code')); ?></label>
                <input id="code" name="code" value="<?php echo e(old('code')); ?>" maxlength="10" class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label"><?php echo e(__('Status')); ?> *</label>
                <select id="status" name="status" required class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="active" <?php if(old('status', 'active') === 'active'): echo 'selected'; endif; ?>><?php echo e(__('Active')); ?></option>
                    <option value="inactive" <?php if(old('status') === 'inactive'): echo 'selected'; endif; ?>><?php echo e(__('Inactive')); ?></option>
                </select>
                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><?php echo e(__('Save Airline')); ?></button>
            <a class="btn btn-outline-secondary" href="<?php echo e(route('airlines.index')); ?>"><?php echo e(__('Cancel')); ?></a>
        </div>
    </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/flight-support/airlines-create.blade.php ENDPATH**/ ?>