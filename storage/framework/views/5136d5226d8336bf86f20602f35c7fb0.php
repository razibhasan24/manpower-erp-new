<?php $__env->startSection('title', __('Grading Criteria')); ?>
<?php $__env->startSection('content'); ?>
<h1 class="mb-3"><?php echo e(__('Grading Criteria')); ?></h1>
<p class="text-muted"><?php echo e(__('Criteria are configurable here and snapshotted onto each selection decision, so later changes never rewrite past decisions.')); ?></p>
<form action="<?php echo e(route('selections.criteria.store')); ?>" method="POST" class="row g-2 mb-3"><?php echo csrf_field(); ?>
    <div class="col-md-4"><input type="text" name="name" class="form-control" placeholder="<?php echo e(__('Criterion name')); ?>" required></div>
    <div class="col-md-2"><input type="number" step="0.01" name="max_score" class="form-control" placeholder="<?php echo e(__('Max score')); ?>" required></div>
    <div class="col-md-2"><input type="number" step="0.01" name="weight" class="form-control" placeholder="<?php echo e(__('Weight')); ?>"></div>
    <div class="col-md-2"><?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?><?php echo e(__('Add Criterion')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?></div>
</form>
<div class="table-responsive"><table class="table table-striped"><thead><tr><th><?php echo e(__('Name')); ?></th><th><?php echo e(__('Max Score')); ?></th><th><?php echo e(__('Weight')); ?></th><th><?php echo e(__('Active')); ?></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($criterion->name); ?></td><td><?php echo e($criterion->max_score); ?></td><td><?php echo e($criterion->weight); ?></td><td><?php echo e($criterion->status); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="4" class="text-center text-muted"><?php echo e(__('No criteria yet.')); ?></td></tr><?php endif; ?>
</tbody></table></div>
<?php echo e($criteria->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/selections/criteria.blade.php ENDPATH**/ ?>