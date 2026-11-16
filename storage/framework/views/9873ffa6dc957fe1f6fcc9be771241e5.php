<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-bs-theme="<?php echo e(session('theme', 'light')); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', \App\Services\BrandingService::appName()); ?></title>
    <?php if($branding?->favicon_path): ?>
        <link rel="icon" href="<?php echo e(route('branding.logo')); ?>">
    <?php endif; ?>
    <style>
        :root {
            --bs-primary: <?php echo e(\App\Services\BrandingService::primaryColor()); ?>;
            --bs-secondary: <?php echo e(\App\Services\BrandingService::secondaryColor()); ?>;
            --brand-primary: <?php echo e(\App\Services\BrandingService::primaryColor()); ?>;
            --brand-secondary: <?php echo e(\App\Services\BrandingService::secondaryColor()); ?>;
        }
    </style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<div class="d-flex" id="wrapper">
    <aside class="sidebar bg-dark text-white d-flex flex-column flex-shrink-0" id="sidebar">
        <a href="<?php echo e(route('dashboard')); ?>" class="d-flex align-items-center px-3 py-3 text-white text-decoration-none border-bottom border-secondary">
            <?php if($branding?->logo_path): ?>
                <img src="<?php echo e(route('branding.logo')); ?>" alt="Logo" height="32" class="me-2">
            <?php endif; ?>
            <span class="fs-6 fw-bold"><?php echo e(\App\Services\BrandingService::appName()); ?></span>
        </a>
        <nav class="nav nav-pills flex-column px-2 py-2 gap-1" aria-label="Main navigation">
            
            <?php $__currentLoopData = config('menu.items', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(empty($item['permission']) || auth()->user()?->can($item['permission'])): ?>
                    <?php if(Route::has($item['route'])): ?>
                        <a class="nav-link text-white <?php echo e(request()->routeIs($item['active'] ?? $item['route']) ? 'active' : ''); ?>" href="<?php echo e(route($item['route'])); ?>"><?php echo e(__($item['label'])); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
        <div class="mt-auto p-3 border-top border-secondary small text-white-50">
            <?php echo e(\App\Services\BrandingService::currency()); ?> &middot; <?php echo e(\App\Services\BrandingService::timezone()); ?>

        </div>
    </aside>

    <div class="flex-grow-1 d-flex flex-column min-vh-100" id="page-content">
        <header class="topbar d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-body">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-label="Toggle navigation">&#9776;</button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Home')); ?></a></li>
                        <?php echo $__env->yieldContent('breadcrumbs'); ?>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-secondary" type="button" onclick="toggleTheme()" aria-label="Toggle theme">&#9788;</button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo e(auth()->user()->name); ?>

                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>"><?php echo e(__('Profile')); ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item"><?php echo e(__('Log out')); ?></button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="container-fluid py-3 flex-grow-1">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
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
        </main>

        <footer class="border-top py-2 px-3 small text-muted d-flex justify-content-between">
            <span><?php echo e(\App\Services\BrandingService::appName()); ?></span>
            <span><?php echo e(now()->format('Y-m-d H:i')); ?></span>
        </footer>
    </div>
</div>
<script>
function toggleTheme() {
    const html = document.documentElement;
    const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-bs-theme', next);
    document.cookie = 'theme=' + next + '; path=/; max-age=' + (60 * 60 * 24 * 365);
}
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH R:\xampp\htdocs\manpower-erp-new\resources\views/layouts/app.blade.php ENDPATH**/ ?>