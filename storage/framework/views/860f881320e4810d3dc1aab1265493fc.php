
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
    $hasStatusFilter = collect($filters)->contains(fn ($filter) => ($filter['name'] ?? null) === 'status');
?>

<?php if($hasSlot): ?>
    <div class="card mb-3">
        <div class="card-body">
            <?php echo e($slot); ?>

        </div>
    </div>
<?php else: ?>
    <form method="<?php echo e($method); ?>" action="<?php echo e($action ?? url()->current()); ?>" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <?php if($search): ?>
                    <div class="col-md-3">
                        <label for="filter-search" class="form-label"><?php echo e(__('Search')); ?></label>
                        <input type="search" name="search" id="filter-search" class="form-control" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Search...')); ?>">
                    </div>
                <?php endif; ?>

                <?php if(! empty($statuses) && ! $hasStatusFilter): ?>
                    <div class="col-md-3">
                        <label for="filter-status" class="form-label"><?php echo e(__('Status')); ?></label>
                        <select name="status" id="filter-status" class="form-select">
                            <option value=""><?php echo e(__('All statuses')); ?></option>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucwords(str_replace('_', ' ', $status))); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3">
                        <label for="filter-<?php echo e($filter['name']); ?>" class="form-label"><?php echo e($filter['label']); ?></label>
                        <?php if(($filter['type'] ?? 'text') === 'select'): ?>
                            <select name="<?php echo e($filter['name']); ?>" id="filter-<?php echo e($filter['name']); ?>" class="form-select">
                                <option value=""><?php echo e(__('All')); ?></option>
                                <?php $__currentLoopData = $filter['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e((string) request($filter['name'], $filter['value'] ?? '') === (string) $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php else: ?>
                            <input type="<?php echo e($filter['type'] ?? 'text'); ?>" name="<?php echo e($filter['name']); ?>" id="filter-<?php echo e($filter['name']); ?>" class="form-control" value="<?php echo e(request($filter['name'], $filter['value'] ?? '')); ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><?php echo e(__('Filter')); ?></button>
                    <a href="<?php echo e($action ?? url()->current()); ?>" class="btn btn-outline-secondary"><?php echo e(__('Clear')); ?></a>
                </div>
            </div>
        </div>
    </form>
<?php endif; ?>
<?php /**PATH R:\xampp\htdocs\up2\resources\views/components/filter-bar.blade.php ENDPATH**/ ?>