<?php $__env->startSection('title', __('Visa Documents')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('Visa Documents')); ?></h1>
<form method="GET" class="row g-2 mb-3"><div class="col-md-6"><input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="<?php echo e(__('Search file or tracking number')); ?>"></div><div class="col-auto"><button class="btn btn-primary"><?php echo e(__('Search')); ?></button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="<?php echo e(route('visa-register.documents')); ?>"><?php echo e(__('Clear')); ?></a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Document')); ?></th><th><?php echo e(__('Tracking')); ?></th><th><?php echo e(__('Candidate')); ?></th><th><?php echo e(__('Uploaded')); ?></th><th></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($record->original_name); ?></td><td><?php echo e($record->recruitment?->tracking_number ?? '—'); ?></td><td><?php echo e($record->recruitment?->passportHolder?->full_name ?? '—'); ?></td><td><?php echo e($record->created_at?->format('Y-m-d') ?? '—'); ?></td><td><?php if($record->recruitment?->visa): ?><?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('visas.show', $record->recruitment->visa)).'','variant' => 'secondary','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('visas.show', $record->recruitment->visa)).'','variant' => 'secondary','size' => 'sm']); ?><?php echo e(__('Open visa')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?><?php endif; ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5" class="text-center text-muted"><?php echo e(__('No visa documents found.')); ?></td></tr><?php endif; ?>
</tbody></table></div><?php echo e($records->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/visa-register/documents.blade.php ENDPATH**/ ?>