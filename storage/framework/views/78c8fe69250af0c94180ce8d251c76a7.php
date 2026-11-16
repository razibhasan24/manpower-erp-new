<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', \App\Services\BrandingService::appName()); ?></title>
    <style>
        :root {
            --brand-primary: <?php echo e(\App\Services\BrandingService::primaryColor()); ?>;
            --brand-secondary: <?php echo e(\App\Services\BrandingService::secondaryColor()); ?>;
        }
    </style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="guest-body">
    <main class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow w-100" style="max-width: 430px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <?php if($branding?->logo_path): ?>
                        <img src="<?php echo e(route('branding.logo')); ?>" alt="Logo" height="56" class="mb-2">
                    <?php endif; ?>
                    <h1 class="h5 mb-0"><?php echo e(\App\Services\BrandingService::appName()); ?></h1>
                </div>
                <?php if(session('status')): ?>
                    <div class="alert alert-info" role="alert"><?php echo e(session('status')); ?></div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </main>
</body>
</html>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/layouts/guest.blade.php ENDPATH**/ ?>