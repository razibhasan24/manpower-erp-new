<?php $__env->startSection('title', __('Country-wise Workflow Settings')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('Country-wise Workflow Settings')); ?></h1>
<div class="alert alert-info"><strong><?php echo e(__('Shared workflow is active')); ?></strong><br><?php echo e(__('All countries currently use the same recruitment workflow and stage rules. Per-country overrides are not configured in the application yet.')); ?></div>

<h2 class="h5 mt-4"><?php echo e(__('Workflow stages')); ?></h2>
<div class="table-responsive mb-4"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Order')); ?></th><th><?php echo e(__('Stage')); ?></th><th><?php echo e(__('Available statuses')); ?></th></tr></thead><tbody>
<?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($index + 1); ?></td><td><?php echo e(__(\Illuminate\Support\Str::headline($stage))); ?></td><td><?php echo e(collect($stageStatuses[$stage] ?? [])->map(fn ($status) => __(\Illuminate\Support\Str::headline($status)))->join(', ')); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody></table></div>

<h2 class="h5"><?php echo e(__('Countries using this workflow')); ?></h2>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="<?php echo e(__('Search country or code')); ?>"></div><div class="col-auto"><button class="btn btn-primary"><?php echo e(__('Search')); ?></button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="<?php echo e(route('country-workflow-settings.index')); ?>"><?php echo e(__('Clear')); ?></a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Country')); ?></th><th><?php echo e(__('Code')); ?></th><th><?php echo e(__('Status')); ?></th><th><?php echo e(__('Recruitments')); ?></th><th><?php echo e(__('Workflow')); ?></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($country->name); ?></td><td><?php echo e($country->code ?: '—'); ?></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $country->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($country->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></td><td><?php echo e($country->recruitments_count); ?></td><td><?php echo e(__('Shared 15-stage workflow')); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5" class="text-center text-muted"><?php echo e(__('No countries found.')); ?></td></tr><?php endif; ?>
</tbody></table></div><?php echo e($countries->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/country-administration/workflow-settings.blade.php ENDPATH**/ ?>