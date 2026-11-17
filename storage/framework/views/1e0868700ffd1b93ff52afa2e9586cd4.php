<?php $__env->startSection('title', __('New Training')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('New Training')); ?></h1>
<?php if($errors->any()): ?><div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><?php endif; ?>
<form action="<?php echo e(route('trainings.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo $__env->make('trainings._form', ['record' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/trainings/create.blade.php ENDPATH**/ ?>