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
            --azul-hover: #152d47;
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

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .btn-outline-light:hover {
            background: var(--cafe);
            border-color: var(--cafe);
        }

        h1 {
            color: var(--azul-oscuro);
            font-weight: 700;
        }

        hr {
            border-color: var(--cafe);
            opacity: 0.3;
            border-width: 2px;
        }

        .card {
            border: 2px solid var(--azul-oscuro);
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .card-header {
            background: var(--azul-oscuro) !important;
            color: white;
            font-weight: 600;
        }

        .kpi-card {
            border-left: 5px solid;
        }

        .kpi-card.primary {
            border-left-color: var(--azul-oscuro);
        }

        .kpi-card.success {
            border-left-color: #198754;
        }

        .kpi-card.danger {
            border-left-color: #dc3545;
        }

        .kpi-card.warning {
            border-left-color: #ffc107;
        }

        .kpi-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--azul-oscuro);
        }

        .kpi-label {
            color: var(--gris);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-custom {
            background: var(--cafe);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 10px;
        }

        .btn-custom:hover {
            background: var(--azul-oscuro);
            color: white;
        }

        .table {
            color: var(--gris);
        }

        .table thead {
            background: var(--azul-oscuro);
            color: white;
        }

        footer {
            background: var(--azul-oscuro) !important;
            color: white;
            margin-top: 3rem;
        }

        footer .text-muted {
            color: rgba(255,255,255,0.7) !important;
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
                    <h1><i class="bi bi-graph-up"></i> <?= esc($titulo) ?></h1>
                    <hr>
                </div>
            </div>

            <!-- Filtros de Fecha -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="<?= base_url('admin/reportes') ?>" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="<?= esc($fecha_inicio) ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control" value="<?= esc($fecha_fin) ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-custom w-100">
                                        <i class="bi bi-search"></i> Filtrar
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= base_url('admin/reportes/exportarPDF?tipo=general&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-danger w-100">
                                        <i class="bi bi-file-pdf"></i> Exportar PDF
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card kpi-card primary">
                        <div class="card-body text-center">
                            <div class="kpi-value"><?= number_format($estadisticas['total_citas']) ?></div>
                            <div class="kpi-label">Total de Citas</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card kpi-card success">
                        <div class="card-body text-center">
                            <div class="kpi-value"><?= number_format($estadisticas['citas_completadas']) ?></div>
                            <div class="kpi-label">Citas Completadas</div>
                            <small class="text-muted"><?= $estadisticas['tasa_completacion'] ?>% del total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card kpi-card danger">
                        <div class="card-body text-center">
                            <div class="kpi-value"><?= number_format($estadisticas['citas_canceladas']) ?></div>
                            <div class="kpi-label">Citas Canceladas</div>
                            <small class="text-muted"><?= $estadisticas['tasa_cancelacion'] ?>% del total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card kpi-card warning">
                        <div class="card-body text-center">
                            <div class="kpi-value">$<?= number_format($estadisticas['ingresos_totales'], 0) ?></div>
                            <div class="kpi-label">Ingresos Totales</div>
                            <small class="text-muted">Promedio: $<?= number_format($estadisticas['promedio_ingreso'], 0) ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reportes Rápidos -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-file-earmark-text"></i> Reportes Detallados
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <a href="<?= base_url('admin/reportes/por-fecha?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-calendar-range"></i><br>Reporte por Fecha
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= base_url('admin/reportes/por-empleado?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-outline-success w-100">
                                        <i class="bi bi-people"></i><br>Reporte por Empleado
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= base_url('admin/reportes/por-servicio?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-outline-info w-100">
                                        <i class="bi bi-gear"></i><br>Reporte por Servicio
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?= base_url('admin/reportes/citas-realizadas?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-outline-warning w-100">
                                        <i class="bi bi-check-circle"></i><br>Citas Realizadas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Empleados -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-trophy"></i> Top Empleados por Productividad
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Empleado</th>
                                            <th>Citas</th>
                                            <th>Ingresos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 0; ?>
                                        <?php foreach ($reporte_empleados as $emp): ?>
                                            <?php if ($count++ >= 5) break; ?>
                                            <tr>
                                                <td><?= esc($emp['nombre'] . ' ' . $emp['apellido']) ?></td>
                                                <td><span class="badge bg-primary"><?= $emp['citas_completadas'] ?></span></td>
                                                <td><strong>$<?= number_format($emp['ingresos_generados'], 0) ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="<?= base_url('admin/reportes/por-empleado?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-sm btn-custom">
                                Ver Reporte Completo <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Top Servicios -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-star"></i> Servicios Más Solicitados
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Servicio</th>
                                            <th>Citas</th>
                                            <th>Ingresos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 0; ?>
                                        <?php foreach ($reporte_servicios as $srv): ?>
                                            <?php if ($count++ >= 5) break; ?>
                                            <tr>
                                                <td><?= esc($srv['nombre']) ?></td>
                                                <td><span class="badge bg-success"><?= $srv['citas_completadas'] ?></span></td>
                                                <td><strong>$<?= number_format($srv['ingresos_generados'], 0) ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="<?= base_url('admin/reportes/por-servicio?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-sm btn-custom">
                                Ver Reporte Completo <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Volver al Dashboard -->
            <div class="row mb-4">
                <div class="col-md-12 text-center">
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
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
                        <p>Desarrollado por: <strong>Ilich Esteban Reyes Botia</strong></p>
                        <p>Aprendiz SENA</p>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
