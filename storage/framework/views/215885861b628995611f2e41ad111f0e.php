<?php $__env->startSection('title', __('Airport Pickup')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('Airport Pickup')); ?></h1>
<?php echo $__env->make('flight-support._arrival-list', ['clearUrl' => route('airport-pickup.index'), 'valueLabel' => __('Pickup'), 'value' => fn ($record) => __('Required'), 'emptyMessage' => __('No airport pickup records found.')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/flight-support/airport-pickup.blade.php ENDPATH**/ ?>