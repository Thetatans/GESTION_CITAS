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
            background: var(--cafe) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .btn-outline-light:hover {
            background: var(--azul-oscuro);
            border-color: var(--azul-oscuro);
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

        .alert-info {
            background-color: white;
            border: 2px solid var(--cafe);
            border-left: 5px solid var(--azul-oscuro);
            color: var(--gris);
        }

        .alert-info h4 {
            color: var(--cafe);
        }

        .card {
            border: 2px solid var(--azul-oscuro);
            border-radius: 15px;
            overflow: hidden;
        }

        .card i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .card-custom-1 {
            background: var(--azul-oscuro);
            color: white;
        }

        .card-custom-2 {
            background: var(--cafe);
            color: white;
        }

        .card .btn-light {
            background: white;
            border: none;
            color: var(--azul-oscuro);
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 10px;
        }

        .card .btn-light:hover {
            background: var(--beige);
        }

        .card-header {
            background: var(--cafe) !important;
            color: white;
            font-weight: 600;
        }

        .card-body .text-muted {
            color: var(--gris) !important;
        }

        footer {
            background: var(--azul-oscuro) !important;
            color: white;
        }

        footer .text-muted {
            color: rgba(255,255,255,0.7) !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('cliente/dashboard') ?>">
                <i class="bi bi-person-circle"></i> Cliente - Barbería
            </a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> <?= esc($usuario_nombre) ?>
                </span>
                <a href="<?= base_url('manual') ?>" class="btn btn-outline-light btn-sm me-2" title="Descargar Manual de Usuario (PDF)">
                    <i class="bi bi-file-earmark-pdf"></i> Manual PDF
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container mt-5">
            <h1><?= esc($titulo) ?></h1>
            <hr>

            <div class="alert alert-info">
                <h4>¡Bienvenido!</h4>
                <p class="mb-0">Has iniciado sesión correctamente con rol de <strong>CLIENTE</strong>.</p>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="card card-custom-1">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-plus"></i>
                            <h3>Agendar Cita</h3>
                            <a href="<?= base_url('cliente/agendar') ?>" class="btn btn-light btn-sm">
                                Agendar ahora
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card card-custom-2">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check"></i>
                            <h3>Mis Citas</h3>
                            <a href="<?= base_url('cliente/mis-citas') ?>" class="btn btn-light btn-sm">
                                Ver mis citas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Próximas Citas</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($proximasCitas)): ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                                    <p class="text-muted mt-3">No tienes citas programadas</p>
                                    <a href="<?= base_url('cliente/agendar') ?>" class="btn btn-primary mt-2">
                                        <i class="bi bi-calendar-plus"></i> Agendar una cita
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($proximasCitas as $cita): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-primary h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="bi bi-scissors"></i> <?= esc($cita['nombre_servicio']) ?>
                                                    </h5>
                                                    <hr>
                                                    <p class="mb-2">
                                                        <strong><i class="bi bi-calendar3"></i> Fecha:</strong>
                                                        <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?>
                                                    </p>
                                                    <p class="mb-2">
                                                        <strong><i class="bi bi-clock"></i> Hora:</strong>
                                                        <?= date('g:i A', strtotime($cita['hora_inicio'])) ?>
                                                    </p>
                                                    <p class="mb-2">
                                                        <strong><i class="bi bi-person"></i> Empleado:</strong>
                                                        <?= esc($cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']) ?>
                                                    </p>
                                                    <p class="mb-2">
                                                        <strong><i class="bi bi-cash"></i> Precio:</strong>
                                                        $<?= number_format($cita['precio'], 0) ?>
                                                    </p>
                                                    <p class="mb-3">
                                                        <strong><i class="bi bi-info-circle"></i> Estado:</strong>
                                                        <span class="badge
                                                            <?php
                                                                echo $cita['estado'] == 'pendiente' ? 'bg-warning' :
                                                                     ($cita['estado'] == 'confirmada' ? 'bg-success' :
                                                                     ($cita['estado'] == 'en_proceso' ? 'bg-info' :
                                                                     ($cita['estado'] == 'completada' ? 'bg-primary' : 'bg-danger')));
                                                            ?>">
                                                            <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                                        </span>
                                                    </p>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('cliente/citas/ver/' . $cita['id_cita']) ?>"
                                                           class="btn btn-sm btn-outline-primary flex-grow-1">
                                                            <i class="bi bi-eye"></i> Ver Detalle
                                                        </a>
                                                        <?php if ($cita['estado'] == 'pendiente' || $cita['estado'] == 'confirmada'): ?>
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
                                <div class="text-center mt-3">
                                    <a href="<?= base_url('cliente/mis-citas') ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-list-ul"></i> Ver todas mis citas
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-auto py-3">
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
    <script>
        function confirmarCancelacion(idCita) {
            if (confirm('¿Estás seguro de que deseas cancelar esta cita?\n\nRecuerda que las citas deben cancelarse con al menos 24 horas de anticipación.')) {
                window.location.href = '<?= base_url('cliente/citas/cancelar/') ?>' + idCita;
            }
        }
    </script>
</body>
</html>
