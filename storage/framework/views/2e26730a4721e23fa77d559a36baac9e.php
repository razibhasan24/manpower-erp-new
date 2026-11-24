<?php $__env->startSection('title', 'Account Type'); ?>
<?php $__env->startSection('content'); ?>
    <h1 class="mb-3"><?php echo e($item->name); ?></h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tr>
                    <th style="width:200px">Name</th>
                    <td><?php echo e($item->name); ?></td>
                </tr>
                <tr>
                    <th>Normal Balance</th>
                    <td><?php echo e($item->normal_balance); ?></td>
                </tr>
            </table>
            <h5 class="mt-4">Accounts</h5>
            <ul>
                <?php $__currentLoopData = $item->accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($account->code); ?> — <?php echo e($account->name); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/accounting/account-types/show.blade.php ENDPATH**/ ?>