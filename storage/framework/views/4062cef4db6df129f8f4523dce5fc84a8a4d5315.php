<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management System (Laravel)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <i class="bi bi-boxes text-danger display-4"></i>
                <h3 class="fw-bold mt-2">Inventory System</h3>
                <p class="text-muted small">Pertemuan 5: Laravel Fundamental & MVC</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3 text-center">Masuk ke Akun</h5>

                    <?php if(session('success')): ?>
                        <div class="alert alert-info py-2 small"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger py-2 small">
                            <?php echo e($errors->first()); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('login.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="username" class="form-label small fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required value="<?php echo e(old('username')); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mt-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-4 pt-0">
                    <hr class="text-muted">
                    <p class="text-muted small mb-1 fw-bold">Akun Demo Praktikum:</p>
                    <span class="badge bg-secondary me-1">Admin: admin / admin123</span>
                    <span class="badge bg-secondary">Staff: staff / staff123</span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel dwi\resources\views/auth/login.blade.php ENDPATH**/ ?>