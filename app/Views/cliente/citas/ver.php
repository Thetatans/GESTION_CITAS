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
            --accent-beige: #f5e6d3;
        }
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: var(--primary-dark);
        }
        .btn-primary {
            background-color: var(--secondary-brown);
            border-color: var(--secondary-brown);
        }
        .btn-primary:hover {
            background-color: #8b5a2b;
            border-color: #8b5a2b;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: var(--secondary-brown);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
        }
        .info-label {
            font-weight: 600;
            color: var(--primary-dark);
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
                <a href="<?= base_url('cliente/mis-citas') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-calendar-check"></i> Mis Citas
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-eye"></i> <?= $titulo ?></h2>
                    <a href="<?= base_url('cliente/dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>

                <!-- Tarjeta de Detalle -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle"></i> Información de la Cita
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="info-label mb-1"><i class="bi bi-scissors"></i> Servicio:</p>
                                <p class="ms-3"><?= esc($cita['nombre_servicio']) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="info-label mb-1"><i class="bi bi-cash"></i> Precio:</p>
                                <p class="ms-3">$<?= number_format($cita['precio'], 0) ?></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="info-label mb-1"><i class="bi bi-calendar3"></i> Fecha:</p>
                                <p class="ms-3"><?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="info-label mb-1"><i class="bi bi-clock"></i> Horario:</p>
                                <p class="ms-3">
                                    <?= date('g:i A', strtotime($cita['hora_inicio'])) ?> -
                                    <?= date('g:i A', strtotime($cita['hora_fin'])) ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="info-label mb-1"><i class="bi bi-person"></i> Empleado:</p>
                                <p class="ms-3"><?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="info-label mb-1"><i class="bi bi-info-circle"></i> Estado:</p>
                                <p class="ms-3">
                                    <span class="badge <?php
                                        echo $cita['estado'] == 'pendiente' ? 'bg-warning' :
                                             ($cita['estado'] == 'confirmada' ? 'bg-success' :
                                             ($cita['estado'] == 'en_proceso' ? 'bg-info' :
                                             ($cita['estado'] == 'completada' ? 'bg-primary' : 'bg-danger')));
                                    ?>">
                                        <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <?php if (!empty($cita['notas'])): ?>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <p class="info-label mb-1"><i class="bi bi-journal-text"></i> Notas:</p>
                                    <p class="ms-3"><?= esc($cita['notas']) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-plus"></i>
                                    Creada: <?= date('d/m/Y g:i A', strtotime($cita['created_at'])) ?>
                                </small>
                            </div>
                            <?php if ($cita['updated_at'] != $cita['created_at']): ?>
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-pencil"></i>
                                        Modificada: <?= date('d/m/Y g:i A', strtotime($cita['updated_at'])) ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Acciones -->
                        <div class="mt-4 d-flex gap-2">
                            <a href="<?= base_url('cliente/dashboard') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver al Dashboard
                            </a>
                            <?php if ($cita['estado'] == 'pendiente' || $cita['estado'] == 'confirmada'): ?>
                                <button type="button" class="btn btn-danger ms-auto" onclick="confirmarCancelacion()">
                                    <i class="bi bi-x-circle"></i> Cancelar Cita
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <?php if ($cita['estado'] == 'pendiente' || $cita['estado'] == 'confirmada'): ?>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        <strong>Recordatorio:</strong> Las citas deben cancelarse con al menos 24 horas de anticipación.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarCancelacion() {
            if (confirm('¿Estás seguro de que deseas cancelar esta cita?\n\nRecuerda que las citas deben cancelarse con al menos 24 horas de anticipación.')) {
                window.location.href = '<?= base_url('cliente/citas/cancelar/' . $cita['id_cita']) ?>';
            }
        }
    </script>
</body>
</html>
