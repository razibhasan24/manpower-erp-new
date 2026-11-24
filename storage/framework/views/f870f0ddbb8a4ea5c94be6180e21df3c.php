<?php $__env->startSection('title', __('Upload Delegation Document')); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="mb-3"><?php echo e(__('Upload Delegation Document')); ?></h1>
    <form method="POST" action="<?php echo e(route('delegation-documents.store')); ?>" enctype="multipart/form-data">
        <?php echo $__env->make('delegation-documents._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/delegation-documents/create.blade.php ENDPATH**/ ?>