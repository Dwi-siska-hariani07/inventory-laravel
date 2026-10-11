<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Ringkasan'); ?> - Inventory Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
        }
        .table > :not(caption) > * > * {
            padding: 0.8rem 0.75rem;
        }
        .badge-low-stock {
            background-color: #ef4444;
            color: #ffffff;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo e(route('dashboard')); ?>">
            <i class="bi bi-boxes text-info fs-4"></i>
            <span>Inventory System</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active text-info fw-semibold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active text-info fw-semibold' : ''); ?>" href="<?php echo e(route('products.index')); ?>">
                        <i class="bi bi-box-seam me-1"></i> Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active text-info fw-semibold' : ''); ?>" href="<?php echo e(route('categories.index')); ?>">
                        <i class="bi bi-tags me-1"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('suppliers.*') ? 'active text-info fw-semibold' : ''); ?>" href="<?php echo e(route('suppliers.index')); ?>">
                        <i class="bi bi-truck me-1"></i> Supplier
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('stock.*') ? 'active text-info fw-semibold' : ''); ?>" href="#" id="stockDrop" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-arrow-left-right me-1"></i> Transaksi Stok
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('stock.history')); ?>"><i class="bi bi-clock-history me-2"></i> Histori Transaksi</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-success" href="<?php echo e(route('stock.in')); ?>"><i class="bi bi-box-arrow-in-down me-2"></i> Barang Masuk (IN)</a></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo e(route('stock.out')); ?>"><i class="bi bi-box-arrow-up-right me-2"></i> Barang Keluar (OUT)</a></li>
                    </ul>
                </li>
                <?php if(auth()->check() && auth()->user()->role === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active text-info fw-semibold' : ''); ?>" href="<?php echo e(route('users.index')); ?>">
                        <i class="bi bi-people me-1"></i> Kelola User <span class="badge bg-danger ms-1">Admin</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item me-3 text-light d-flex align-items-center gap-2">
                        <span class="badge bg-secondary"><?php echo e(strtoupper(auth()->user()->role)); ?></span>
                        <span class="fw-medium"><i class="bi bi-person-circle me-1"></i> <?php echo e(auth()->user()->username); ?></span>
                    </li>
                    <li class="nav-item">
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline m-0 p-0">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container-fluid px-4 py-4 flex-grow-1">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="bg-white border-top py-3 mt-auto">
    <div class="container-fluid px-4 text-center text-muted small">
        &copy; <?php echo e(date('Y')); ?> <strong>Inventory Management System</strong> &bull; Mini Project 3 Relational Database + Advanced PHP (Laravel Edition)
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel dwi\resources\views/layouts/app.blade.php ENDPATH**/ ?>