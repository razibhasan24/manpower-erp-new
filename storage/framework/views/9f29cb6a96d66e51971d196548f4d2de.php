<?php if(isset($createUrl) && auth()->user()?->can('post-arrival.create')): ?><div class="d-flex justify-content-end mb-3"><a class="btn btn-primary" href="<?php echo e($createUrl); ?>"><?php echo e(__('Create')); ?></a></div><?php endif; ?>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="<?php echo e(__('Search tracking or candidate')); ?>"></div><div class="col-md-3"><select name="status" class="form-select"><option value=""><?php echo e(__('All statuses')); ?></option><?php $__currentLoopData = ['pending','arrived','settled','issue_reported','completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(__(ucwords(str_replace('_', ' ', $status)))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><div class="col-auto"><button class="btn btn-primary"><?php echo e(__('Filter')); ?></button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="<?php echo e($clearUrl); ?>"><?php echo e(__('Clear')); ?></a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th><?php echo e(__('Arrival date')); ?></th><th><?php echo e(__('Tracking')); ?></th><th><?php echo e(__('Candidate')); ?></th><th><?php echo e($valueLabel); ?></th><th><?php echo e(__('Status')); ?></th><th><?php echo e(__('Action')); ?></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($record->arrival_date?->format('Y-m-d') ?? '—'); ?></td><td><?php echo e($record->recruitment?->tracking_number ?? '—'); ?></td><td><?php echo e($record->recruitment?->passportHolder?->full_name ?? '—'); ?></td><td><?php echo e($value($record) ?: '—'); ?></td><td><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td><td class="text-nowrap"><?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('post-arrivals.show', $record)).'','variant' => 'secondary','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('post-arrivals.show', $record)).'','variant' => 'secondary','size' => 'sm']); ?><?php echo e(__('View')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post-arrival.edit')): ?><a class="btn btn-sm btn-warning" href="<?php echo e(route('post-arrivals.edit', $record)); ?>"><?php echo e(__('Edit')); ?></a><?php if(!empty($isAirportPickup)): ?><form class="d-inline" method="POST" action="<?php echo e(route('airport-pickup.destroy', $record)); ?>" onsubmit="return confirm('<?php echo e(__('Remove airport pickup requirement? Arrival record will remain.')); ?>')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-danger"><?php echo e(__('Remove Pickup')); ?></button></form><?php endif; ?> <?php endif; ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="text-center text-muted"><?php echo e($emptyMessage); ?></td></tr><?php endif; ?>
</tbody></table></div><?php echo e($records->links()); ?>

<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/flight-support/_arrival-list.blade.php ENDPATH**/ ?>