
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['action' => null, 'method' => 'GET', 'filters' => [], 'search' => true, 'statuses' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['action' => null, 'method' => 'GET', 'filters' => [], 'search' => true, 'statuses' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $hasSlot = trim((string) $slot) !== '';
    $hasStatusFilter = collect($filters)->contains(fn($filter) => ($filter['name'] ?? null) === 'status');
?>

<?php if($hasSlot): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <?php echo e($slot); ?>

        </div>
    </div>
<?php else: ?>
    <form method="<?php echo e($method); ?>" action="<?php echo e($action ?? url()->current()); ?>" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">

                
                <?php if($search): ?>
                    <div class="col-md-4 col-lg-4">
                        <label for="filter-search"
                            class="form-label small fw-semibold text-muted"><?php echo e(__('Search')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" name="search" id="filter-search"
                                class="form-control border-start-0 ps-0" value="<?php echo e(request('search')); ?>"
                                placeholder="<?php echo e(__('Search...')); ?>">
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if(!empty($statuses) && !$hasStatusFilter): ?>
                    <div class="col-md-3 col-lg-2">
                        <label for="filter-status"
                            class="form-label small fw-semibold text-muted"><?php echo e(__('Status')); ?></label>
                        <select name="status" id="filter-status" class="form-select">
                            <option value=""><?php echo e(__('All statuses')); ?></option>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>>
                                    <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>

                
                <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 col-lg-2">
                        <label for="filter-<?php echo e($filter['name']); ?>"
                            class="form-label small fw-semibold text-muted"><?php echo e($filter['label']); ?></label>
                        <?php if(($filter['type'] ?? 'text') === 'select'): ?>
                            <select name="<?php echo e($filter['name']); ?>" id="filter-<?php echo e($filter['name']); ?>"
                                class="form-select">
                                <option value=""><?php echo e(__('All')); ?></option>
                                <?php $__currentLoopData = $filter['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>"
                                        <?php echo e((string) request($filter['name'], $filter['value'] ?? '') === (string) $value ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php else: ?>
                            <input type="<?php echo e($filter['type'] ?? 'text'); ?>" name="<?php echo e($filter['name']); ?>"
                                id="filter-<?php echo e($filter['name']); ?>" class="form-control"
                                value="<?php echo e(request($filter['name'], $filter['value'] ?? '')); ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <div class="col-auto ms-auto d-flex gap-2">
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'primary','class' => 'btn btn-primary px-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','class' => 'btn btn-primary px-4']); ?>
                        <i class="bi bi-funnel me-1"></i> <?php echo e(__('Filter')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e($action ?? url()->current()).'','variant' => 'danger','class' => 'btn btn-danger px-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($action ?? url()->current()).'','variant' => 'danger','class' => 'btn btn-danger px-3']); ?>
                        <i class="bi bi-arrow-counterclockwise me-1"></i> <?php echo e(__('Clear')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </form>
<?php endif; ?>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/components/filter-bar.blade.php ENDPATH**/ ?>