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
            --gris: #495057;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--beige);
        }

        .main-content {
            flex: 1;
        }

        .navbar {
            background: var(--azul-oscuro) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        h1 {
            color: var(--azul-oscuro);
            font-weight: 700;
        }

        .card {
            border: 2px solid var(--azul-oscuro);
            border-radius: 15px;
        }

        .card-header {
            background: var(--azul-oscuro) !important;
            color: white;
            font-weight: 600;
        }

        .btn-custom {
            background: var(--cafe);
            color: white;
            border: none;
            font-weight: 600;
        }

        .btn-custom:hover {
            background: var(--azul-oscuro);
            color: white;
        }

        .table thead {
            background: var(--azul-oscuro);
            color: white;
        }

        .badge {
            font-size: 0.85rem;
        }

        footer {
            background: var(--azul-oscuro) !important;
            color: white;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>">
                <i class="bi bi-scissors"></i> Admin - Barbería
            </a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> <?= session()->get('usuario_nombre') ?>
                </span>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container-fluid mt-4">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h1><i class="bi bi-calendar-range"></i> <?= esc($titulo) ?></h1>
                    <hr>
                </div>
            </div>

            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="<?= esc($fecha_inicio) ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control" value="<?= esc($fecha_fin) ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-custom w-100">
                                        <i class="bi bi-search"></i> Filtrar
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?= base_url('admin/reportes/exportarPDF?tipo=general&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-danger w-100">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?= base_url('admin/reportes/exportarExcel?tipo=general&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-success w-100">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen Estadístico -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-primary"><?= $estadisticas['total_citas'] ?></h3>
                            <p class="mb-0">Total de Citas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-success"><?= $estadisticas['citas_completadas'] ?></h3>
                            <p class="mb-0">Completadas (<?= $estadisticas['tasa_completacion'] ?>%)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-danger"><?= $estadisticas['citas_canceladas'] ?></h3>
                            <p class="mb-0">Canceladas (<?= $estadisticas['tasa_cancelacion'] ?>%)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-warning">$<?= number_format($estadisticas['ingresos_totales'], 0) ?></h3>
                            <p class="mb-0">Ingresos Totales</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Citas -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-list-ul"></i> Listado Completo de Citas (<?= count($citas) ?> registros)
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Empleado</th>
                                            <th>Servicio</th>
                                            <th>Precio</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($citas)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No hay citas en este período</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($citas as $cita): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></td>
                                                    <td><?= date('g:i A', strtotime($cita['hora_inicio'])) ?></td>
                                                    <td><?= esc($cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']) ?></td>
                                                    <td><?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?></td>
                                                    <td><?= esc($cita['nombre_servicio']) ?></td>
                                                    <td>$<?= number_format($cita['precio'], 0) ?></td>
                                                    <td>
                                                        <?php
                                                        $badgeClass = [
                                                            'pendiente' => 'warning',
                                                            'confirmada' => 'info',
                                                            'en_proceso' => 'primary',
                                                            'completada' => 'success',
                                                            'cancelada' => 'danger'
                                                        ];
                                                        ?>
                                                        <span class="badge bg-<?= $badgeClass[$cita['estado']] ?>">
                                                            <?= ucfirst($cita['estado']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Navegación -->
            <div class="row mb-4">
                <div class="col-md-12 text-center">
                    <a href="<?= base_url('admin/reportes') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a Reportes
                    </a>
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-house"></i> Ir al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <small class="text-muted">
                        <p><strong>Sistema de Gestión de Citas</strong></p>
                        <p>Barbería y Spa - Proyecto DICO TELECOMUNICACIONES</p>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
