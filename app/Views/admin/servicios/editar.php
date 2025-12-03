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
                <a href="<?= base_url('admin/servicios') ?>" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-arrow-left"></i> Volver</a>
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
                <form action="<?= base_url('admin/servicios/actualizar/' . $servicio['id_servicio']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Servicio *</label>
                            <input type="text" name="nombre" class="form-control" value="<?= old('nombre', $servicio['nombre']) ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Precio ($) *</label>
                            <input type="number" step="0.01" min="0" name="precio" class="form-control" value="<?= old('precio', $servicio['precio']) ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Duración (minutos) *</label>
                            <select name="duracion_minutos" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="20" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '20' ? 'selected' : '' ?>>20 min</option>
                                <option value="40" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '40' ? 'selected' : '' ?>>40 min</option>
                                <option value="60" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '60' ? 'selected' : '' ?>>60 min (1 hora)</option>
                                <option value="80" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '80' ? 'selected' : '' ?>>80 min</option>
                                <option value="100" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '100' ? 'selected' : '' ?>>100 min</option>
                                <option value="120" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '120' ? 'selected' : '' ?>>120 min (2 horas)</option>
                                <option value="140" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '140' ? 'selected' : '' ?>>140 min</option>
                                <option value="160" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '160' ? 'selected' : '' ?>>160 min</option>
                                <option value="180" <?= old('duracion_minutos', $servicio['duracion_minutos']) == '180' ? 'selected' : '' ?>>180 min (3 horas)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"><?= old('descripcion', $servicio['descripcion']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="activo" class="form-select" required>
                            <option value="1" <?= old('activo', $servicio['activo']) == '1' ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?= old('activo', $servicio['activo']) == '0' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <hr>
                    <div class="text-end">
                        <a href="<?= base_url('admin/servicios') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Actualizar Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
