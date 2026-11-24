<?php $__env->startSection('title', 'Balance Sheet'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Balance Sheet</h1>
            <p class="text-muted small mb-0">A financial statement summarizing the company's assets, liabilities, and equity.
            </p>
        </div>
    </div>

    
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

    
    <div class="row g-4">
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-briefcase me-2 text-primary"></i> Assets</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4 text-muted small" style="width: 80px;"><?php echo e($row['code']); ?></td>
                                    <td class="fw-medium text-dark"><?php echo e($row['name']); ?></td>
                                    <td class="text-end pe-4 fw-semibold text-dark"><?php echo e(number_format($row['amount'], 2)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No assets found for this period.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light border-top border-2">
                            <tr>
                                <th colspan="2" class="ps-4 py-3 text-uppercase fs-6">Total Assets</th>
                                <th class="text-end pe-4 py-3 text-primary fs-5"><?php echo e(number_format($total_assets, 2)); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-wallet2 me-2 text-success"></i> Liabilities & Equity</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            
                            <?php $__currentLoopData = $liabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-4 text-muted small" style="width: 80px;"><?php echo e($row['code']); ?></td>
                                    <td class="fw-medium text-dark"><?php echo e($row['name']); ?></td>
                                    <td class="text-end pe-4 fw-semibold text-dark"><?php echo e(number_format($row['amount'], 2)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php $__currentLoopData = $equity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-4 text-muted small" style="width: 80px;"><?php echo e($row['code']); ?></td>
                                    <td class="fw-medium text-dark"><?php echo e($row['name']); ?></td>
                                    <td class="text-end pe-4 fw-semibold text-dark"><?php echo e(number_format($row['amount'], 2)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <tr class="bg-light bg-opacity-50">
                                <td class="ps-4 text-muted small" style="width: 80px;">—</td>
                                <td class="fw-bold text-dark">Net Profit</td>
                                <td class="text-end pe-4 fw-bold <?php echo e($net_profit >= 0 ? 'text-success' : 'text-danger'); ?>">
                                    <?php echo e(number_format($net_profit, 2)); ?>

                                </td>
                            </tr>

                            <?php if(empty($liabilities) && empty($equity)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No liabilities or equity found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light border-top border-2">
                            <tr>
                                <th colspan="2" class="ps-4 py-3 text-uppercase fs-6">Total Liabilities & Equity</th>
                                <th class="text-end pe-4 py-3 text-success fs-5">
                                    <?php echo e(number_format($total_liabilities_equity, 2)); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="mt-4 d-flex justify-content-end">
        <?php if($balanced): ?>
            <div class="alert alert-success d-inline-flex align-items-center border-0 shadow-sm px-4 py-3 mb-0">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <strong class="d-block fs-6">Balanced</strong>
                    <span class="small opacity-75">Total Assets match Total Liabilities & Equity.</span>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger d-inline-flex align-items-center border-0 shadow-sm px-4 py-3 mb-0">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong class="d-block fs-6">Out of Balance</strong>
                    <span class="small opacity-75">Investigate immediately! Totals do not match.</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/financial/balance-sheet.blade.php ENDPATH**/ ?>