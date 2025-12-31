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
        .cita-card {
            transition: all 0.3s;
        }
        .cita-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .estado-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('cliente/dashboard') ?>">
                <i class="bi bi-scissors"></i> Barbería
            </a>
            <div class="text-white">
                <a href="<?= base_url('cliente/agendar') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-calendar-plus"></i> Agendar Cita
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2><i class="bi bi-calendar-check"></i> <?= $titulo ?></h2>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#proximas">
                    <i class="bi bi-clock"></i> Próximas Citas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#historial">
                    <i class="bi bi-calendar-event"></i> Historial
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Próximas Citas -->
            <div class="tab-pane fade show active" id="proximas">
                <?php
                $hoy = date('Y-m-d');
                $proximasCitas = array_filter($citas, function($cita) use ($hoy) {
                    return $cita['fecha_cita'] >= $hoy && !in_array($cita['estado'], ['completada', 'cancelada']);
                });
                ?>

                <?php if (empty($proximasCitas)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No tienes citas próximas.
                        <a href="<?= base_url('cliente/agendar') ?>" class="alert-link">Agenda una ahora</a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($proximasCitas as $cita): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card cita-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0">
                                                <i class="bi bi-scissors"></i> <?= $cita['nombre_servicio'] ?>
                                            </h5>
                                            <?php
                                            $badgeClass = [
                                                'pendiente' => 'bg-warning text-dark',
                                                'confirmada' => 'bg-info',
                                                'en_proceso' => 'bg-primary',
                                            ];
                                            ?>
                                            <span class="badge estado-badge <?= $badgeClass[$cita['estado']] ?? 'bg-secondary' ?>">
                                                <?= ucfirst($cita['estado']) ?>
                                            </span>
                                        </div>

                                        <p class="mb-2">
                                            <i class="bi bi-calendar3"></i>
                                            <strong>Fecha:</strong>
                                            <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-clock"></i>
                                            <strong>Hora:</strong>
                                            <?= date('g:i A', strtotime($cita['hora_inicio'])) ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-person"></i>
                                            <strong>Barbero:</strong>
                                            <?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-clock-history"></i>
                                            <strong>Duración:</strong>
                                            <?= $cita['duracion_minutos'] ?> minutos
                                        </p>
                                        <p class="mb-3">
                                            <i class="bi bi-cash"></i>
                                            <strong>Precio:</strong>
                                            $<?= number_format($cita['precio'], 0) ?>
                                        </p>

                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('cliente/citas/ver/' . $cita['id_cita']) ?>"
                                               class="btn btn-sm btn-outline-primary flex-grow-1">
                                                <i class="bi bi-eye"></i> Ver Detalle
                                            </a>
                                            <?php if ($cita['estado'] != 'en_proceso'): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmarCancelacion(<?= $cita['id_cita'] ?>)">
                                                    <i class="bi bi-x-circle"></i> Cancelar
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Historial -->
            <div class="tab-pane fade" id="historial">
                <?php
                $historial = array_filter($citas, function($cita) use ($hoy) {
                    return $cita['fecha_cita'] < $hoy || in_array($cita['estado'], ['completada', 'cancelada']);
                });
                ?>

                <?php if (empty($historial)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No tienes historial de citas.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Servicio</th>
                                    <th>Barbero</th>
                                    <th>Estado</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial as $cita): ?>
                                    <tr>
                                        <td>
                                            <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= date('g:i A', strtotime($cita['hora_inicio'])) ?>
                                            </small>
                                        </td>
                                        <td><?= $cita['nombre_servicio'] ?></td>
                                        <td><?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = [
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
                                            <a href="<?= base_url('cliente/citas/ver/' . $cita['id_cita']) ?>"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarCancelacion(idCita) {
            if (confirm('¿Estás seguro de que deseas cancelar esta cita?\n\nRecuerda que las citas deben cancelarse con al menos 24 horas de anticipación.')) {
                window.location.href = '<?= base_url('cliente/citas/cancelar/') ?>' + idCita;
            }
        }
    </script>
</body>
</html>
