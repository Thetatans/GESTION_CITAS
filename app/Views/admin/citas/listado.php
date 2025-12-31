<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> - Sistema de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-dark: #1e3a5f;
            --secondary-brown: #6b4423;
        }
        .navbar {
            background-color: var(--primary-dark);
        }
        .btn-primary {
            background-color: var(--secondary-brown);
            border-color: var(--secondary-brown);
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>">
                <i class="bi bi-scissors"></i> Barbería Admin
            </a>
            <div class="text-white">
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-list-ul"></i> <?= $titulo ?></h2>
                    <a href="<?= base_url('admin/citas') ?>" class="btn btn-primary">
                        <i class="bi bi-calendar"></i> Ver Calendario
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" action="/admin/citas/listado">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Empleado</label>
                            <select name="empleado" class="form-select">
                                <option value="">Todos</option>
                                <?php foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id_empleado'] ?>"
                                        <?= (isset($filtros['id_empleado']) && $filtros['id_empleado'] == $emp['id_empleado']) ? 'selected' : '' ?>>
                                        <?= $emp['nombre'] . ' ' . $emp['apellido'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos</option>
                                <option value="pendiente" <?= (isset($filtros['estado']) && $filtros['estado'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                                <option value="confirmada" <?= (isset($filtros['estado']) && $filtros['estado'] == 'confirmada') ? 'selected' : '' ?>>Confirmada</option>
                                <option value="en_proceso" <?= (isset($filtros['estado']) && $filtros['estado'] == 'en_proceso') ? 'selected' : '' ?>>En Proceso</option>
                                <option value="completada" <?= (isset($filtros['estado']) && $filtros['estado'] == 'completada') ? 'selected' : '' ?>>Completada</option>
                                <option value="cancelada" <?= (isset($filtros['estado']) && $filtros['estado'] == 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Desde</label>
                            <input type="date" name="fecha_desde" class="form-control"
                                value="<?= $filtros['fecha_desde'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control"
                                value="<?= $filtros['fecha_hasta'] ?? '' ?>">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de citas -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($citas)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No se encontraron citas con los filtros seleccionados.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Cliente</th>
                                    <th>Empleado</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas as $cita): ?>
                                    <tr>
                                        <td><?= $cita['id_cita'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></td>
                                        <td><?= date('g:i A', strtotime($cita['hora_inicio'])) ?></td>
                                        <td>
                                            <i class="bi bi-person"></i> <?= esc($cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']) ?>
                                            <br><small class="text-muted"><?= $cita['telefono_cliente'] ?></small>
                                        </td>
                                        <td>
                                            <?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?>
                                            <br><small class="text-muted"><?= $cita['especialidad'] ?></small>
                                        </td>
                                        <td><?= $cita['nombre_servicio'] ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = [
                                                'pendiente' => 'bg-warning text-dark',
                                                'confirmada' => 'bg-info',
                                                'en_proceso' => 'bg-primary',
                                                'completada' => 'bg-success',
                                                'cancelada' => 'bg-danger'
                                            ];
                                            ?>
                                            <span class="badge <?= $badgeClass[$cita['estado']] ?? 'bg-secondary' ?>">
                                                <?= ucfirst($cita['estado']) ?>
                                            </span>
                                        </td>
                                        <td>$<?= number_format($cita['precio'], 0) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('admin/citas/ver/' . $cita['id_cita']) ?>"
                                                   class="btn btn-sm btn-info" title="Ver detalle">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= base_url('admin/citas/editar/' . $cita['id_cita']) ?>"
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted">Total de citas: <strong><?= count($citas) ?></strong></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
