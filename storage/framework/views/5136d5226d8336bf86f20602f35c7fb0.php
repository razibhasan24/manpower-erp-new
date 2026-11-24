<?php $__env->startSection('title', __('Grading Criteria')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Custom Pagination Design */
        .custom-pagination .pagination {
            gap: 8px;
            margin-bottom: 0;
        }

        .custom-pagination .page-item .page-link {
            border-radius: 8px !important;
            border: 1px solid #e5e7eb;
            color: #374151;
            padding: 0.5rem 0.85rem;
            font-weight: 500;
            margin-left: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none !important;
        }

        .custom-pagination .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }

        .custom-pagination .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #ffffff;
            border-color: #e5e7eb;
        }

        .custom-pagination .page-link:hover:not(.active) {
            background-color: #f3f4f6;
            color: #374151;
        }

        /* ✅ FIX: Laravel এর ডিফল্ট Showing টেক্সট হাইড করা হচ্ছে */
        .custom-pagination nav>div>div:first-child {
            display: none !important;
        }

        /* ✅ FIX: পেজিনেশন বাটনগুলোকে ডান দিকে রাখার জন্য */
        .custom-pagination nav>div {
            justify-content: flex-end !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="mb-4">
        <h1 class="h3 mb-1"><?php echo e(__('Grading Criteria')); ?></h1>
        <p class="text-muted small mb-0">
            <?php echo e(__('Criteria are configurable here and snapshotted onto each selection decision, so later changes never rewrite past decisions.')); ?>

        </p>
    </div>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-plus-circle me-1"></i> <?php echo e(__('Add New Criterion')); ?></h6>
            <form action="<?php echo e(route('selections.criteria.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted"><?php echo e(__('Criterion Name')); ?> <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Interview Score"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted"><?php echo e(__('Max Score')); ?> <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="max_score" class="form-control" placeholder="e.g. 100"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted"><?php echo e(__('Weight')); ?></label>
                        <input type="number" step="0.01" name="weight" class="form-control" placeholder="e.g. 1.5">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <?php echo e(__('Add')); ?>

                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold"><?php echo e(__('Name')); ?></th>
                        <th class="py-3 fw-semibold"><?php echo e(__('Max Score')); ?></th>
                        <th class="py-3 fw-semibold"><?php echo e(__('Weight')); ?></th>
                        <th class="py-3 fw-semibold"><?php echo e(__('Status')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4 fw-medium text-dark"><?php echo e($criterion->name); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo e($criterion->max_score); ?></span></td>
                            <td>
                                <span class="badge bg-light text-dark border"><?php echo e($criterion->weight ?? '—'); ?></span>
                            </td>
                            <td>
                                
                                <?php if(is_string($criterion->status)): ?>
                                    <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $criterion->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($criterion->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $criterion->status ? 'active' : 'inactive']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($criterion->status ? 'active' : 'inactive')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        fill="currentColor" class="bi bi-list-check mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
                                    </svg>
                                    <h6 class="fw-bold mb-1"><?php echo e(__('No criteria found')); ?></h6>
                                    <p class="small mb-0"><?php echo e(__('Add a new criterion using the form above.')); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="card-body border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                Showing <?php echo e($criteria->firstItem() ?? 0); ?>–<?php echo e($criteria->lastItem() ?? 0); ?> of <?php echo e($criteria->total()); ?>

                results
            </div>
            <div class="custom-pagination">
                <?php echo e($criteria->appends(request()->query())->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/selections/criteria.blade.php ENDPATH**/ ?>