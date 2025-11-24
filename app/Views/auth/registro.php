<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="auth-card mx-auto">
    <div class="auth-header">
        <h3 class="mb-0"><i class="bi bi-scissors"></i> Barbería & Spa</h3>
        <p class="mb-0 mt-2">Crear Cuenta Nueva</p>
    </div>
    
    <div class="auth-body">
        <h4 class="text-center mb-4">Registro</h4>
        
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

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('registro') ?>" method="post" id="formRegistro">
            <?= csrf_field() ?>
            
            <!-- Rol fijo como cliente -->
            <input type="hidden" name="rol" value="cliente">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">
                        <i class="bi bi-person"></i> Nombre *
                    </label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?= old('nombre') ?>" placeholder="Tu nombre" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="apellido" class="form-label">
                        <i class="bi bi-person"></i> Apellido *
                    </label>
                    <input type="text" class="form-control" id="apellido" name="apellido" 
                           value="<?= old('apellido') ?>" placeholder="Tu apellido" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope"></i> Email *
                </label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= old('email') ?>" placeholder="tu@email.com" required>
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">
                    <i class="bi bi-phone"></i> Teléfono *
                </label>
                <input type="tel" class="form-control" id="telefono" name="telefono" 
                       value="<?= old('telefono') ?>" placeholder="300 123 4567" required>
            </div>

            <!-- CAMPOS ADICIONALES PARA CLIENTE -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_nacimiento" class="form-label">
                        <i class="bi bi-calendar"></i> Fecha de Nacimiento
                    </label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                           value="<?= old('fecha_nacimiento') ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="genero" class="form-label">
                        <i class="bi bi-gender-ambiguous"></i> Género
                    </label>
                    <select class="form-select" id="genero" name="genero">
                        <option value="">Seleccionar...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">
                    <i class="bi bi-geo-alt"></i> Dirección
                </label>
                <textarea class="form-control" id="direccion" name="direccion" rows="2"
                          placeholder="Calle, número, ciudad"><?= old('direccion') ?></textarea>
            </div>

            <hr class="my-3">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i> Contraseña *
                    </label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Mínimo 8 caracteres" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmar" class="form-label">
                        <i class="bi bi-lock-fill"></i> Confirmar *
                    </label>
                    <input type="password" class="form-control" id="password_confirmar" name="password_confirmar"
                           placeholder="Repite tu contraseña" required>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="terminos" required>
                <label class="form-check-label" for="terminos">
                    Acepto los términos y condiciones
                </label>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> Crear Cuenta
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="text-center">
            <p class="mb-0">
                ¿Ya tienes cuenta? 
                <a href="<?= base_url('login') ?>" class="text-decoration-none fw-bold">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>
</div>


<?= $this->endSection() ?>