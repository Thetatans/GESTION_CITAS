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
        .btn-primary:hover {
            background-color: #8b5a2b;
            border-color: #8b5a2b;
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
            <div class="col-md-8 mx-auto">
                <h2><i class="bi bi-pencil"></i> <?= $titulo ?></h2>
                <hr>

                <div id="mensaje" class="alert d-none"></div>

                <div class="card">
                    <div class="card-body">
                        <form id="formEditarCita">
                            <div class="row">
                                <!-- Cliente -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_cliente" class="form-label">
                                        <i class="bi bi-person"></i> Cliente *
                                    </label>
                                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                                        <option value="">Seleccionar cliente...</option>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?= $cliente['id_cliente'] ?>"
                                                <?= $cita['id_cliente'] == $cliente['id_cliente'] ? 'selected' : '' ?>>
                                                <?= esc($cliente['nombre'] . ' ' . $cliente['apellido']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Empleado -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_empleado" class="form-label">
                                        <i class="bi bi-person-badge"></i> Empleado/Barbero *
                                    </label>
                                    <select class="form-select" id="id_empleado" name="id_empleado" required>
                                        <option value="">Seleccionar empleado...</option>
                                        <?php foreach ($empleados as $empleado): ?>
                                            <option value="<?= $empleado['id_empleado'] ?>"
                                                <?= $cita['id_empleado'] == $empleado['id_empleado'] ? 'selected' : '' ?>>
                                                <?= esc($empleado['nombre'] . ' ' . $empleado['apellido']) ?>
                                                <?= !empty($empleado['especialidad']) ? '- ' . esc($empleado['especialidad']) : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Servicio -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_servicio" class="form-label">
                                        <i class="bi bi-scissors"></i> Servicio *
                                    </label>
                                    <select class="form-select" id="id_servicio" name="id_servicio" required>
                                        <option value="">Seleccionar servicio...</option>
                                        <?php foreach ($servicios as $servicio): ?>
                                            <option value="<?= $servicio['id_servicio'] ?>"
                                                data-duracion="<?= $servicio['duracion_minutos'] ?>"
                                                <?= $cita['id_servicio'] == $servicio['id_servicio'] ? 'selected' : '' ?>>
                                                <?= esc($servicio['nombre']) ?> -
                                                $<?= number_format($servicio['precio'], 0) ?>
                                                (<?= $servicio['duracion_minutos'] ?> min)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Fecha -->
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_cita" class="form-label">
                                        <i class="bi bi-calendar"></i> Fecha *
                                    </label>
                                    <input type="date"
                                           class="form-control"
                                           id="fecha_cita"
                                           name="fecha_cita"
                                           value="<?= $cita['fecha_cita'] ?>"
                                           min="<?= date('Y-m-d') ?>"
                                           required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Hora Inicio -->
                                <div class="col-md-6 mb-3">
                                    <label for="hora_inicio" class="form-label">
                                        <i class="bi bi-clock"></i> Hora de Inicio *
                                    </label>
                                    <select class="form-select" id="hora_inicio" name="hora_inicio" required>
                                        <option value="">Primero seleccione servicio, empleado y fecha</option>
                                    </select>
                                    <small class="text-muted">Los horarios disponibles se cargarán automáticamente</small>
                                </div>

                                <!-- Notas -->
                                <div class="col-md-6 mb-3">
                                    <label for="notas" class="form-label">
                                        <i class="bi bi-journal-text"></i> Notas (Opcional)
                                    </label>
                                    <textarea class="form-control"
                                              id="notas"
                                              name="notas"
                                              rows="3"
                                              placeholder="Agregar observaciones o requerimientos especiales"><?= esc($cita['notas']) ?></textarea>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Información:</strong>
                                La hora de fin se calculará automáticamente según la duración del servicio seleccionado.
                                El sistema verificará la disponibilidad del empleado antes de guardar.
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="<?= base_url('admin/citas/listado') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary" id="btnGuardar">
                                    <i class="bi bi-save"></i> Actualizar Cita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const idCita = <?= $cita['id_cita'] ?>;
        const horaInicioActual = '<?= $cita['hora_inicio'] ?>';

        // Cargar horarios disponibles cuando cambien los campos necesarios
        document.getElementById('id_empleado').addEventListener('change', cargarHorarios);
        document.getElementById('fecha_cita').addEventListener('change', cargarHorarios);
        document.getElementById('id_servicio').addEventListener('change', cargarHorarios);

        // Cargar horarios iniciales
        window.addEventListener('load', cargarHorarios);

        function cargarHorarios() {
            const empleado = document.getElementById('id_empleado').value;
            const fecha = document.getElementById('fecha_cita').value;
            const servicio = document.getElementById('id_servicio').value;
            const selectHora = document.getElementById('hora_inicio');

            if (!empleado || !fecha || !servicio) {
                selectHora.innerHTML = '<option value="">Primero seleccione servicio, empleado y fecha</option>';
                return;
            }

            selectHora.innerHTML = '<option value="">Cargando horarios disponibles...</option>';

            fetch(`<?= base_url('admin/citas/horarios-disponibles') ?>?empleado=${empleado}&fecha=${fecha}&servicio=${servicio}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        selectHora.innerHTML = '<option value="">Seleccionar hora...</option>';

                        if (data.horarios.length === 0) {
                            selectHora.innerHTML = '<option value="">No hay horarios disponibles</option>';
                        } else {
                            data.horarios.forEach(horario => {
                                const option = document.createElement('option');
                                option.value = horario;
                                option.textContent = horario;

                                // Seleccionar la hora actual si está disponible
                                if (horario === horaInicioActual) {
                                    option.selected = true;
                                }

                                selectHora.appendChild(option);
                            });

                            // Si la hora actual no está en la lista, agregarla
                            if (!data.horarios.includes(horaInicioActual)) {
                                const option = document.createElement('option');
                                option.value = horaInicioActual;
                                option.textContent = horaInicioActual + ' (Hora actual)';
                                option.selected = true;
                                selectHora.insertBefore(option, selectHora.firstChild.nextSibling);
                            }
                        }
                    } else {
                        selectHora.innerHTML = '<option value="">Error al cargar horarios</option>';
                        mostrarMensaje('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    selectHora.innerHTML = '<option value="">Error al cargar horarios</option>';
                    mostrarMensaje('error', 'Error al cargar los horarios disponibles');
                });
        }

        // Enviar formulario
        document.getElementById('formEditarCita').addEventListener('submit', function(e) {
            e.preventDefault();

            const btnGuardar = document.getElementById('btnGuardar');
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Actualizando...';

            const formData = new FormData(this);

            fetch('<?= base_url('admin/citas/actualizar/') ?>' + idCita, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje('success', data.message);
                    setTimeout(() => {
                        window.location.href = '<?= base_url('admin/citas/listado') ?>';
                    }, 1500);
                } else {
                    mostrarMensaje('error', data.message);
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = '<i class="bi bi-save"></i> Actualizar Cita';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('error', 'Error al actualizar la cita. Por favor, intente nuevamente.');
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = '<i class="bi bi-save"></i> Actualizar Cita';
            });
        });

        function mostrarMensaje(tipo, mensaje) {
            const divMensaje = document.getElementById('mensaje');
            divMensaje.className = 'alert alert-' + (tipo === 'success' ? 'success' : 'danger');
            divMensaje.innerHTML = '<i class="bi bi-' + (tipo === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + mensaje;
            divMensaje.classList.remove('d-none');

            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
