<?php $__env->startSection('title', 'Employees'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Employees</h1>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee.create')): ?><a href="<?php echo e(route('hrm.employees.create')); ?>" class="btn btn-primary">Add Employee</a><?php endif; ?>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Employee code, name or email..."></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">All statuses</option><?php $__currentLoopData = ['active','inactive','terminated']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Search</button></div>
    <div class="col-auto"><a href="<?php echo e(route('hrm.employees.index')); ?>" class="btn btn-outline-secondary">Clear</a></div>
</form>
<div class="card"><div class="table-responsive">
<table class="table table-striped mb-0">
    <thead><tr><th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><?php echo e($item->employee_code); ?></td><td><?php echo e($item->name ?: ($item->user?->name ?? '—')); ?></td><td><?php echo e($item->department?->name ?? '—'); ?></td><td><?php echo e($item->designation?->name ?? '—'); ?></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
            <td class="text-end">
                <a href="<?php echo e(route('hrm.employees.show', $item)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee.update')): ?><a href="<?php echo e(route('hrm.employees.edit', $item)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee.delete')): ?><form method="POST" action="<?php echo e(route('hrm.employees.destroy', $item)); ?>" class="d-inline" onsubmit="return confirm('Deactivate this employee?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger" type="submit">Deactivate</button></form><?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="99" class="text-center text-muted py-4">No records found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div></div>
<div class="mt-3"><?php echo e($items->links()); ?></div>
<p class="text-muted small"><?php echo e($items->total()); ?> record(s) found.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/hrm/employees/index.blade.php ENDPATH**/ ?>