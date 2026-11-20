<?php $__env->startSection('title', __('Edit role permissions')); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0"><?php echo e(__('Edit permissions: :role', ['role' => $role->name])); ?></h1>
    <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-outline-secondary"><?php echo e(__('Back to roles')); ?></a>
</div>
<div class="alert alert-info"><?php echo e(__('Only permissions available to your own account can be assigned. Super Admin role permissions are protected.')); ?></div>
<form method="POST" action="<?php echo e(route('roles.update', $role)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="row g-3 mb-3">
        <?php $__currentLoopData = $permissions->groupBy(fn ($permission) => explode('.', $permission->name, 2)[0]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6">
                <div class="border rounded p-3 h-100">
                    <strong><?php echo e(\Illuminate\Support\Str::headline($module)); ?></strong>
                    <div class="mt-2">
                        <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission_ids[]"
                                    id="permission_<?php echo e($permission->id); ?>" value="<?php echo e($permission->id); ?>"
                                    <?php if(in_array((string) $permission->id, array_map('strval', old('permission_ids', $role->permissions->pluck('id')->all())), true)): echo 'checked'; endif; ?>>
                                <label class="form-check-label" for="permission_<?php echo e($permission->id); ?>"><?php echo e($permission->name); ?></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php $__errorArgs = ['permission_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger mb-2"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <button class="btn btn-primary" type="submit"><?php echo e(__('Save permissions')); ?></button>
    <a class="btn btn-outline-secondary" href="<?php echo e(route('roles.index')); ?>"><?php echo e(__('Cancel')); ?></a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/access-administration/edit-role.blade.php ENDPATH**/ ?>