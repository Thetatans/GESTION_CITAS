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
            --beige: #f5e6d3;
        }
        body {
            background-color: var(--beige);
        }
        .navbar {
            background-color: var(--primary-dark);
        }
        .btn-primary {
            background-color: var(--secondary-brown);
            border-color: var(--secondary-brown);
        }
        .btn-primary:hover {
            background-color: #523317;
            border-color: #523317;
        }
        .card {
            border: 2px solid var(--primary-dark);
            border-radius: 15px;
        }
        .card-header {
            background-color: var(--primary-dark);
            color: white;
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
            <a class="navbar-brand" href="<?= base_url('empleado/dashboard') ?>">
                <i class="bi bi-scissors"></i> Barbería - Empleado
            </a>
            <div class="text-white">
                <a href="<?= base_url('empleado/agenda') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-calendar-week"></i> Agenda
                </a>
                <a href="<?= base_url('empleado/citas') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-list-check"></i> Mis Citas
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('empleado/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('empleado/citas') ?>">Mis Citas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detalle de Cita</li>
            </ol>
        </nav>

        <div class="row mb-4">
            <div class="col-12">
                <h2><i class="bi bi-file-text"></i> <?= $titulo ?></h2>
            </div>
        </div>

        <!-- Información de la Cita -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-calendar-event"></i> Información de la Cita
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="info-label mb-1">Fecha:</p>
                                <p class="fs-5">
                                    <i class="bi bi-calendar3"></i>
                                    <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label mb-1">Hora:</p>
                                <p class="fs-5">
                                    <i class="bi bi-clock"></i>
                                    <?= date('g:i A', strtotime($cita['hora_inicio'])) ?> -
                                    <?= date('g:i A', strtotime($cita['hora_fin'])) ?>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p class="info-label mb-1">Servicio:</p>
                                <p class="fs-5">
                                    <i class="bi bi-scissors"></i> <?= esc($cita['nombre_servicio']) ?>
                                    <span class="badge bg-secondary ms-2">
                                        <?= $cita['duracion_servicio'] ?> min
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="info-label mb-1">Precio:</p>
                                <p class="fs-4 text-success">
                                    <i class="bi bi-currency-dollar"></i>
                                    $<?= number_format($cita['precio'], 0, ',', '.') ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label mb-1">Estado:</p>
                                <p>
                                    <?php
                                    $badgeClass = [
                                        'pendiente' => 'bg-warning text-dark',
                                        'confirmada' => 'bg-info',
                                        'en_proceso' => 'bg-primary',
                                        'completada' => 'bg-success',
                                        'cancelada' => 'bg-danger'
                                    ];
                                    ?>
                                    <span class="badge <?= $badgeClass[$cita['estado']] ?? 'bg-secondary' ?> fs-6" id="badge-estado">
                                        <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <?php if (!empty($cita['notas'])): ?>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="info-label mb-1">Notas:</p>
                                    <div class="alert alert-light">
                                        <i class="bi bi-sticky"></i> <?= nl2br(esc($cita['notas'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card mb-4" id="acciones-card">
                    <div class="card-header">
                        <i class="bi bi-gear"></i> Acciones
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2 d-md-flex">
                            <?php if ($cita['estado'] === 'pendiente'): ?>
                                <button class="btn btn-info" onclick="cambiarEstado('confirmada')">
                                    <i class="bi bi-check-circle"></i> Confirmar Cita
                                </button>
                                <button class="btn btn-danger" onclick="cambiarEstado('cancelada')">
                                    <i class="bi bi-x-circle"></i> Cancelar Cita
                                </button>
                            <?php elseif ($cita['estado'] === 'confirmada'): ?>
                                <button class="btn btn-primary" onclick="cambiarEstado('en_proceso')">
                                    <i class="bi bi-play-circle"></i> Iniciar Servicio
                                </button>
                                <button class="btn btn-danger" onclick="cambiarEstado('cancelada')">
                                    <i class="bi bi-x-circle"></i> Cancelar Cita
                                </button>
                            <?php elseif ($cita['estado'] === 'en_proceso'): ?>
                                <button class="btn btn-success" onclick="cambiarEstado('completada')">
                                    <i class="bi bi-check2-all"></i> Completar Servicio
                                </button>
                            <?php elseif ($cita['estado'] === 'completada'): ?>
                                <div class="alert alert-success mb-0">
                                    <i class="bi bi-check-circle-fill"></i> Esta cita ha sido completada exitosamente.
                                </div>
                            <?php elseif ($cita['estado'] === 'cancelada'): ?>
                                <div class="alert alert-danger mb-0">
                                    <i class="bi bi-x-circle-fill"></i> Esta cita fue cancelada.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-person"></i> Información del Cliente
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="info-label mb-1">Nombre:</p>
                            <p><strong><?= esc($cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']) ?></strong></p>
                        </div>

                        <div class="mb-3">
                            <p class="info-label mb-1">Teléfono:</p>
                            <p>
                                <a href="tel:<?= esc($cita['telefono_cliente']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-telephone"></i> <?= esc($cita['telefono_cliente']) ?>
                                </a>
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="info-label mb-1">Email:</p>
                            <p>
                                <?php if (!empty($cita['email_cliente'])): ?>
                                    <a href="mailto:<?= esc($cita['email_cliente']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-envelope"></i> <?= esc($cita['email_cliente']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No registrado</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones de navegación -->
                <div class="d-grid gap-2">
                    <a href="<?= base_url('empleado/citas') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a Mis Citas
                    </a>
                    <a href="<?= base_url('empleado/dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-house"></i> Ir al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 py-3" style="background-color: var(--primary-dark); color: white;">
        <div class="container text-center">
            <small>
                <p><strong>Sistema de Gestión de Citas</strong></p>
                <p>Barbería y Spa - Proyecto DICO TELECOMUNICACIONES</p>
                <p>Desarrollado por: <strong>Ilich Esteban Reyes Botia</strong></p>
                <p>Aprendiz SENA</p>
            </small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cambiarEstado(nuevoEstado) {
            const mensajes = {
                'confirmada': '¿Confirmar esta cita?',
                'cancelada': '¿Estás seguro de cancelar esta cita?',
                'en_proceso': '¿Iniciar el servicio ahora?',
                'completada': '¿Marcar esta cita como completada?'
            };

            if (!confirm(mensajes[nuevoEstado])) {
                return;
            }

            // Deshabilitar botones
            const botones = document.querySelectorAll('#acciones-card button');
            botones.forEach(btn => btn.disabled = true);

            // Enviar petición AJAX
            fetch('<?= base_url('empleado/citas/actualizar-estado/' . $cita['id_cita']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'estado=' + nuevoEstado
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    botones.forEach(btn => btn.disabled = false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el estado de la cita');
                botones.forEach(btn => btn.disabled = false);
            });
        }
    </script>
</body>
</html>
