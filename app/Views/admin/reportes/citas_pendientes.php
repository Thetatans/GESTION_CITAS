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

        .alert-warning {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }

        footer {
            background: var(--azul-oscuro) !important;
            color: white;
            margin-top: 3rem;
        }

        .proxima-cita {
            background: #d1f2eb;
            border-left: 4px solid #198754;
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
                    <h1><i class="bi bi-clock-history"></i> <?= esc($titulo) ?></h1>
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
                                    <label class="form-label">Fecha Inicio (opcional)</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="<?= esc($fecha_inicio) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Fin (opcional)</label>
                                    <input type="date" name="fecha_fin" class="form-control" value="<?= esc($fecha_fin) ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-custom w-100">
                                        <i class="bi bi-search"></i> Filtrar
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?= base_url('admin/reportes/citas-pendientes') ?>" class="btn btn-secondary w-100">
                                        <i class="bi bi-x-circle"></i> Limpiar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3><i class="bi bi-clock"></i> <?= count($citas) ?></h3>
                                <p class="mb-0">Citas Pendientes</p>
                            </div>
                            <div class="col-md-4">
                                <?php
                                $pendientes = count(array_filter($citas, fn($c) => $c['estado'] == 'pendiente'));
                                $confirmadas = count(array_filter($citas, fn($c) => $c['estado'] == 'confirmada'));
                                ?>
                                <h3><i class="bi bi-exclamation-circle"></i> <?= $pendientes ?></h3>
                                <p class="mb-0">Sin Confirmar</p>
                            </div>
                            <div class="col-md-4">
                                <h3><i class="bi bi-check-circle"></i> <?= $confirmadas ?></h3>
                                <p class="mb-0">Confirmadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Citas Pendientes -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-list-task"></i> Listado de Citas Pendientes y Confirmadas
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
                                            <th>Precio</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($citas)): ?>
                                            <tr>
                                                <td colspan="10" class="text-center">No hay citas pendientes</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php
                                            $contador = 1;
                                            $hoy = date('Y-m-d');
                                            ?>
                                            <?php foreach ($citas as $cita): ?>
                                                <?php
                                                $esProxima = ($cita['fecha_cita'] == $hoy || $cita['fecha_cita'] == date('Y-m-d', strtotime('+1 day')));
                                                $rowClass = $esProxima ? 'proxima-cita' : '';
                                                ?>
                                                <tr class="<?= $rowClass ?>">
                                                    <td><?= $contador++ ?></td>
                                                    <td>
                                                        <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?>
                                                        <?php if ($cita['fecha_cita'] == $hoy): ?>
                                                            <span class="badge bg-danger">HOY</span>
                                                        <?php elseif ($cita['fecha_cita'] == date('Y-m-d', strtotime('+1 day'))): ?>
                                                            <span class="badge bg-warning">MAÑANA</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= date('g:i A', strtotime($cita['hora_inicio'])) ?></td>
                                                    <td><?= esc($cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']) ?></td>
                                                    <td>
                                                        <a href="tel:<?= esc($cita['telefono_cliente']) ?>" class="text-decoration-none">
                                                            <i class="bi bi-telephone"></i> <?= esc($cita['telefono_cliente']) ?>
                                                        </a>
                                                    </td>
                                                    <td><?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?></td>
                                                    <td><?= esc($cita['nombre_servicio']) ?></td>
                                                    <td>$<?= number_format($cita['precio'], 0) ?></td>
                                                    <td>
                                                        <?php if ($cita['estado'] == 'pendiente'): ?>
                                                            <span class="badge bg-warning">Pendiente</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-info">Confirmada</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('admin/citas/ver/' . $cita['id_cita']) ?>" class="btn btn-sm btn-primary" title="Ver Detalles">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="<?= base_url('admin/citas/editar/' . $cita['id_cita']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
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
                    <a href="<?= base_url('admin/reportes/citas-realizadas') ?>" class="btn btn-outline-success">
                        <i class="bi bi-check-circle"></i> Ver Citas Realizadas
                    </a>
                    <a href="<?= base_url('admin/citas') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-calendar"></i> Ir al Calendario
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
