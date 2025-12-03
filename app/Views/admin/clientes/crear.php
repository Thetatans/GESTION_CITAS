<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --azul-oscuro: #1e3a5f;
            --cafe: #6b4423;
            --beige: #f5e6d3;
        }
        body { background: var(--beige); min-height: 100vh; }
        .navbar { background: var(--azul-oscuro) !important; }
        h1 { color: var(--azul-oscuro); font-weight: 700; }
        .btn-primary { background: var(--azul-oscuro); border-color: var(--azul-oscuro); }
        .btn-primary:hover { background: #152d47; }
        .card { border: 2px solid var(--azul-oscuro); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>">
                <i class="bi bi-scissors"></i> Admin - Barbería
            </a>
            <div class="d-flex">
                <a href="<?= base_url('admin/clientes') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1><i class="bi bi-person-plus"></i> <?= esc($titulo) ?></h1>
        <hr>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-body">
                <form action="<?= base_url('admin/clientes/guardar') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" value="<?= old('nombre') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido *</label>
                            <input type="text" name="apellido" class="form-control" value="<?= old('apellido') ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contraseña *</label>
                            <input type="password" name="password" class="form-control" required>
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono *</label>
                            <input type="text" name="telefono" class="form-control" value="<?= old('telefono') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" value="<?= old('fecha_nacimiento') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Género</label>
                            <select name="genero" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="Masculino" <?= old('genero') == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                <option value="Femenino" <?= old('genero') == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                                <option value="Otro" <?= old('genero') == 'Otro' ? 'selected' : '' ?>>Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="<?= old('direccion') ?>">
                        </div>
                    </div>

                    <hr>
                    <div class="text-end">
                        <a href="<?= base_url('admin/clientes') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
