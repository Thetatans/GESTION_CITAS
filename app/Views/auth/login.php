<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="auth-card mx-auto">
    <div class="auth-header">
        <h3 class="mb-0"><i class="bi bi-scissors"></i> Barbería & Spa</h3>
        <p class="mb-0 mt-2">Sistema de Gestión de Citas</p>
    </div>
    
    <div class="auth-body">
        <h4 class="text-center mb-4">Iniciar Sesión</h4>
        
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <?php
            // Determinar el icono y clase según el tipo de error
            $estadoUsuario = session('error_estado');
            $iconoClase = 'bi-exclamation-triangle';
            $alertaClase = 'alert-danger';

            // Personalizar según el estado del usuario
            if ($estadoUsuario === 'suspendido') {
                $iconoClase = 'bi-pause-circle';
                $alertaClase = 'alert-warning';
            } elseif ($estadoUsuario === 'inactivo') {
                $iconoClase = 'bi-x-circle';
                $alertaClase = 'alert-info';
            } elseif ($estadoUsuario === 'despedido') {
                $iconoClase = 'bi-ban';
                $alertaClase = 'alert-danger';
            }
            ?>
            <div class="alert <?= $alertaClase ?> alert-dismissible fade show" role="alert">
                <i class="bi <?= $iconoClase ?>"></i>
                <strong><?= $estadoUsuario ? 'Cuenta ' . ucfirst($estadoUsuario) : 'Error' ?>:</strong>
                <?= session('error') ?>
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

        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope"></i> Email
                </label>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       value="<?= old('email') ?>"
                       placeholder="tu@email.com"
                       required 
                       autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-lock"></i> Contraseña
                </label>
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password"
                       placeholder="••••••••"
                       required>
            </div>

            

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="text-center">
            <p class="mb-2">
                <a href="<?= base_url('recuperar-password') ?>" class="text-decoration-none">
                    ¿Olvidaste tu contraseña?
                </a>
            </p>
            <p class="mb-0">
                ¿No tienes cuenta? 
                <a href="<?= base_url('registro') ?>" class="text-decoration-none fw-bold">
                    Regístrate aquí
                </a>
            </p>
        </div>

        <div class="mt-4 p-3 bg-light rounded">
            <small class="text-muted">
                <strong>Credenciales de prueba:</strong><br>
                <i class="bi bi-person-badge"></i> Admin: admin@barberia.com / ilich123<br>
                <i class="bi bi-person"></i> Empleado: empleado@barberia.com / ilich123<br>
                <i class="bi bi-person"></i> Cliente: cliente@barberia.com / ilich123
            </small>
        </div>
    </div>
</div>

<?= $this->endSection() ?>