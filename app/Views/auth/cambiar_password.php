<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="auth-card mx-auto">
    <div class="auth-header">
        <h3 class="mb-0"><i class="bi bi-key"></i> Cambiar Contraseña</h3>
        <p class="mb-0 mt-2">Actualiza tu contraseña</p>
    </div>
    
    <div class="auth-body">
        <h4 class="text-center mb-4">Nueva Contraseña</h4>
        
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('actualizar-password') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="password_actual" class="form-label">
                    <i class="bi bi-lock"></i> Contraseña Actual *
                </label>
                <input type="password" 
                       class="form-control" 
                       id="password_actual" 
                       name="password_actual"
                       placeholder="Tu contraseña actual"
                       required>
            </div>

            <div class="mb-3">
                <label for="password_nueva" class="form-label">
                    <i class="bi bi-lock-fill"></i> Nueva Contraseña *
                </label>
                <input type="password" 
                       class="form-control" 
                       id="password_nueva" 
                       name="password_nueva"
                       placeholder="Mínimo 8 caracteres"
                       required>
                <small class="text-muted">Debe tener al menos 8 caracteres</small>
            </div>

            <div class="mb-3">
                <label for="password_confirmar" class="form-label">
                    <i class="bi bi-lock-fill"></i> Confirmar Nueva Contraseña *
                </label>
                <input type="password" 
                       class="form-control" 
                       id="password_confirmar" 
                       name="password_confirmar"
                       placeholder="Repite la nueva contraseña"
                       required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Actualizar Contraseña
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="text-center">
            <a href="<?= base_url() ?>" class="text-decoration-none">
                <i class="bi bi-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>