<?php $__env->startSection('title', __('Add Employee')); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0"><?php echo e(__('Add Employee')); ?></h1><a href="<?php echo e(route('hrm.employees.index')); ?>" class="btn btn-outline-secondary"><?php echo e(__('Back to employees')); ?></a></div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?php echo e(route('hrm.employees.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('hrm.employees._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button type="submit" class="btn btn-primary"><?php echo e(__('Create employee')); ?></button>
        <a href="<?php echo e(route('hrm.employees.index')); ?>" class="btn btn-outline-secondary"><?php echo e(__('Cancel')); ?></a>
    </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/hrm/employees/create.blade.php ENDPATH**/ ?>