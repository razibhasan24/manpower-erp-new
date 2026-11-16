


<?php $__env->startSection('content'); ?>

    <style>
        .report-page {
            padding: 20px 0 30px;
            color: #263445;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 22px;
        }

        .report-title {
            font-size: 23px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .report-subtitle {
            color: #64748b;
            font-size: 13px;
            margin-top: 5px;
        }

        .report-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .report-page .btn {
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 15px;
            transition: all .2s ease;
        }

        .report-page .btn:hover {
            transform: translateY(-1px);
        }

        .report-page .card {
            border: 1px solid #e5eaf0;
            border-radius: 11px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .045);
            overflow: hidden;
            background: #fff;
        }

        .report-filter-card {
            margin-bottom: 22px;
        }

        .report-section-header {
            background: #fff;
            border-bottom: 1px solid #edf0f4;
            padding: 16px 20px;
            font-size: 15px;
            font-weight: 700;
            color: #263445;
        }

        .report-page .card-body {
            padding: 20px;
        }

        .report-page .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 7px;
        }

        .report-page .form-control,
        .report-page .form-select {
            min-height: 40px;
            border: 1px solid #dce2e9;
            border-radius: 7px;
            font-size: 13px;
            color: #334155;
            box-shadow: none;
        }

        .report-page .form-control:focus,
        .report-page .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
        }

        .report-filter-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .report-summary-card {
            height: 100%;
            border-left: 4px solid #3b82f6 !important;
            transition: all .2s ease;
        }

        .report-summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(15, 23, 42, .08);
        }

        .report-summary-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 9px;
        }

        .report-summary-value {
            font-size: 21px;
            font-weight: 700;
            color: #1e293b;
            word-break: break-word;
        }

        .report-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 17px 20px;
            background: #fff;
            border-bottom: 1px solid #e9edf2;
        }

        .report-table-title {
            font-size: 15px;
            font-weight: 700;
            color: #263445;
            margin: 0;
        }

        .report-record-count {
            font-size: 12px;
            color: #64748b;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .report-page .table {
            margin-bottom: 0;
        }

        .report-page .table thead th {
            background: #f1f5f9;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            padding: 14px 15px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .report-page .table tbody td {
            padding: 13px 15px;
            font-size: 13px;
            color: #475569;
            vertical-align: middle;
            border-color: #edf0f4;
        }

        .report-page .table tbody tr:hover {
            background: #f8fafc;
        }

        .report-empty {
            padding: 45px 15px !important;
            color: #94a3b8 !important;
            font-size: 14px !important;
        }

        .report-filter-actions {
            display: flex;
            flex-wrap: nowrap;
            gap: 8px;
            width: 100%;
        }

        .report-filter-actions .btn {
            flex: 1;
            white-space: nowrap;
        }

        @media (max-width: 767px) {
            .report-page {
                padding-top: 12px;
            }

            .report-title {
                font-size: 19px;
            }

            .report-header {
                align-items: flex-start;
            }

            .report-actions {
                width: 100%;
            }

            .report-actions .btn {
                flex: 1;
            }

            .report-page .card-body {
                padding: 15px;
            }

            .report-summary-value {
                font-size: 17px;
            }

            .report-page .table {
                min-width: 600px;
            }

            .report-filter-actions .btn {
                flex: 1;
            }
        }
    </style>

    <div class="container-fluid report-page">

        
        <div class="report-header">
            <div>
                <h2 class="report-title">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    <?php echo e($title); ?>

                </h2>
                <div class="report-subtitle">
                    Report Management / <?php echo e($title); ?>

                </div>
            </div>

            <div class="report-actions">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('report.export')): ?>
                    <a href="<?php echo e(route('reports.pdf', ['report' => $report] + request()->query())); ?>" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </a>

                    <a href="<?php echo e(route('reports.excel', ['report' => $report] + request()->query())); ?>" class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>
                <?php endif; ?>

                <a href="<?php echo e(route('reports.print', ['report' => $report] + request()->query())); ?>" target="_blank"
                    class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> Print
                </a>
            </div>
        </div>

        
        <div class="card report-filter-card">

            <div class="report-section-header">
                <i class="fas fa-filter text-primary me-2"></i>
                Report Filters
            </div>

            <div class="card-body">
                <form method="GET" action="<?php echo e(route('reports.show', $report)); ?>" class="row g-3">

                    <div class="col-md-2">
                        <label class="form-label" for="date_from"><?php echo e(__('From')); ?></label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="<?php echo e($filters['date_from']); ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="date_to"><?php echo e(__('To')); ?></label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="<?php echo e($filters['date_to']); ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="country_id"><?php echo e(__('Country')); ?></label>
                        <select name="country_id" id="country_id" class="form-select">
                            <option value=""><?php echo e(__('All')); ?></option>
                            <?php $__currentLoopData = $filterOptions['countries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($country->id); ?>" <?php if($filters['country_id'] == $country->id): echo 'selected'; endif; ?>>
                                    <?php echo e($country->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="branch_id"><?php echo e(__('Branch')); ?></label>
                        <select name="branch_id" id="branch_id" class="form-select">
                            <option value=""><?php echo e(__('All')); ?></option>
                            <?php $__currentLoopData = $filterOptions['branches']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch->id); ?>" <?php if($filters['branch_id'] == $branch->id): echo 'selected'; endif; ?>>
                                    <?php echo e($branch->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="agent_id"><?php echo e(__('Agent')); ?></label>
                        <select name="agent_id" id="agent_id" class="form-select">
                            <option value=""><?php echo e(__('All')); ?></option>
                            <?php $__currentLoopData = $filterOptions['agents']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($agent->id); ?>" <?php if($filters['agent_id'] == $agent->id): echo 'selected'; endif; ?>>
                                    <?php echo e($agent->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="status"><?php echo e(__('Status')); ?></label>
                        <input type="text" name="status" id="status" class="form-control"
                            value="<?php echo e($filters['status']); ?>" placeholder="<?php echo e(__('Status')); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="search"><?php echo e(__('Search')); ?></label>
                        <input type="text" name="search" id="search" class="form-control"
                            value="<?php echo e($filters['search']); ?>" placeholder="<?php echo e(__('Search…')); ?>">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <div class="report-filter-actions w-100">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-search me-1"></i> <?php echo e(__('Filter')); ?>

                            </button>

                            <a href="<?php echo e(route('reports.show', $report)); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i> <?php echo e(__('Reset')); ?>

                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        
        <?php if(!empty($summary)): ?>
            <div class="row mb-4">
                <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card report-summary-card">
                            <div class="card-body">
                                <div class="report-summary-label">
                                    <i class="fas fa-chart-line text-primary me-1"></i>
                                    <?php echo e($label); ?>

                                </div>

                                <div class="report-summary-value">
                                    <?php echo e(is_array($value) ? collect($value)->map(fn($v, $k) => $k . ': ' . $v)->implode(', ') : $value); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        
        <div class="card">

            <div class="report-table-header">
                <h5 class="report-table-title">
                    <i class="fas fa-table text-primary me-2"></i>
                    <?php echo e($title); ?>

                </h5>

                <span class="report-record-count">
                    <i class="fas fa-database me-1"></i>
                    <?php echo e(count($rows)); ?> <?php echo e(__('record(s)')); ?>

                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">

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
                                <td colspan="<?php echo e(count($columns)); ?>" class="text-center report-empty">
                                    <i class="fas fa-folder-open fa-2x d-block mb-3"></i>
                                    <?php echo e(__('No records match the selected filters.')); ?>

                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/show.blade.php ENDPATH**/ ?>