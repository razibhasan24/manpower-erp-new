<?php $__env->startSection('title', 'Trial Balance'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Trial Balance</h1>
            <p class="text-muted small mb-0">View and export the trial balance for a specific period.</p>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?php echo e(session('success')); ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                
                <div class="col-md-3">
                    <label for="from" class="form-label small fw-semibold text-muted">From Date</label>
                    <input type="date" name="from" id="from" value="<?php echo e($filters['from'] ?? ''); ?>"
                        class="form-control">
                </div>

                
                <div class="col-md-3">
                    <label for="to" class="form-label small fw-semibold text-muted">To Date</label>
                    <input type="date" name="to" id="to" value="<?php echo e($filters['to'] ?? ''); ?>"
                        class="form-control">
                </div>

                
                <div class="col-md-6 d-flex gap-2 justify-content-md-end align-items-center flex-wrap mt-3 mt-md-0">
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'primary','class' => 'btn btn-primary px-4','type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','class' => 'btn btn-primary px-4','type' => 'submit']); ?>
                        <i class="bi bi-funnel me-1"></i> Filter
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
                    <div class="vr mx-1 d-none d-md-block"></div>
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'danger','href' => ''.e(request()->fullUrlWithQuery(['export' => 'pdf'])).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'danger','href' => ''.e(request()->fullUrlWithQuery(['export' => 'pdf'])).'']); ?>
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'success','href' => ''.e(request()->fullUrlWithQuery(['export' => 'excel'])).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'success','href' => ''.e(request()->fullUrlWithQuery(['export' => 'excel'])).'']); ?>
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
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

    
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold" style="width: 120px;">Code</th>
                        <th class="py-3 fw-semibold">Account Name</th>
                        <th class="text-end py-3 fw-semibold" style="width: 180px;">Debit</th>
                        <th class="text-end pe-4 py-3 fw-semibold" style="width: 180px;">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4 fw-medium text-muted"><?php echo e($row['code']); ?></td>
                            <td class="fw-medium text-dark"><?php echo e($row['name']); ?></td>
                            
                            <td class="text-end text-danger fw-semibold">
                                <?php echo e($row['debit'] > 0 ? number_format($row['debit'], 2) : '—'); ?>

                            </td>
                            
                            <td class="text-end pe-4 text-success fw-semibold">
                                <?php echo e($row['credit'] > 0 ? number_format($row['credit'], 2) : '—'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-file-earmark-bar-graph mb-3 opacity-25"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M10 13.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-6a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm-2.5.5a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5zm-4 0a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5z" />
                                        <path
                                            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">No posted entries found</h6>
                                    <p class="small mb-0">There are no transactions in the selected period.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                
                <?php if(count($rows) > 0): ?>
                    <tfoot class="table-light border-top border-2">
                        <tr>
                            <th colspan="2" class="ps-4 py-3 fs-6 text-uppercase">Total</th>
                            <th class="text-end py-3 fs-6 text-danger"><?php echo e(number_format($total_debit, 2)); ?></th>
                            <th class="text-end pe-4 py-3 fs-6 text-success"><?php echo e(number_format($total_credit, 2)); ?></th>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>

    
    <div class="mt-4 d-flex justify-content-end">
        <?php if($balanced): ?>
            <div class="alert alert-success d-inline-flex align-items-center border-0 shadow-sm px-4 py-3 mb-0">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>
                    <strong class="d-block">Balanced</strong>
                    <span class="small opacity-75">Debit and Credit totals match perfectly.</span>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger d-inline-flex align-items-center border-0 shadow-sm px-4 py-3 mb-0">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>
                    <strong class="d-block">Out of Balance</strong>
                    <span class="small opacity-75">Investigate immediately! Total debit and credit do not match.</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/financial/trial-balance.blade.php ENDPATH**/ ?>