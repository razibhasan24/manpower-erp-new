<?php $__env->startSection('title', __('Employer Information')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('Employer Information')); ?></h1>
<?php echo $__env->make('flight-support._arrival-list', ['clearUrl' => route('employer-information.index'), 'valueLabel' => __('Employer contact'), 'value' => fn ($record) => $record->employer_contact, 'emptyMessage' => __('No employer information found.')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/flight-support/employer-information.blade.php ENDPATH**/ ?>