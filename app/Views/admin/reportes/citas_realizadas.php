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

        .alert-success {
            background: #d1e7dd;
            border-color: #198754;
            color: #0f5132;
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
                    <h1><i class="bi bi-check-circle"></i> <?= esc($titulo) ?></h1>
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
                                    <a href="<?= base_url('admin/reportes/exportarPDF?tipo=realizadas&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-danger w-100">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?= base_url('admin/reportes/exportarExcel?tipo=realizadas&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-success w-100">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de Ingresos -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3><i class="bi bi-check-circle-fill"></i> <?= count($citas) ?></h3>
                                <p class="mb-0">Citas Completadas</p>
                            </div>
                            <div class="col-md-4">
                                <h3><i class="bi bi-cash-stack"></i> $<?= number_format($ingresos_totales, 0) ?></h3>
                                <p class="mb-0">Ingresos Totales</p>
                            </div>
                            <div class="col-md-4">
                                <h3><i class="bi bi-graph-up-arrow"></i> $<?= count($citas) > 0 ? number_format($ingresos_totales / count($citas), 0) : 0 ?></h3>
                                <p class="mb-0">Promedio por Cita</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Citas Realizadas -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-list-check"></i> Detalle de Citas Completadas
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Teléfono</th>
                                            <th>Empleado</th>
                                            <th>Servicio</th>
                                            <th>Duración</th>
                                            <th>Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($citas)): ?>
                                            <tr>
                                                <td colspan="9" class="text-center">No hay citas completadas en este período</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $contador = 1; ?>
                                            <?php foreach ($citas as $cita): ?>
                                                <tr>
                                                    <td><?= $contador++ ?></td>
                                                    <td><?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></td>
                                                    <td><?= date('g:i A', strtotime($cita['hora_inicio'])) ?></td>
                                                    <td><?= esc($cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']) ?></td>
                                                    <td><?= esc($cita['telefono_cliente']) ?></td>
                                                    <td><?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?></td>
                                                    <td><?= esc($cita['nombre_servicio']) ?></td>
                                                    <td><?= $cita['duracion_minutos'] ?> min</td>
                                                    <td><strong>$<?= number_format($cita['precio'], 0) ?></strong></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-success">
                                                <td colspan="8" class="text-end"><strong>TOTAL INGRESOS:</strong></td>
                                                <td><strong>$<?= number_format($ingresos_totales, 0) ?></strong></td>
                                            </tr>
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
                    <a href="<?= base_url('admin/reportes/citas-pendientes') ?>" class="btn btn-outline-warning">
                        <i class="bi bi-clock"></i> Ver Citas Pendientes
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
