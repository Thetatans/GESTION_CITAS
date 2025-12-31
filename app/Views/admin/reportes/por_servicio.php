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
                    <h1><i class="bi bi-gear"></i> <?= esc($titulo) ?></h1>
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
                                    <a href="<?= base_url('admin/reportes/exportarPDF?tipo=servicio&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-danger w-100">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?= base_url('admin/reportes/exportarExcel?tipo=servicio&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-success w-100">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Servicios -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-star"></i> Análisis de Servicios
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Servicio</th>
                                            <th>Precio Unitario</th>
                                            <th>Duración</th>
                                            <th>Total Citas</th>
                                            <th>Completadas</th>
                                            <th>Canceladas</th>
                                            <th>Ingresos Generados</th>
                                            <th>% del Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($reporte)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No hay datos en este período</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php
                                            $totalIngresos = array_sum(array_column($reporte, 'ingresos_generados'));
                                            ?>
                                            <?php foreach ($reporte as $srv): ?>
                                                <?php
                                                $porcentaje = $totalIngresos > 0 ? round(($srv['ingresos_generados'] / $totalIngresos) * 100, 1) : 0;
                                                ?>
                                                <tr>
                                                    <td><strong><?= esc($srv['nombre']) ?></strong></td>
                                                    <td>$<?= number_format($srv['precio'], 0) ?></td>
                                                    <td><?= $srv['duracion_minutos'] ?> min</td>
                                                    <td><span class="badge bg-primary"><?= $srv['total_citas'] ?></span></td>
                                                    <td><span class="badge bg-success"><?= $srv['citas_completadas'] ?></span></td>
                                                    <td><span class="badge bg-danger"><?= $srv['citas_canceladas'] ?></span></td>
                                                    <td><strong>$<?= number_format($srv['ingresos_generados'], 0) ?></strong></td>
                                                    <td>
                                                        <div class="progress" style="height: 25px;">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                 style="width: <?= $porcentaje ?>%;"
                                                                 aria-valuenow="<?= $porcentaje ?>"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                                <?= $porcentaje ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-active">
                                                <td colspan="6"><strong>TOTALES</strong></td>
                                                <td><strong>$<?= number_format($totalIngresos, 0) ?></strong></td>
                                                <td><strong>100%</strong></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfica de Servicios -->
            <?php if (!empty($reporte)): ?>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-pie-chart"></i> Distribución de Citas por Servicio
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="chartServicios"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-cash-stack"></i> Distribución de Ingresos por Servicio
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="chartIngresos"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        <?php if (!empty($reporte)): ?>
        const servicios = <?= json_encode(array_column($reporte, 'nombre')) ?>;
        const citas = <?= json_encode(array_column($reporte, 'citas_completadas')) ?>;
        const ingresos = <?= json_encode(array_column($reporte, 'ingresos_generados')) ?>;

        const colores = [
            '#1e3a5f', '#6b4423', '#198754', '#ffc107', '#dc3545',
            '#0dcaf0', '#6610f2', '#fd7e14', '#20c997', '#6c757d'
        ];

        // Gráfica de Citas
        new Chart(document.getElementById('chartServicios'), {
            type: 'pie',
            data: {
                labels: servicios,
                datasets: [{
                    data: citas,
                    backgroundColor: colores,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfica de Ingresos
        new Chart(document.getElementById('chartIngresos'), {
            type: 'doughnut',
            data: {
                labels: servicios,
                datasets: [{
                    data: ingresos,
                    backgroundColor: colores,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.parsed.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>
