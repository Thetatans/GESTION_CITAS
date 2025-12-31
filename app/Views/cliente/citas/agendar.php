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
        .step-wizard {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 1rem;
            position: relative;
        }
        .step::after {
            content: '';
            position: absolute;
            top: 2rem;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #dee2e6;
            z-index: -1;
        }
        .step:last-child::after {
            display: none;
        }
        .step-number {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .step.active .step-number {
            background: var(--secondary-brown);
            color: white;
        }
        .step.completed .step-number {
            background: #198754;
            color: white;
        }
        .horarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.5rem;
        }
        .horario-btn {
            padding: 0.75rem 0.5rem;
            border: 2px solid #dee2e6;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            text-align: center;
        }
        .horario-btn:hover {
            border-color: var(--secondary-brown);
            background-color: var(--accent-beige);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .horario-btn.active {
            background-color: var(--secondary-brown);
            color: white;
            border-color: var(--secondary-brown);
            box-shadow: 0 4px 12px rgba(107, 68, 35, 0.3);
        }
        .servicio-card {
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .servicio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .servicio-card.selected {
            border-color: var(--secondary-brown);
            background-color: var(--accent-beige);
        }
        .empleado-item {
            cursor: pointer;
            transition: all 0.3s;
        }
        .empleado-item:hover {
            background-color: var(--accent-beige);
        }
        .empleado-item.active {
            background-color: var(--accent-beige);
            border-left: 4px solid var(--secondary-brown);
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
            <div class="col-lg-10">
                <h2 class="mb-4"><i class="bi bi-calendar-plus"></i> <?= $titulo ?></h2>

                <!-- Pasos del wizard -->
                <div class="step-wizard">
                    <div class="step active" id="step1-indicator">
                        <div class="step-number">1</div>
                        <div class="step-label">Servicio</div>
                    </div>
                    <div class="step" id="step2-indicator">
                        <div class="step-number">2</div>
                        <div class="step-label">Empleado</div>
                    </div>
                    <div class="step" id="step3-indicator">
                        <div class="step-number">3</div>
                        <div class="step-label">Fecha y Hora</div>
                    </div>
                    <div class="step" id="step4-indicator">
                        <div class="step-number">4</div>
                        <div class="step-label">Confirmación</div>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="card">
                    <div class="card-body">
                        <form id="formAgendar">
                            <!-- Paso 1: Seleccionar Servicio -->
                            <div class="paso active" id="paso1">
                                <h4 class="mb-3">Selecciona el servicio</h4>
                                <div class="row">
                                    <?php foreach ($servicios as $servicio): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card servicio-card" data-servicio="<?= $servicio['id_servicio'] ?>"
                                                 data-duracion="<?= $servicio['duracion_minutos'] ?>">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= $servicio['nombre'] ?></h5>
                                                    <p class="card-text"><?= $servicio['descripcion'] ?></p>
                                                    <p class="mb-0">
                                                        <strong>Precio:</strong> $<?= number_format($servicio['precio'], 0) ?>
                                                        <br>
                                                        <strong>Duración:</strong> <?= $servicio['duracion_minutos'] ?> minutos
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="id_servicio" id="id_servicio" required>
                            </div>

                            <!-- Paso 2: Seleccionar Empleado -->
                            <div class="paso" id="paso2" style="display: none;">
                                <h4 class="mb-3">Selecciona tu barbero preferido</h4>
                                <div class="list-group">
                                    <?php foreach ($empleados as $empleado): ?>
                                        <div class="list-group-item list-group-item-action empleado-item"
                                             data-empleado="<?= $empleado['id_empleado'] ?>">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">
                                                    <i class="bi bi-person-circle"></i>
                                                    <?= $empleado['nombre'] . ' ' . $empleado['apellido'] ?>
                                                </h5>
                                            </div>
                                            <p class="mb-1">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                Especialidad: <?= $empleado['especialidad'] ?>
                                            </p>
                                            <small>Teléfono: <?= $empleado['telefono'] ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="id_empleado" id="id_empleado" required>
                            </div>

                            <!-- Paso 3: Seleccionar Fecha y Hora -->
                            <div class="paso" id="paso3" style="display: none;">
                                <h4 class="mb-3">Selecciona fecha y hora</h4>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="card">
                                            <div class="card-body">
                                                <label class="form-label fw-bold">
                                                    <i class="bi bi-calendar3"></i> Selecciona la fecha
                                                </label>
                                                <input type="date" class="form-control form-control-lg"
                                                       name="fecha_cita" id="fecha_cita"
                                                       min="<?= date('Y-m-d') ?>" required>
                                                <small class="text-muted mt-2 d-block">
                                                    <i class="bi bi-info-circle"></i> Horario de atención: 9:00 AM - 7:00 PM
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="card">
                                            <div class="card-body">
                                                <div id="loadingHorarios" style="display: none;" class="text-center py-5">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Cargando...</span>
                                                    </div>
                                                    <p class="mt-2">Cargando horarios disponibles...</p>
                                                </div>
                                                <div id="horariosContainer" style="display: none;">
                                                    <label class="form-label fw-bold">
                                                        <i class="bi bi-clock"></i> Horarios disponibles
                                                    </label>

                                                    <!-- Horarios de la mañana -->
                                                    <div class="mb-4" id="horariosMañana">
                                                        <h6 class="text-muted mb-2">
                                                            <i class="bi bi-sunrise"></i> Mañana (9:00 AM - 12:00 PM)
                                                        </h6>
                                                        <div class="horarios-grid" id="gridMañana">
                                                            <!-- Se llenarán dinámicamente -->
                                                        </div>
                                                    </div>

                                                    <!-- Horarios de la tarde -->
                                                    <div id="horariosTarde">
                                                        <h6 class="text-muted mb-2">
                                                            <i class="bi bi-sunset"></i> Tarde (12:00 PM - 7:00 PM)
                                                        </h6>
                                                        <div class="horarios-grid" id="gridTarde">
                                                            <!-- Se llenarán dinámicamente -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="noHorarios" style="display: none;" class="text-center py-4">
                                                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                                    <p class="text-muted mt-2">No hay horarios disponibles para esta fecha</p>
                                                    <small>Por favor, selecciona otra fecha</small>
                                                </div>
                                                <div id="mensajeInicial" class="text-center py-5 text-muted">
                                                    <i class="bi bi-calendar-check" style="font-size: 3rem;"></i>
                                                    <p class="mt-2">Selecciona una fecha para ver los horarios disponibles</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="hora_inicio" id="hora_inicio" required>
                            </div>

                            <!-- Paso 4: Confirmación -->
                            <div class="paso" id="paso4" style="display: none;">
                                <h4 class="mb-3">Confirma tu cita</h4>
                                <div class="alert alert-info">
                                    <h5><i class="bi bi-info-circle"></i> Resumen de tu cita</h5>
                                    <hr>
                                    <p><strong>Servicio:</strong> <span id="resumen_servicio"></span></p>
                                    <p><strong>Empleado:</strong> <span id="resumen_empleado"></span></p>
                                    <p><strong>Fecha:</strong> <span id="resumen_fecha"></span></p>
                                    <p><strong>Hora:</strong> <span id="resumen_hora"></span></p>
                                    <p><strong>Precio:</strong> <span id="resumen_precio"></span></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notas adicionales (opcional)</label>
                                    <textarea class="form-control" name="notas" rows="3"
                                              placeholder="Ej: Preferencias de estilo, alergias, etc."></textarea>
                                </div>
                            </div>

                            <!-- Botones de navegación -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" id="btnAnterior" style="display: none;">
                                    <i class="bi bi-arrow-left"></i> Anterior
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSiguiente">
                                    Siguiente <i class="bi bi-arrow-right"></i>
                                </button>
                                <button type="button" class="btn btn-success" id="btnConfirmar" style="display: none;">
                                    <i class="bi bi-check-circle"></i> Confirmar Cita
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
        let pasoActual = 1;
        const totalPasos = 4;
        const datosSeleccionados = {};

        document.addEventListener('DOMContentLoaded', function() {
            // Selección de servicio
            document.querySelectorAll('.servicio-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.servicio-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    datosSeleccionados.servicio = {
                        id: this.dataset.servicio,
                        nombre: this.querySelector('.card-title').textContent,
                        precio: this.querySelector('.card-text').nextElementSibling.innerHTML.split('$')[1].split('<')[0],
                        duracion: this.dataset.duracion
                    };
                    document.getElementById('id_servicio').value = this.dataset.servicio;
                });
            });

            // Selección de empleado
            document.querySelectorAll('.empleado-item').forEach(item => {
                item.addEventListener('click', function() {
                    document.querySelectorAll('.empleado-item').forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    datosSeleccionados.empleado = {
                        id: this.dataset.empleado,
                        nombre: this.querySelector('h5').textContent.trim()
                    };
                    document.getElementById('id_empleado').value = this.dataset.empleado;
                });
            });

            // Cambio de fecha
            document.getElementById('fecha_cita').addEventListener('change', cargarHorarios);

            // Botones de navegación
            document.getElementById('btnSiguiente').addEventListener('click', siguientePaso);
            document.getElementById('btnAnterior').addEventListener('click', anteriorPaso);
            document.getElementById('btnConfirmar').addEventListener('click', confirmarCita);
        });

        function siguientePaso() {
            if (validarPaso(pasoActual)) {
                pasoActual++;
                mostrarPaso(pasoActual);
            }
        }

        function anteriorPaso() {
            pasoActual--;
            mostrarPaso(pasoActual);
        }

        function mostrarPaso(paso) {
            document.querySelectorAll('.paso').forEach(p => p.style.display = 'none');
            document.getElementById('paso' + paso).style.display = 'block';

            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + paso + '-indicator').classList.add('active');

            for (let i = 1; i < paso; i++) {
                document.getElementById('step' + i + '-indicator').classList.add('completed');
            }

            document.getElementById('btnAnterior').style.display = paso > 1 ? 'block' : 'none';
            document.getElementById('btnSiguiente').style.display = paso < totalPasos ? 'block' : 'none';
            document.getElementById('btnConfirmar').style.display = paso === totalPasos ? 'block' : 'none';

            if (paso === totalPasos) {
                mostrarResumen();
            }
        }

        function validarPaso(paso) {
            switch(paso) {
                case 1:
                    if (!document.getElementById('id_servicio').value) {
                        alert('Por favor, selecciona un servicio');
                        return false;
                    }
                    break;
                case 2:
                    if (!document.getElementById('id_empleado').value) {
                        alert('Por favor, selecciona un empleado');
                        return false;
                    }
                    break;
                case 3:
                    if (!document.getElementById('fecha_cita').value || !document.getElementById('hora_inicio').value) {
                        alert('Por favor, selecciona fecha y hora');
                        return false;
                    }
                    break;
            }
            return true;
        }

        function cargarHorarios() {
            const empleado = document.getElementById('id_empleado').value;
            const servicio = document.getElementById('id_servicio').value;
            const fecha = document.getElementById('fecha_cita').value;

            if (!empleado || !servicio || !fecha) return;

            // Mostrar loading
            document.getElementById('mensajeInicial').style.display = 'none';
            document.getElementById('horariosContainer').style.display = 'none';
            document.getElementById('noHorarios').style.display = 'none';
            document.getElementById('loadingHorarios').style.display = 'block';

            // Limpiar selección anterior
            document.getElementById('hora_inicio').value = '';

            fetch(`<?= base_url('cliente/citas/horarios-disponibles') ?>?empleado=${empleado}&servicio=${servicio}&fecha=${fecha}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                    document.getElementById('loadingHorarios').style.display = 'none';

                    if (!data.success) {
                        console.error('Error del servidor:', data.message);
                        if (data.debug) {
                            console.log('Debug info:', data.debug);
                        }
                        document.getElementById('noHorarios').style.display = 'block';
                        return;
                    }

                    if (data.horarios && data.horarios.length > 0) {
                        console.log(`Total de horarios: ${data.horarios.length}`);
                        const gridMañana = document.getElementById('gridMañana');
                        const gridTarde = document.getElementById('gridTarde');
                        gridMañana.innerHTML = '';
                        gridTarde.innerHTML = '';

                        let hayMañana = false;
                        let hayTarde = false;

                        // Organizar horarios por período del día
                        data.horarios.forEach(horario => {
                            const btn = document.createElement('div');
                            btn.className = 'horario-btn';
                            btn.textContent = horario.texto;
                            btn.dataset.hora = horario.hora_inicio;
                            btn.addEventListener('click', function() {
                                document.querySelectorAll('.horario-btn').forEach(b => b.classList.remove('active'));
                                this.classList.add('active');
                                document.getElementById('hora_inicio').value = this.dataset.hora;
                                datosSeleccionados.hora = this.textContent;
                            });

                            // Determinar si es mañana o tarde (antes o después de las 12:00)
                            const hora = horario.hora_inicio.split(':')[0];
                            if (parseInt(hora) < 12) {
                                gridMañana.appendChild(btn);
                                hayMañana = true;
                            } else {
                                gridTarde.appendChild(btn);
                                hayTarde = true;
                            }
                        });

                        // Mostrar/ocultar secciones según disponibilidad
                        document.getElementById('horariosMañana').style.display = hayMañana ? 'block' : 'none';
                        document.getElementById('horariosTarde').style.display = hayTarde ? 'block' : 'none';

                        document.getElementById('horariosContainer').style.display = 'block';
                    } else {
                        console.log('No hay horarios disponibles');
                        document.getElementById('noHorarios').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error en la petición:', error);
                    document.getElementById('loadingHorarios').style.display = 'none';
                    document.getElementById('noHorarios').style.display = 'block';
                });
        }

        function mostrarResumen() {
            document.getElementById('resumen_servicio').textContent = datosSeleccionados.servicio.nombre;
            document.getElementById('resumen_empleado').textContent = datosSeleccionados.empleado.nombre;
            document.getElementById('resumen_fecha').textContent = new Date(document.getElementById('fecha_cita').value + 'T00:00:00').toLocaleDateString('es-ES');
            document.getElementById('resumen_hora').textContent = datosSeleccionados.hora;
            document.getElementById('resumen_precio').textContent = '$' + new Intl.NumberFormat().format(datosSeleccionados.servicio.precio);
        }

        function confirmarCita() {
            const formData = new FormData(document.getElementById('formAgendar'));

            // Debug: mostrar datos que se envían
            console.log('Datos del formulario:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            fetch('<?= base_url('cliente/agendar/guardar') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.success) {
                    alert(data.message);
                    window.location.href = '<?= base_url('cliente/mis-citas') ?>';
                } else {
                    let errorMsg = data.message || 'Error desconocido';
                    if (data.errors) {
                        console.error('Errores de validación:', data.errors);
                        errorMsg += '\n\nDetalles:\n';
                        for (let field in data.errors) {
                            errorMsg += '- ' + data.errors[field] + '\n';
                        }
                    }
                    alert(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                alert('Error al agendar la cita. Por favor revisa la consola del navegador (F12) para más detalles.');
            });
        }
    </script>
</body>
</html>
