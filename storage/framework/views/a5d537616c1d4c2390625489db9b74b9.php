<?php $__env->startSection('title', 'Edit Payment Mode'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">Edit Payment Mode</h1>
<div class="card"><div class="card-body">
    <?php echo $__env->make('accounting.payment-modes._form', ['action' => route('accounting.payment-modes.update', $item), 'method' => 'PUT'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/payment-modes/edit.blade.php ENDPATH**/ ?>