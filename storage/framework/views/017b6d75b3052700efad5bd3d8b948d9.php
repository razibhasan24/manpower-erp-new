<?php $__env->startSection('title', __('Recruitment Tracking') . ' — ' . \App\Services\BrandingService::appName()); ?>

<?php
    $stagePositions = array_flip($stages);
?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => __('Recruitment Tracking'),'subtitle' => __('A read-only view of the current workflow stage for every visible registration.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Recruitment Tracking')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('A read-only view of the current workflow stage for every visible registration.'))]); ?>
         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('recruitments.index')); ?>" class="btn btn-outline-secondary btn-sm"><?php echo e(__('All registrations')); ?></a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('recruitment.create')): ?>
                <a href="<?php echo e(route('recruitments.create')); ?>" class="btn btn-primary btn-sm"><?php echo e(__('New registration')); ?></a>
            <?php endif; ?>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $attributes = $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $component = $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>

    <form method="GET" action="<?php echo e(route('recruitments.tracking')); ?>" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label"><?php echo e(__('Search')); ?></label>
                    <input type="search" name="search" id="search" class="form-control" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Tracking number')); ?>">
                </div>
                <div class="col-md-4">
                    <label for="current_stage" class="form-label"><?php echo e(__('Stage')); ?></label>
                    <select name="current_stage" id="current_stage" class="form-select">
                        <option value=""><?php echo e(__('All stages')); ?></option>
                        <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($stage); ?>" <?php if(request('current_stage') === $stage): echo 'selected'; endif; ?>><?php echo e(ucwords(str_replace('_', ' ', $stage))); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><?php echo e(__('Filter')); ?></button>
                    <a href="<?php echo e(route('recruitments.tracking')); ?>" class="btn btn-outline-secondary"><?php echo e(__('Clear')); ?></a>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><?php echo e(__('Tracking board')); ?></span>
            <span class="small text-secondary"><?php echo e($items->total()); ?> <?php echo e(__('registrations')); ?></span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th><?php echo e(__('Tracking')); ?></th>
                        <th><?php echo e(__('Candidate')); ?></th>
                        <th><?php echo e(__('Country')); ?></th>
                        <th><?php echo e(__('Stage')); ?></th>
                        <th style="min-width: 170px;"><?php echo e(__('Progress')); ?></th>
                        <th><?php echo e(__('Status')); ?></th>
                        <th class="text-end"><?php echo e(__('Action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $currentIndex = $stagePositions[$item->current_stage] ?? 0;
                        ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($item->tracking_number); ?></td>
                            <td><?php echo e($item->passportHolder?->full_name ?? '—'); ?></td>
                            <td><?php echo e($item->country?->name ?? '—'); ?></td>
                            <td><?php echo e(ucwords(str_replace('_', ' ', $item->current_stage))); ?></td>
                            <td>
                                <div class="workflow-progress" role="img" aria-label="<?php echo e(__('Stage :current of :total', ['current' => $currentIndex + 1, 'total' => count($stages)])); ?>">
                                    <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="<?php echo e($index < $currentIndex ? 'is-done' : ''); ?> <?php echo e($index === $currentIndex ? 'is-current' : ''); ?>" title="<?php echo e(ucwords(str_replace('_', ' ', $stage))); ?>"></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <span class="small text-secondary"><?php echo e($currentIndex + 1); ?>/<?php echo e(count($stages)); ?></span>
                            </td>
                            <td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $item->current_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->current_status)]); ?>
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
                                <a href="<?php echo e(route('recruitments.show', $item)); ?>" class="btn btn-sm btn-outline-primary"><?php echo e(__('View')); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4"><?php echo e(__('No registrations match the filter.')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3"><?php echo e($items->links()); ?></div>

    <p class="small text-secondary mt-2 mb-0"><?php echo e(__('Stage changes are performed only through the workflow actions on each registration record and are fully audited.')); ?></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/recruitments/tracking.blade.php ENDPATH**/ ?>