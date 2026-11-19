<?php $__env->startSection('title', 'Payroll'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Payroll</h1>
    <a href="<?php echo e(route('hrm.payroll.create')); ?>" class="btn btn-primary">Compute Payroll</a>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2"><input type="month" name="month" value="<?php echo e(request('month')); ?>" class="form-control"></div>
    <div class="col-md-2"><select name="status" class="form-select"><option value="">All statuses</option><?php $__currentLoopData = ['draft','approved','paid']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Employee</th><th>Month</th><th class="text-end">Gross</th><th class="text-end">Deductions</th><th class="text-end">Net</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($item->employee->user->name ?? $item->employee->employee_code ?? $item->employee_id); ?></td><td><?php echo e($item->month); ?></td>
            <td class="text-end"><?php echo e(number_format($item->gross, 2)); ?></td>
            <td class="text-end"><?php echo e(number_format($item->deductions, 2)); ?></td>
            <td class="text-end"><?php echo e(number_format($item->net, 2)); ?></td>
            <td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $item->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td>
            <td class="text-end"><a href="<?php echo e(route('hrm.payroll.show', $item)); ?>" class="btn btn-sm btn-outline-primary">View</a></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No payroll records found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div></div>
<div class="mt-3"><?php echo e($items->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/hrm/payroll/index.blade.php ENDPATH**/ ?>