<?php $__env->startSection('title', __('Visitor Passes')); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="mb-0"><?php echo e(__('Visitor Passes')); ?></h1>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('visitor.create')): ?>
        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('visitors.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('visitors.create')).'']); ?><?php echo e(__('Register visitor')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
    <?php endif; ?>
</div>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5"><input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="<?php echo e(__('Pass number, visitor or phone')); ?>"></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value=""><?php echo e(__('All pass statuses')); ?></option><?php $__currentLoopData = ['issued','returned','lost']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(__(ucfirst($status))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div class="col-auto"><button class="btn btn-primary"><?php echo e(__('Filter')); ?></button></div>
    <div class="col-auto"><a class="btn btn-outline-secondary" href="<?php echo e(route('visitor-passes.index')); ?>"><?php echo e(__('Clear')); ?></a></div>
</form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Pass number')); ?></th><th><?php echo e(__('Visitor')); ?></th><th><?php echo e(__('Phone')); ?></th><th><?php echo e(__('Purpose')); ?></th><th><?php echo e(__('Issued')); ?></th><th><?php echo e(__('Returned')); ?></th><th><?php echo e(__('Status')); ?></th><th><?php echo e(__('Actions')); ?></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($record->pass_number); ?></td><td><?php echo e($record->visitor?->name ?? '—'); ?></td><td><?php echo e($record->visitor?->phone ?? '—'); ?></td><td><?php echo e(\Illuminate\Support\Str::limit($record->visitor?->purpose, 80) ?: '—'); ?></td><td><?php echo e($record->issued_at?->format('Y-m-d H:i') ?? '—'); ?></td><td><?php echo e($record->returned_at?->format('Y-m-d H:i') ?? '—'); ?></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td><td class="text-nowrap"><?php if($record->visitor): ?><a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('visitors.pass', $record->visitor)); ?>"><?php echo e(__('Print pass')); ?></a><a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('visitors.show', $record->visitor)); ?>"><?php echo e(__('Visitor')); ?></a><?php endif; ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="8" class="text-center text-muted"><?php echo e(__('No visitor passes found.')); ?></td></tr><?php endif; ?>
</tbody></table></div>
<?php echo e($records->links()); ?>

<p class="text-muted small"><?php echo e(__(':count passes', ['count' => $records->total()])); ?></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/visitors/passes.blade.php ENDPATH**/ ?>