<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <?php
    // Función helper para obtener el color del badge según el estado
    if (!function_exists('obtenerColorEstado')) {
        function obtenerColorEstado($estado) {
            $colores = [
                'pendiente' => 'warning',
                'confirmada' => 'info',
                'en_proceso' => 'primary',
                'completada' => 'success',
                'cancelada' => 'danger'
            ];
            return $colores[$estado] ?? 'secondary';
        }
    }
    ?>

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
            background: var(--gris) !important;
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

        .alert-success {
            background-color: white;
            border: 2px solid var(--gris);
            border-left: 5px solid var(--cafe);
            color: var(--gris);
        }

        .alert-success h4 {
            color: var(--gris);
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
            background: var(--gris);
            color: white;
        }

        .card-custom-2 {
            background: var(--azul-oscuro);
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
            background: var(--gris) !important;
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
            <a class="navbar-brand" href="<?= base_url('empleado/dashboard') ?>">
                <i class="bi bi-person-badge"></i> Empleado - Barbería
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

            <div class="alert alert-success">
                <h4>¡Bienvenido Empleado!</h4>
                <p class="mb-0">Has iniciado sesión correctamente con rol de <strong>EMPLEADO</strong>.</p>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="card card-custom-1">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar2-week"></i>
                            <h3>Mi Agenda</h3>
                            <a href="<?= base_url('empleado/agenda') ?>" class="btn btn-light btn-sm">
                                Ver agenda
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card card-custom-2">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check"></i>
                            <h3>Mis Citas</h3>
                            <a href="<?= base_url('empleado/citas') ?>" class="btn btn-light btn-sm">
                                Ver citas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Citas de Hoy - <?= date('d/m/Y') ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($citas_hoy)): ?>
                                <p class="text-muted text-center">No hay citas programadas para hoy</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead style="background-color: var(--gris); color: white;">
                                            <tr>
                                                <th>Hora</th>
                                                <th>Cliente</th>
                                                <th>Servicio</th>
                                                <th>Teléfono</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($citas_hoy as $cita): ?>
                                                <tr id="cita-<?= $cita['id_cita'] ?>">
                                                    <td><strong><?= date('g:i A', strtotime($cita['hora_inicio'])) ?></strong></td>
                                                    <td><?= esc($cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']) ?></td>
                                                    <td><?= esc($cita['nombre_servicio']) ?></td>
                                                    <td>
                                                        <a href="tel:<?= esc($cita['telefono_cliente']) ?>" class="text-decoration-none">
                                                            <i class="bi bi-telephone"></i> <?= esc($cita['telefono_cliente']) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?= obtenerColorEstado($cita['estado']) ?>" id="badge-<?= $cita['id_cita'] ?>">
                                                            <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($cita['estado'] === 'pendiente'): ?>
                                                            <button class="btn btn-sm btn-info" onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'confirmada')">
                                                                <i class="bi bi-check-circle"></i> Confirmar
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'cancelada')">
                                                                <i class="bi bi-x-circle"></i> Cancelar
                                                            </button>
                                                        <?php elseif ($cita['estado'] === 'confirmada'): ?>
                                                            <button class="btn btn-sm btn-primary" onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'en_proceso')">
                                                                <i class="bi bi-play-circle"></i> Iniciar
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'cancelada')">
                                                                <i class="bi bi-x-circle"></i> Cancelar
                                                            </button>
                                                        <?php elseif ($cita['estado'] === 'en_proceso'): ?>
                                                            <button class="btn btn-sm btn-success" onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'completada')">
                                                                <i class="bi bi-check2-all"></i> Completar
                                                            </button>
                                                        <?php elseif ($cita['estado'] === 'completada'): ?>
                                                            <span class="text-success"><i class="bi bi-check-circle-fill"></i> Finalizada</span>
                                                        <?php elseif ($cita['estado'] === 'cancelada'): ?>
                                                            <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Cancelada</span>
                                                        <?php endif; ?>
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
        /**
         * Función para cambiar el estado de una cita
         * @param {number} idCita - ID de la cita
         * @param {string} nuevoEstado - Nuevo estado (confirmada, cancelada, en_proceso, completada)
         */
        function cambiarEstado(idCita, nuevoEstado) {
            // Confirmar acción con el usuario
            const mensajes = {
                'confirmada': '¿Deseas confirmar esta cita?',
                'cancelada': '¿Estás seguro de cancelar esta cita?',
                'en_proceso': '¿Iniciar el servicio de esta cita?',
                'completada': '¿Marcar esta cita como completada?'
            };

            if (!confirm(mensajes[nuevoEstado])) {
                return;
            }

            // Deshabilitar botones para evitar múltiples clics
            const fila = document.getElementById('cita-' + idCita);
            const botones = fila.querySelectorAll('button');
            botones.forEach(btn => btn.disabled = true);

            // Enviar petición AJAX
            fetch('<?= base_url('empleado/citas/actualizar-estado/') ?>' + idCita, {
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
                    // Mostrar mensaje de éxito
                    alert(data.message);
                    // Recargar la página para mostrar los cambios
                    location.reload();
                } else {
                    // Mostrar mensaje de error
                    alert('Error: ' + data.message);
                    // Rehabilitar botones
                    botones.forEach(btn => btn.disabled = false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el estado de la cita');
                // Rehabilitar botones
                botones.forEach(btn => btn.disabled = false);
            });
        }
    </script>
</body>
</html>
