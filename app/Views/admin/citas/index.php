<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> - Sistema de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css' rel='stylesheet' />
    <style>
        :root {
            --primary-dark: #1e3a5f;
            --secondary-brown: #6b4423;
            --accent-beige: #f5e6d3;
            --text-gray: #495057;
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
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .fc-event {
            cursor: pointer;
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
            <div class="text-white">
                <span class="me-3">Admin</span>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-calendar-check"></i> <?= $titulo ?></h2>
                    <div>
                        <a href="<?= base_url('admin/citas/listado') ?>" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-list-ul"></i> Ver Listado
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCita">
                            <i class="bi bi-plus-circle"></i> Nueva Cita
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Filtrar por Empleado</label>
                                <select id="filtroEmpleado" class="form-select">
                                    <option value="">Todos los empleados</option>
                                    <?php foreach ($empleados as $emp): ?>
                                        <option value="<?= $emp['id_empleado'] ?>">
                                            <?= $emp['nombre'] . ' ' . $emp['apellido'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Filtrar por Estado</label>
                                <select id="filtroEstado" class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="confirmada">Confirmada</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="completada">Completada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button id="btnAplicarFiltros" class="btn btn-secondary w-100">
                                    <i class="bi bi-funnel"></i> Aplicar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leyenda de colores -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-2">Leyenda:</h6>
                        <span class="badge bg-warning text-dark me-2">Pendiente</span>
                        <span class="badge bg-info me-2">Confirmada</span>
                        <span class="badge bg-primary me-2">En Proceso</span>
                        <span class="badge bg-success me-2">Completada</span>
                        <span class="badge bg-danger me-2">Cancelada</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario -->
        <div class="row">
            <div class="col-12">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Cita -->
    <div class="modal fade" id="modalNuevaCita" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevaCita">
                        <div class="mb-3">
                            <label class="form-label">Cliente *</label>
                            <select name="id_cliente" class="form-select" required>
                                <option value="">Seleccione un cliente</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Empleado *</label>
                            <select name="id_empleado" id="empleadoSelect" class="form-select" required>
                                <option value="">Seleccione un empleado</option>
                                <?php foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id_empleado'] ?>">
                                        <?= $emp['nombre'] . ' ' . $emp['apellido'] ?> - <?= $emp['especialidad'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Servicio *</label>
                            <select name="id_servicio" id="servicioSelect" class="form-select" required>
                                <option value="">Seleccione un servicio</option>
                                <?php foreach ($servicios as $serv): ?>
                                    <option value="<?= $serv['id_servicio'] ?>" data-duracion="<?= $serv['duracion_minutos'] ?>">
                                        <?= $serv['nombre'] ?> - $<?= number_format($serv['precio'], 0) ?> (<?= $serv['duracion_minutos'] ?> min)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="fecha_cita" id="fechaCita" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora *</label>
                            <select name="hora_inicio" id="horaSelect" class="form-select" required disabled>
                                <option value="">Seleccione empleado, servicio y fecha primero</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notas</label>
                            <textarea name="notas" class="form-control" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnGuardarCita" class="btn btn-primary">Guardar Cita</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle Cita -->
    <div class="modal fade" id="modalDetalleCita" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detalleCitaContent">
                    <!-- Se llenará dinámicamente -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/locales/es.global.min.js'></script>
    <script>
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                events: function(info, successCallback, failureCallback) {
                    const empleado = document.getElementById('filtroEmpleado').value;
                    const estado = document.getElementById('filtroEstado').value;

                    let url = '<?= base_url('admin/citas/obtener') ?>?start=' + info.startStr + '&end=' + info.endStr;
                    if (empleado) url += '&empleado=' + empleado;
                    if (estado) url += '&estado=' + estado;

                    console.log('Cargando citas desde:', url);

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Citas recibidas:', data);
                            successCallback(data);
                        })
                        .catch(error => {
                            console.error('Error al cargar citas:', error);
                            failureCallback(error);
                        });
                },
                eventClick: function(info) {
                    mostrarDetalleCita(info.event);
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }
            });

            calendar.render();

            // Aplicar filtros
            document.getElementById('btnAplicarFiltros').addEventListener('click', function() {
                calendar.refetchEvents();
            });

            // Cargar horarios disponibles
            ['empleadoSelect', 'servicioSelect', 'fechaCita'].forEach(id => {
                document.getElementById(id).addEventListener('change', cargarHorarios);
            });

            // Guardar cita
            document.getElementById('btnGuardarCita').addEventListener('click', guardarCita);

            // Cargar clientes
            cargarClientes();
        });

        function cargarClientes() {
            fetch('/api/clientes')
                .then(response => response.json())
                .then(data => {
                    const select = document.querySelector('select[name="id_cliente"]');
                    data.forEach(cliente => {
                        const option = document.createElement('option');
                        option.value = cliente.id_cliente;
                        option.textContent = cliente.nombre + ' ' + cliente.apellido;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function cargarHorarios() {
            const empleado = document.getElementById('empleadoSelect').value;
            const servicio = document.getElementById('servicioSelect').value;
            const fecha = document.getElementById('fechaCita').value;
            const horaSelect = document.getElementById('horaSelect');

            if (!empleado || !servicio || !fecha) {
                horaSelect.disabled = true;
                return;
            }

            fetch(`/admin/citas/horarios-disponibles?empleado=${empleado}&servicio=${servicio}&fecha=${fecha}`)
                .then(response => response.json())
                .then(data => {
                    horaSelect.innerHTML = '';
                    horaSelect.disabled = false;

                    if (data.success && data.horarios.length > 0) {
                        data.horarios.forEach(horario => {
                            const option = document.createElement('option');
                            option.value = horario.hora_inicio;
                            option.textContent = horario.texto;
                            horaSelect.appendChild(option);
                        });
                    } else {
                        horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                        horaSelect.disabled = true;
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function guardarCita() {
            const form = document.getElementById('formNuevaCita');
            const formData = new FormData(form);

            fetch('/admin/citas/guardar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('modalNuevaCita')).hide();
                    form.reset();
                    calendar.refetchEvents();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function mostrarDetalleCita(event) {
            const props = event.extendedProps;
            const html = `
                <p><strong>Cliente:</strong> ${props.cliente}</p>
                <p><strong>Empleado:</strong> ${props.empleado}</p>
                <p><strong>Servicio:</strong> ${props.servicio}</p>
                <p><strong>Estado:</strong> <span class="badge bg-${getEstadoColor(props.estado)}">${props.estado}</span></p>
                <p><strong>Fecha:</strong> ${event.start.toLocaleDateString()}</p>
                <p><strong>Hora:</strong> ${event.start.toLocaleTimeString()} - ${event.end.toLocaleTimeString()}</p>
                <p><strong>Precio:</strong> $${new Intl.NumberFormat().format(props.precio)}</p>
                <p><strong>Teléfono:</strong> ${props.telefono}</p>
                <div class="mt-3">
                    <a href="<?= base_url('admin/citas/ver/') ?>${event.id}" class="btn btn-sm btn-primary">Ver Detalle</a>
                    <a href="<?= base_url('admin/citas/editar/') ?>${event.id}" class="btn btn-sm btn-warning">Editar</a>
                </div>
            `;
            document.getElementById('detalleCitaContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalDetalleCita')).show();
        }

        function getEstadoColor(estado) {
            const colores = {
                'pendiente': 'warning',
                'confirmada': 'info',
                'en_proceso': 'primary',
                'completada': 'success',
                'cancelada': 'danger'
            };
            return colores[estado] || 'secondary';
        }
    </script>
</body>
</html>
