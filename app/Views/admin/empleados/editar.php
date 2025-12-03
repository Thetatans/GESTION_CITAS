<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --azul-oscuro: #1e3a5f; --beige: #f5e6d3; }
        body { background: var(--beige); min-height: 100vh; }
        .navbar { background: var(--azul-oscuro) !important; }
        h1 { color: var(--azul-oscuro); font-weight: 700; }
        .btn-primary { background: var(--azul-oscuro); border-color: var(--azul-oscuro); }
        .card { border: 2px solid var(--azul-oscuro); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-scissors"></i> Admin - Barbería</a>
            <div class="d-flex">
                <a href="<?= base_url('admin/empleados') ?>" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-arrow-left"></i> Volver</a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1><i class="bi bi-pencil"></i> <?= esc($titulo) ?></h1>
        <hr>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> <?= session('error') ?></div>
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
                <form action="<?= base_url('admin/empleados/actualizar/' . $empleado['id_empleado']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" value="<?= old('nombre', $empleado['nombre']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido *</label>
                            <input type="text" name="apellido" class="form-control" value="<?= old('apellido', $empleado['apellido']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email', $empleado['email']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control">
                            <small class="text-muted">Dejar en blanco para mantener la actual</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Teléfono *</label>
                            <input type="text" name="telefono" class="form-control" value="<?= old('telefono', $empleado['telefono']) ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="especialidad" class="form-control" value="<?= old('especialidad', $empleado['especialidad']) ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Comisión (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="comision_porcentaje" class="form-control" value="<?= old('comision_porcentaje', $empleado['comision_porcentaje']) ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Contratación</label>
                            <input type="date" name="fecha_contratacion" class="form-control" value="<?= old('fecha_contratacion', $empleado['fecha_contratacion']) ?>">
                        </div>
                    </div>

                    <hr>
                    <div class="text-end">
                        <a href="<?= base_url('admin/empleados') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Actualizar Empleado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
