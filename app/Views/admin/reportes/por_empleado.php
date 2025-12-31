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

        .progress {
            height: 25px;
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
                    <h1><i class="bi bi-people"></i> <?= esc($titulo) ?></h1>
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
                                    <a href="<?= base_url('admin/reportes/exportarPDF?tipo=empleado&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-danger w-100">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?= base_url('admin/reportes/exportarExcel?tipo=empleado&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin) ?>" class="btn btn-success w-100">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Empleados -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-bar-chart"></i> Productividad por Empleado
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Empleado</th>
                                            <th>Especialidad</th>
                                            <th>Total Citas</th>
                                            <th>Completadas</th>
                                            <th>Canceladas</th>
                                            <th>Pendientes</th>
                                            <th>Ingresos</th>
                                            <th>Horas Trabajadas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($reporte)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No hay datos en este período</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($reporte as $emp): ?>
                                                <tr>
                                                    <td><strong><?= esc($emp['nombre'] . ' ' . $emp['apellido']) ?></strong></td>
                                                    <td><?= esc($emp['especialidad']) ?></td>
                                                    <td><span class="badge bg-primary"><?= $emp['total_citas'] ?></span></td>
                                                    <td><span class="badge bg-success"><?= $emp['citas_completadas'] ?></span></td>
                                                    <td><span class="badge bg-danger"><?= $emp['citas_canceladas'] ?></span></td>
                                                    <td><span class="badge bg-warning"><?= $emp['citas_pendientes'] ?></span></td>
                                                    <td><strong>$<?= number_format($emp['ingresos_generados'], 0) ?></strong></td>
                                                    <td><?= round($emp['minutos_trabajados'] / 60, 1) ?> hrs</td>
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

            <!-- Gráfica de Comparación -->
            <?php if (!empty($reporte)): ?>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-graph-up"></i> Comparación de Ingresos por Empleado
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="chartEmpleados"></canvas>
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
        // Gráfica de Empleados
        const ctx = document.getElementById('chartEmpleados').getContext('2d');

        const empleados = <?= json_encode(array_map(function($e) { return $e['nombre'] . ' ' . $e['apellido']; }, $reporte)) ?>;
        const ingresos = <?= json_encode(array_column($reporte, 'ingresos_generados')) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: empleados,
                datasets: [{
                    label: 'Ingresos Generados ($)',
                    data: ingresos,
                    backgroundColor: '#6b4423',
                    borderColor: '#1e3a5f',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
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
