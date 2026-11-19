<?php $__env->startSection('title', 'Income Statement'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3">Income Statement</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="from" value="<?php echo e($filters['from'] ?? ''); ?>" class="form-control" placeholder="From"></div>
    <div class="col-md-2"><input type="date" name="to" value="<?php echo e($filters['to'] ?? ''); ?>" class="form-control" placeholder="To"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
    <div class="col-auto">
        <a class="btn btn-outline-primary" href="<?php echo e(request()->fullUrlWithQuery(['export' => 'pdf'])); ?>">Export PDF</a>
        <a class="btn btn-outline-primary" href="<?php echo e(request()->fullUrlWithQuery(['export' => 'excel'])); ?>">Export Excel</a>
    </div>
</form>

<div class="row">
    <div class="col-md-6"><div class="card mb-3"><div class="card-header">Income</div><div class="card-body">
        <?php $__currentLoopData = $income; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="d-flex justify-content-between"><span><?php echo e($row['code']); ?> — <?php echo e($row['name']); ?></span><span><?php echo e(number_format($row['amount'], 2)); ?></span></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <hr><div class="d-flex justify-content-between fw-bold"><span>Total Income</span><span><?php echo e(number_format($total_income, 2)); ?></span></div>
    </div></div></div>
    <div class="col-md-6"><div class="card mb-3"><div class="card-header">Expenses</div><div class="card-body">
        <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="d-flex justify-content-between"><span><?php echo e($row['code']); ?> — <?php echo e($row['name']); ?></span><span><?php echo e(number_format($row['amount'], 2)); ?></span></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <hr><div class="d-flex justify-content-between fw-bold"><span>Total Expenses</span><span><?php echo e(number_format($total_expenses, 2)); ?></span></div>
    </div></div></div>
</div>
<div class="card"><div class="card-body d-flex justify-content-between fw-bold"><span>Net Profit</span><span><?php echo e(number_format($net_profit, 2)); ?></span></div></div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/financial/income-statement.blade.php ENDPATH**/ ?>