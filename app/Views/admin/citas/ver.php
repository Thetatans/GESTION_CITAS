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
        .info-label {
            font-weight: bold;
            color: var(--text-gray);
        }
        .estado-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
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
            <div>
                <a href="<?= base_url('admin/citas') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-calendar"></i> Calendario
                </a>
                <a href="<?= base_url('admin/citas/listado') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-list"></i> Listado
                </a>
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-house"></i> Inicio
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2><i class="bi bi-eye"></i> <?= $titulo ?></h2>
                <hr>

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

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle"></i> Información de la Cita #<?= $cita['id_cita'] ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Información del Cliente -->
                            <div class="col-md-6 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-person"></i> Cliente
                                </h6>
                                <p>
                                    <span class="info-label">Nombre:</span>
                                    <?= esc($cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']) ?>
                                </p>
                                <p>
                                    <span class="info-label">Teléfono:</span>
                                    <?= esc($cita['telefono_cliente']) ?>
                                </p>
                            </div>

                            <!-- Información del Empleado -->
                            <div class="col-md-6 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-person-badge"></i> Empleado/Barbero
                                </h6>
                                <p>
                                    <span class="info-label">Nombre:</span>
                                    <?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?>
                                </p>
                                <p>
                                    <span class="info-label">Especialidad:</span>
                                    <?= esc($cita['especialidad']) ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Información del Servicio -->
                            <div class="col-md-6 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-scissors"></i> Servicio
                                </h6>
                                <p>
                                    <span class="info-label">Servicio:</span>
                                    <?= esc($cita['nombre_servicio']) ?>
                                </p>
                                <p>
                                    <span class="info-label">Precio:</span>
                                    $<?= number_format($cita['precio'], 0) ?>
                                </p>
                                <p>
                                    <span class="info-label">Duración:</span>
                                    <?= $cita['duracion_minutos'] ?> minutos
                                </p>
                            </div>

                            <!-- Información de la Cita -->
                            <div class="col-md-6 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-calendar-event"></i> Detalles de la Cita
                                </h6>
                                <p>
                                    <span class="info-label">Fecha:</span>
                                    <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?>
                                </p>
                                <p>
                                    <span class="info-label">Hora Inicio:</span>
                                    <?= date('g:i A', strtotime($cita['hora_inicio'])) ?>
                                </p>
                                <p>
                                    <span class="info-label">Hora Fin:</span>
                                    <?= date('g:i A', strtotime($cita['hora_fin'])) ?>
                                </p>
                                <p>
                                    <span class="info-label">Estado:</span>
                                    <?php
                                    $badgeClass = [
                                        'pendiente' => 'bg-warning text-dark',
                                        'confirmada' => 'bg-info',
                                        'en_proceso' => 'bg-primary',
                                        'completada' => 'bg-success',
                                        'cancelada' => 'bg-danger'
                                    ];
                                    ?>
                                    <span class="badge estado-badge <?= $badgeClass[$cita['estado']] ?? 'bg-secondary' ?>">
                                        <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <?php if (!empty($cita['notas'])): ?>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="border-bottom pb-2 mb-3">
                                        <i class="bi bi-journal-text"></i> Notas
                                    </h6>
                                    <p><?= esc($cita['notas']) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-clock-history"></i> Registro
                                </h6>
                                <p>
                                    <span class="info-label">Creada:</span>
                                    <?= date('d/m/Y g:i A', strtotime($cita['created_at'])) ?>
                                </p>
                                <p>
                                    <span class="info-label">Última actualización:</span>
                                    <?= date('d/m/Y g:i A', strtotime($cita['updated_at'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/citas/listado') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>

                            <?php if ($cita['estado'] !== 'cancelada' && $cita['estado'] !== 'completada'): ?>
                                <a href="<?= base_url('admin/citas/editar/' . $cita['id_cita']) ?>" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            <?php endif; ?>

                            <!-- Botones de cambio de estado -->
                            <?php if ($cita['estado'] === 'pendiente'): ?>
                                <button type="button" class="btn btn-info" onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'confirmada')">
                                    <i class="bi bi-check-circle"></i> Confirmar
                                </button>
                            <?php endif; ?>

                            <?php if ($cita['estado'] === 'confirmada'): ?>
                                <button type="button" class="btn btn-primary" onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'en_proceso')">
                                    <i class="bi bi-play-circle"></i> Iniciar
                                </button>
                            <?php endif; ?>

                            <?php if ($cita['estado'] === 'en_proceso'): ?>
                                <button type="button" class="btn btn-success" onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'completada')">
                                    <i class="bi bi-check-all"></i> Completar
                                </button>
                            <?php endif; ?>

                            <?php if ($cita['estado'] !== 'cancelada' && $cita['estado'] !== 'completada'): ?>
                                <button type="button" class="btn btn-danger" onclick="confirmarCancelacion(<?= $cita['id_cita'] ?>)">
                                    <i class="bi bi-x-circle"></i> Cancelar Cita
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cambiarEstado(idCita, nuevoEstado) {
            if (confirm('¿Está seguro de cambiar el estado de esta cita?')) {
                const formData = new FormData();
                formData.append('estado', nuevoEstado);

                fetch('<?= base_url('admin/citas/cambiar-estado/') ?>' + idCita, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cambiar el estado');
                });
            }
        }

        function confirmarCancelacion(idCita) {
            if (confirm('¿Está seguro de que desea cancelar esta cita?\n\nEsta acción no se puede deshacer.')) {
                window.location.href = '<?= base_url('admin/citas/eliminar/') ?>' + idCita;
            }
        }
    </script>
</body>
</html>
