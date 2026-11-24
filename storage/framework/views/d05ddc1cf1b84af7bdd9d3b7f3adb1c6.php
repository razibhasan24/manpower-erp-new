<?php $__env->startSection('title', __('Travel Agencies')); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1><?php echo e(__('Travel Agencies')); ?></h1><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('flight.create')): ?><a class="btn btn-primary" href="<?php echo e(route('travel-agencies.create')); ?>"><?php echo e(__('Create Travel Agency')); ?></a><?php endif; ?></div>
<?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="<?php echo e(__('Search name, phone or email')); ?>"></div><div class="col-md-3"><select name="status" class="form-select"><option value=""><?php echo e(__('All statuses')); ?></option><?php $__currentLoopData = ['active','inactive']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(__(ucfirst($status))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><div class="col-auto"><button class="btn btn-primary"><?php echo e(__('Filter')); ?></button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="<?php echo e(route('travel-agencies.index')); ?>"><?php echo e(__('Clear')); ?></a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Name')); ?></th><th><?php echo e(__('Phone')); ?></th><th><?php echo e(__('Email')); ?></th><th><?php echo e(__('Address')); ?></th><th><?php echo e(__('Status')); ?></th><th><?php echo e(__('Actions')); ?></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($record->name); ?></td><td><?php echo e($record->phone ?: '—'); ?></td><td><?php echo e($record->email ?: '—'); ?></td><td><?php echo e($record->address ?: '—'); ?></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $record->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($record->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td><td class="text-nowrap"><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('flight.edit')): ?><a class="btn btn-sm btn-warning" href="<?php echo e(route('travel-agencies.edit', $record)); ?>"><?php echo e(__('Edit')); ?></a><form class="d-inline" method="POST" action="<?php echo e(route('travel-agencies.destroy', $record)); ?>" onsubmit="return confirm('<?php echo e(__('Delete this travel agency? Existing flight references will be cleared.')); ?>')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-danger"><?php echo e(__('Delete')); ?></button></form><?php endif; ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="text-center text-muted"><?php echo e(__('No travel agencies found.')); ?></td></tr><?php endif; ?>
</tbody></table></div><?php echo e($records->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/flight-support/travel-agencies.blade.php ENDPATH**/ ?>