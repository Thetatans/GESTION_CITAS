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
        .card {
            border: 2px solid var(--primary-dark);
            border-radius: 15px;
        }
        .card-header {
            background-color: var(--primary-dark);
            color: white;
            font-weight: 600;
        }
        .cita-proxima {
            background-color: #d4edda;
        }
        .hora-actual {
            background-color: #fff3cd;
            font-weight: bold;
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
                <li class="breadcrumb-item active" aria-current="page">Citas de Hoy</li>
            </ol>
        </nav>

        <div class="row mb-4">
            <div class="col-md-8">
                <h2><i class="bi bi-calendar-day"></i> <?= $titulo ?></h2>
                <p class="text-muted">
                    <i class="bi bi-calendar3"></i> <?= date('l, d \de F \de Y', strtotime($fecha)) ?>
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-clock"></i> <strong><?= date('g:i A') ?></strong>
                </div>
            </div>
        </div>

        <!-- Resumen del día -->
        <div class="row mb-4">
            <?php
            $totalCitas = count($citas);
            $citasPendientes = count(array_filter($citas, fn($c) => $c['estado'] === 'pendiente'));
            $citasCompletadas = count(array_filter($citas, fn($c) => $c['estado'] === 'completada'));
            $citasEnProceso = count(array_filter($citas, fn($c) => $c['estado'] === 'en_proceso'));
            ?>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?= $totalCitas ?></h3>
                        <p class="mb-0">Total Citas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning"><?= $citasPendientes ?></h3>
                        <p class="mb-0">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info"><?= $citasEnProceso ?></h3>
                        <p class="mb-0">En Proceso</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?= $citasCompletadas ?></h3>
                        <p class="mb-0">Completadas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de citas -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list"></i> Lista de Citas del Día
            </div>
            <div class="card-body">
                <?php if (empty($citas)): ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> No tienes citas programadas para hoy.
                        <br>
                        <small>¡Disfruta de tu día libre!</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="100">Hora</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $horaActual = date('H:i:s');
                                foreach ($citas as $cita):
                                    // Determinar si es la próxima cita
                                    $esProxima = ($cita['hora_inicio'] > $horaActual && $cita['estado'] !== 'completada' && $cita['estado'] !== 'cancelada');
                                    $claseProxima = $esProxima ? 'cita-proxima' : '';
                                ?>
                                    <tr class="<?= $claseProxima ?>" id="cita-<?= $cita['id_cita'] ?>">
                                        <td>
                                            <strong><?= date('g:i A', strtotime($cita['hora_inicio'])) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= $cita['duracion_servicio'] ?> min</small>
                                        </td>
                                        <td>
                                            <strong><?= esc($cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']) ?></strong>
                                            <?php if ($esProxima): ?>
                                                <br><span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock"></i> Próxima
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($cita['nombre_servicio']) ?></td>
                                        <td>
                                            <a href="tel:<?= esc($cita['telefono_cliente']) ?>" class="text-decoration-none">
                                                <i class="bi bi-telephone"></i> <?= esc($cita['telefono_cliente']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = [
                                                'pendiente' => 'bg-warning text-dark',
                                                'confirmada' => 'bg-info',
                                                'en_proceso' => 'bg-primary',
                                                'completada' => 'bg-success',
                                                'cancelada' => 'bg-danger'
                                            ];
                                            ?>
                                            <span class="badge <?= $badgeClass[$cita['estado']] ?? 'bg-secondary' ?>" id="badge-<?= $cita['id_cita'] ?>">
                                                <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('empleado/citas/ver/' . $cita['id_cita']) ?>"
                                                   class="btn btn-sm btn-info" title="Ver detalle">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <?php if ($cita['estado'] === 'pendiente'): ?>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'confirmada')"
                                                            title="Confirmar">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'cancelada')"
                                                            title="Cancelar">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                <?php elseif ($cita['estado'] === 'confirmada'): ?>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'en_proceso')"
                                                            title="Iniciar">
                                                        <i class="bi bi-play-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'cancelada')"
                                                            title="Cancelar">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                <?php elseif ($cita['estado'] === 'en_proceso'): ?>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            onclick="cambiarEstado(<?= $cita['id_cita'] ?>, 'completada')"
                                                            title="Completar">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 row">
                        <div class="col-md-6">
                            <p class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Las filas destacadas en verde son las próximas citas pendientes
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Total: <?= count($citas) ?> cita(s)</strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Botones de navegación -->
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="<?= base_url('empleado/citas') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Ver Todas Mis Citas
                </a>
                <a href="<?= base_url('empleado/dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-house"></i> Ir al Dashboard
                </a>
                <button onclick="location.reload()" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                </button>
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
        function cambiarEstado(idCita, nuevoEstado) {
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

        // Auto-actualizar cada 2 minutos
        setTimeout(() => {
            location.reload();
        }, 120000);
    </script>
</body>
</html>
