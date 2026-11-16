

<?php $__env->startSection('title', 'Branches'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .branch-page {
            padding-top: 15px;
            padding-bottom: 25px;
        }

        .branch-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 22px;
        }

        .branch-title {
            font-size: 23px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .branch-page .card {
            border: 1px solid #e5eaf0;
            border-radius: 11px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .045);
            overflow: hidden;
        }

        .branch-page .card-body {
            padding: 20px;
        }

        .branch-page .form-control,
        .branch-page .form-select {
            min-height: 40px;
            border-radius: 7px;
            border: 1px solid #dce2e9;
            font-size: 13px;
            box-shadow: none;
        }

        .branch-page .form-control:focus,
        .branch-page .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
        }

        .branch-page .btn {
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 13px;
            transition: all .2s ease;
        }

        .branch-page .btn:hover {
            transform: translateY(-1px);
        }

        .branch-info {
            color: #64748b;
            font-size: 13px;
            margin: 18px 0 12px;
        }

        .branch-page .table {
            margin-bottom: 0;
        }

        .branch-page .table thead th {
            background: #f1f5f9;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            padding: 14px 15px;
            white-space: nowrap;
            border-bottom: 1px solid #e2e8f0;
        }

        .branch-page .table tbody td {
            padding: 13px 15px;
            font-size: 13px;
            color: #475569;
            vertical-align: middle;
            border-color: #edf0f4;
        }

        .branch-page .table tbody tr:hover {
            background: #f8fafc;
        }

        .branch-actions {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 5px;
        }

        .branch-actions .btn {
            white-space: nowrap;
        }

        .branch-pagination {
            margin-top: 20px;
        }

        @media (max-width: 767px) {
            .branch-title {
                font-size: 19px;
            }

            .branch-header {
                align-items: flex-start;
            }

            .branch-page .card-body {
                padding: 14px;
            }

            .branch-page .table {
                min-width: 750px;
            }

            .branch-header .btn {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid branch-page">

        
        <div class="branch-header">
            <div>
                <h1 class="branch-title">
                    <i class="fas fa-building text-primary me-2"></i>
                    Branches
                </h1>
                <p class="text-muted small mb-0 mt-1">
                    Manage and monitor your company branches.
                </p>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch.create')): ?>
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'success','href' => ''.e(route('branches.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'success','href' => ''.e(route('branches.create')).'']); ?>
                    <i class="fas fa-plus me-1"></i> New Branch
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
            <?php endif; ?>
        </div>

        
        <?php if (isset($component)) { $__componentOriginale9f22847d79d6273acb27aff60f1f678 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale9f22847d79d6273acb27aff60f1f678 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.filter-bar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('branches.index')); ?>" class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label class="form-label fw-semibold small">Search Branch</label>
                        <input type="search" name="search" value="<?php echo e(request('search')); ?>" class="form-control"
                            placeholder="Search by name, code or phone...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All statuses</option>
                            <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>
                                Active
                            </option>
                            <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Actions</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>

                            <a href="<?php echo e(route('branches.index')); ?>" class="btn btn-outline-secondary flex-fill">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>

                </form>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale9f22847d79d6273acb27aff60f1f678)): ?>
<?php $attributes = $__attributesOriginale9f22847d79d6273acb27aff60f1f678; ?>
<?php unset($__attributesOriginale9f22847d79d6273acb27aff60f1f678); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale9f22847d79d6273acb27aff60f1f678)): ?>
<?php $component = $__componentOriginale9f22847d79d6273acb27aff60f1f678; ?>
<?php unset($__componentOriginale9f22847d79d6273acb27aff60f1f678); ?>
<?php endif; ?>

        
        <div class="branch-info">
            <i class="fas fa-database me-1"></i>
            Showing <strong><?php echo e($items->total()); ?></strong> branches found.
        </div>

        
        <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <table class="table table-striped table-hover mb-0">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Phone</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th style="min-width: 220px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>

                            <td>
                                <div class="fw-semibold text-dark">
                                    <i class="fas fa-building text-muted me-1"></i>
                                    <?php echo e($item->name); ?>

                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?php echo e($item->code); ?>

                                </span>
                            </td>

                            <td><?php echo e($item->phone ?? '—'); ?></td>

                            <td><?php echo e($item->manager?->name ?? '—'); ?></td>

                            <td>
                                <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($item->status); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                            </td>

                            <td>
                                <div class="branch-actions">

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch.view')): ?>
                                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('branches.show', $item)).'','variant' => 'primary','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('branches.show', $item)).'','variant' => 'primary','size' => 'sm']); ?>
                                            <i class="fas fa-eye"></i> View
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
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch.edit')): ?>
                                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('branches.edit', $item)).'','variant' => 'warning','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('branches.edit', $item)).'','variant' => 'warning','size' => 'sm']); ?>
                                            <i class="fas fa-edit"></i> Edit
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
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch.delete')): ?>
                                        <button type="button" class="btn btn-danger btn-sm delete-branch-btn"
                                            data-action="<?php echo e(route('branches.destroy', $item)); ?>"
                                            data-name="<?php echo e($item->name); ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    <?php endif; ?>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-2x d-block mb-3"></i>
                                <strong>No branches found.</strong>
                                <div class="small mt-1">
                                    Adjust the filters or create a new branch.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>

        
        <div class="branch-pagination">
            <?php echo e($items->links()); ?>

        </div>

    </div>

<?php $__env->stopSection(); ?>



<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.delete-branch-btn').forEach(function(button) {

                button.addEventListener('click', function() {

                    const action = this.dataset.action;
                    const branchName = this.dataset.name;

                    Swal.fire({
                        title: 'Are you sure?',
                        html: 'You are about to delete <strong>' +
                            branchName +
                            '</strong>.<br><small>This action cannot be undone!</small>',

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',

                        confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
                        cancelButtonText: '<i class="fas fa-times"></i> Cancel',

                        reverseButtons: true,
                        focusCancel: true,

                        customClass: {
                            popup: 'rounded-4',
                            confirmButton: 'rounded-3 px-4',
                            cancelButton: 'rounded-3 px-4'
                        }

                    }).then((result) => {

                        if (result.isConfirmed) {

                            const form = document.createElement('form');

                            form.method = 'POST';
                            form.action = action;

                            const csrfToken = document.querySelector(
                                'meta[name="csrf-token"]'
                            )?.getAttribute('content');

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken || '<?php echo e(csrf_token()); ?>';

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';

                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);

                            document.body.appendChild(form);

                            form.submit();
                        }

                    });

                });

            });

        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/branches/index.blade.php ENDPATH**/ ?>