<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo e($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h1 class="h4 mb-0"><?php echo e($company['name'] ?? config('app.name')); ?></h1>
                <div class="text-muted small">
                    <?php echo e($company['address'] ?? ''); ?>

                    <?php if(! empty($company['phone'])): ?> &middot; <?php echo e($company['phone']); ?> <?php endif; ?>
                    <?php if(! empty($company['email'])): ?> &middot; <?php echo e($company['email']); ?> <?php endif; ?>
                </div>
                <h2 class="h5 mt-3"><?php echo e($title); ?></h2>
                <div class="text-muted small">Generated: <?php echo e($generatedAt); ?></div>
            </div>
            <button onclick="window.print()" class="btn btn-primary no-print">Print</button>
        </div>

        <?php if(! empty($summary)): ?>
            <div class="mb-3">
                <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="me-3"><strong><?php echo e($label); ?>:</strong> <?php echo e(is_array($value) ? collect($value)->map(fn ($v, $k) => $k.': '.$v)->implode(', ') : $value); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-sm">
            <thead class="table-light">
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
                    <tr><td colspan="<?php echo e(count($columns)); ?>" class="text-center text-muted">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script>window.addEventListener('load', () => { /* print is manual via the button */ });</script>
</body>
</html>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/reports/print.blade.php ENDPATH**/ ?>