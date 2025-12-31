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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('empleado/dashboard') ?>">
                <i class="bi bi-scissors"></i> Barbería - Empleado
            </a>
            <div class="text-white">
                <a href="<?= base_url('empleado/agenda') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-calendar-week"></i> Ver Agenda
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2><i class="bi bi-list-check"></i> <?= $titulo ?></h2>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" action="/empleado/citas">
                    <div class="row g-3">
                        <div class="col-md-3">
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
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control"
                                value="<?= $filtros['fecha_desde'] ?? '' ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bi bi-search"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="<?= base_url('empleado/citas') ?>" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
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
                        <i class="bi bi-info-circle"></i> No se encontraron citas.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas as $cita): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></td>
                                        <td><?= date('g:i A', strtotime($cita['hora_inicio'])) ?></td>
                                        <td>
                                            <strong><?= $cita['nombre_cliente'] ?></strong>
                                            <br><small class="text-muted">Tel: <?= $cita['telefono_cliente'] ?></small>
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
                                                <a href="<?= base_url('empleado/citas/ver/' . $cita['id_cita']) ?>"
                                                   class="btn btn-sm btn-info" title="Ver detalle">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <?php if ($cita['estado'] == 'pendiente'): ?>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'confirmada')"
                                                            title="Confirmar">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                <?php elseif ($cita['estado'] == 'confirmada'): ?>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'en_proceso')"
                                                            title="Iniciar">
                                                        <i class="bi bi-play-circle"></i>
                                                    </button>
                                                <?php elseif ($cita['estado'] == 'en_proceso'): ?>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'completada')"
                                                            title="Completar">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </button>
                                                <?php endif; ?>
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
    <script>
        function cambiarEstado(idCita, nuevoEstado) {
            if (confirm('¿Estás seguro de cambiar el estado de esta cita?')) {
                fetch(`/empleado/citas/actualizar-estado/${idCita}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `estado=${nuevoEstado}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar el estado');
                });
            }
        }
    </script>
</body>
</html>
