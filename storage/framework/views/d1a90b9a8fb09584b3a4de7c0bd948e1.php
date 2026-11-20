<?php $__env->startSection('title', __('Add Training Course')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('Add Training Course')); ?></h1><form method="POST" action="<?php echo e(route('training-courses.store')); ?>"><?php echo csrf_field(); ?> <?php echo $__env->make('training-courses._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><div class="mt-3"><button class="btn btn-primary"><?php echo e(__('Save')); ?></button> <a class="btn btn-outline-secondary" href="<?php echo e(route('training-courses.index')); ?>"><?php echo e(__('Cancel')); ?></a></div></form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/training-courses/create.blade.php ENDPATH**/ ?>