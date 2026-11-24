<?php $__env->startSection('title', __('Selection & Grading')); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1"><?php echo e(__('Selection & Grading')); ?></h1>
            <p class="text-muted small mb-0">View selection details, record decisions, and manage grading status.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('selection.edit')): ?>
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('selections.edit', $record)).'','variant' => 'primary','class' => 'px-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('selections.edit', $record)).'','variant' => 'primary','class' => 'px-3']); ?>
                    <?php echo e(__('Edit')); ?>

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
            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('selections.index')).'','variant' => 'dark','class' => 'px-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('selections.index')).'','variant' => 'dark','class' => 'px-3']); ?>
                <?php echo e(__('Back to List')); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','variant' => 'danger','dataBsToggle' => 'modal','dataBsTarget' => '#cancel-modal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'danger','data-bs-toggle' => 'modal','data-bs-target' => '#cancel-modal']); ?>
                <?php echo e(__('Cancel Record')); ?>

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

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?php echo e(session('success')); ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1"><?php echo e(__('Status')); ?></div>
                    <div><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></div>
                </div>

                
                <div class="col-md-6 col-lg-8">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Recruitment</div>
                    <div class="fw-medium text-dark">
                        <?php echo e($record->recruitment?->tracking_number ?? '—'); ?>

                        <span class="text-muted">— <?php echo e($record->recruitment?->passportHolder?->full_name ?? ''); ?></span>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Grade</div>
                    <div class="fw-bold text-primary fs-5"><?php echo e($record->grade ?? '—'); ?></div>
                </div>

                
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Decided At</div>
                    <div>
                        <?php echo e($record->decided_at ? \Carbon\Carbon::parse($record->decided_at)->format('d M, Y h:i A') : '—'); ?>

                    </div>
                </div>

                
                <div class="col-12 mt-2">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Remarks</div>
                    <div class="bg-light p-3 rounded border"><?php echo e($record->remarks ?? __('No remarks provided.')); ?></div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($recruitment): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="mb-0"><?php echo e(__('Financial Responsibility')); ?></h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="text-muted small text-uppercase fw-semibold mb-1"><?php echo e(__('Responsible Party')); ?></div>
                        <div class="fw-medium text-dark">
                            <?php echo e(ucwords(str_replace('_', ' ', $recruitment->financial_responsible_type ?? __('Undecided')))); ?>

                            <span class="text-muted">(<?php echo e($recruitment->financial_responsible_id ?? '—'); ?>)</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="text-muted small text-uppercase fw-semibold mb-1"><?php echo e(__('Total Cost')); ?></div>
                        <div><?php echo e(number_format((float) ($recruitment->total_cost ?? 0), 2)); ?></div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="text-muted small text-uppercase fw-semibold mb-1"><?php echo e(__('Advance')); ?></div>
                        <div><?php echo e(number_format((float) ($recruitment->advance_amount ?? 0), 2)); ?></div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="text-muted small text-uppercase fw-semibold mb-1"><?php echo e(__('Remaining Due')); ?></div>
                        <div class="fw-bold text-danger"><?php echo e(number_format((float) ($recruitment->remaining_due ?? 0), 2)); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?php echo e(__('Record Selection Decision')); ?></h5>
            <a href="<?php echo e(route('selections.criteria')); ?>" class="text-decoration-none small fw-medium">
                <i class="bi bi-sliders me-1"></i> <?php echo e(__('Manage grading criteria')); ?>

            </a>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('selections.decide', $record)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="recruitment_id" value="<?php echo e($record->recruitment_id); ?>">

                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Grade <span class="text-danger">*</span></label>
                        <input type="text" name="grade" class="form-control" placeholder="<?php echo e(__('e.g. A+')); ?>"
                            value="<?php echo e($record->grade); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php if($record->status == $status): echo 'selected'; endif; ?>>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Responsible Type</label>
                        <select name="financial_responsible_type" class="form-select">
                            <option value="agent" <?php if($record->recruitment?->financial_responsible_type == 'agent'): echo 'selected'; endif; ?>><?php echo e(__('Agent')); ?></option>
                            <option value="passport_holder" <?php if($record->recruitment?->financial_responsible_type == 'passport_holder'): echo 'selected'; endif; ?>><?php echo e(__('Passport Holder')); ?>

                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Responsible ID</label>
                        <input type="number" name="financial_responsible_id" class="form-control"
                            placeholder="<?php echo e(__('ID')); ?>"
                            value="<?php echo e($record->recruitment?->financial_responsible_id); ?>">
                    </div>
                    <div class="col-12 mt-4">
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'primary','class' => 'px-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','class' => 'px-4']); ?><?php echo e(__('Save Decision')); ?> <?php echo $__env->renderComponent(); ?>
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
            </form>
        </div>
    </div>

    
    <?php if($recruitment): ?>
        <div class="mb-4">
            <h5 class="border-bottom pb-2 mb-3 text-secondary"><?php echo e(__('Workflow History')); ?></h5>
            <?php if (isset($component)) { $__componentOriginalfed8860d116b3f02bb30c55071819700 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfed8860d116b3f02bb30c55071819700 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workflow-timeline','data' => ['recruitment' => $recruitment]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workflow-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['recruitment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recruitment)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfed8860d116b3f02bb30c55071819700)): ?>
<?php $attributes = $__attributesOriginalfed8860d116b3f02bb30c55071819700; ?>
<?php unset($__attributesOriginalfed8860d116b3f02bb30c55071819700); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfed8860d116b3f02bb30c55071819700)): ?>
<?php $component = $__componentOriginalfed8860d116b3f02bb30c55071819700; ?>
<?php unset($__componentOriginalfed8860d116b3f02bb30c55071819700); ?>
<?php endif; ?>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0"><?php echo e(__('Change Status (Quick Update)')); ?></h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('selections.status', $record)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">New Status</label>
                        <select name="status" class="form-select">
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php if($record->status == $status): echo 'selected'; endif; ?>>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Remarks</label>
                        <input type="text" name="remarks" class="form-control"
                            placeholder="<?php echo e(__('Add a short remark (optional)')); ?>">
                    </div>
                    <div class="col-md-3">
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'success','class' => 'w-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'success','class' => 'w-100']); ?><?php echo e(__('Update Status')); ?> <?php echo $__env->renderComponent(); ?>
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
            </form>
        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'cancel-modal','title' => ''.e(__('Cancel Record')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'cancel-modal','title' => ''.e(__('Cancel Record')).'']); ?>
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3"
                style="width: 60px; height: 60px;">
                <i class="bi bi-exclamation-triangle fs-2"></i>
            </div>
            <h5 class="mb-1">Are you sure?</h5>
            <p class="text-muted small"><?php echo e(__('This cancels (archives) the record. It is not permanently deleted.')); ?></p>
        </div>
        <form action="<?php echo e(route('selections.destroy', $record)); ?>" method="POST"
            class="d-flex justify-content-center gap-2">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Keep it')); ?></button>
            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'danger']); ?><?php echo e(__('Confirm Cancel')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/selections/show.blade.php ENDPATH**/ ?>