<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0"><?php echo e($title); ?></h2>
            <div class="btn-group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('report.export')): ?>
                    <a href="<?php echo e(route('reports.pdf', ['report' => $report] + request()->query())); ?>" class="btn btn-outline-danger btn-sm">PDF</a>
                    <a href="<?php echo e(route('reports.excel', ['report' => $report] + request()->query())); ?>" class="btn btn-outline-success btn-sm">Excel</a>
                <?php endif; ?>
                <a href="<?php echo e(route('reports.print', ['report' => $report] + request()->query())); ?>" target="_blank" class="btn btn-outline-secondary btn-sm">Print</a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('reports.show', $report)); ?>" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label" for="date_from"><?php echo e(__('From')); ?></label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo e($filters['date_from']); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="date_to"><?php echo e(__('To')); ?></label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo e($filters['date_to']); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="country_id"><?php echo e(__('Country')); ?></label>
                        <select name="country_id" id="country_id" class="form-select">
                            <option value=""><?php echo e(__('All')); ?></option>
                            <?php $__currentLoopData = $filterOptions['countries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($country->id); ?>" <?php if($filters['country_id'] == $country->id): echo 'selected'; endif; ?>><?php echo e($country->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="branch_id"><?php echo e(__('Branch')); ?></label>
                        <select name="branch_id" id="branch_id" class="form-select">
                            <option value=""><?php echo e(__('All')); ?></option>
                            <?php $__currentLoopData = $filterOptions['branches']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch->id); ?>" <?php if($filters['branch_id'] == $branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="agent_id"><?php echo e(__('Agent')); ?></label>
                        <select name="agent_id" id="agent_id" class="form-select">
                            <option value=""><?php echo e(__('All')); ?></option>
                            <?php $__currentLoopData = $filterOptions['agents']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($agent->id); ?>" <?php if($filters['agent_id'] == $agent->id): echo 'selected'; endif; ?>><?php echo e($agent->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="status"><?php echo e(__('Status')); ?></label>
                        <input type="text" name="status" id="status" class="form-control" value="<?php echo e($filters['status']); ?>" placeholder="<?php echo e(__('Status')); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="search"><?php echo e(__('Search')); ?></label>
                        <input type="text" name="search" id="search" class="form-control" value="<?php echo e($filters['search']); ?>" placeholder="<?php echo e(__('Search…')); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo e(__('Filter')); ?></button>
                        <a href="<?php echo e(route('reports.show', $report)); ?>" class="btn btn-outline-secondary"><?php echo e(__('Reset')); ?></a>
                    </div>
                </form>
            </div>
        </div>

        
        <?php if(! empty($summary)): ?>
            <div class="row mb-4">
                <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 mb-2">
                        <div class="card">
                            <div class="card-body py-2">
                                <div class="text-muted small"><?php echo e($label); ?></div>
                                <div class="h5 mb-0"><?php echo e(is_array($value) ? collect($value)->map(fn ($v, $k) => $k.': '.$v)->implode(', ') : $value); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><?php echo e($title); ?></span>
                <span class="text-muted"><?php echo e(count($rows)); ?> <?php echo e(__('record(s)')); ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th><?php echo e($label); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <?php $__currentLoopData = array_keys($columns); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td><?php echo e($row[$key] ?? ''); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="<?php echo e(count($columns)); ?>" class="text-center text-muted py-4"><?php echo e(__('No records match the selected filters.')); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\up2\resources\views/reports/show.blade.php ENDPATH**/ ?>