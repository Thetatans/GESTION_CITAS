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
        #calendar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
                <span class="me-3"><?= $empleado['nombre'] . ' ' . $empleado['apellido'] ?></span>
                <a href="<?= base_url('empleado/citas') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-list-ul"></i> Mis Citas
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2><i class="bi bi-calendar-week"></i> <?= $titulo ?></h2>
            </div>
        </div>

        <!-- Leyenda -->
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
                <div class="modal-footer" id="modalFooter">
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
        let modalDetalle;

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'timeGridWeek',
                slotMinTime: '09:00:00',
                slotMaxTime: '19:00:00',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Hoy',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },
                events: '<?= base_url('empleado/agenda/obtener') ?>',
                eventClick: function(info) {
                    mostrarDetalleCita(info.event);
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                },
                allDaySlot: false
            });

            calendar.render();

            modalDetalle = new bootstrap.Modal(document.getElementById('modalDetalleCita'));
        });

        function mostrarDetalleCita(event) {
            const props = event.extendedProps;
            const html = `
                <div class="mb-3">
                    <h6><i class="bi bi-person"></i> Cliente</h6>
                    <p class="mb-1"><strong>${props.cliente}</strong></p>
                    <p class="mb-0"><small class="text-muted">Tel: ${props.telefono}</small></p>
                </div>
                <div class="mb-3">
                    <h6><i class="bi bi-scissors"></i> Servicio</h6>
                    <p class="mb-0">${props.servicio}</p>
                </div>
                <div class="mb-3">
                    <h6><i class="bi bi-calendar3"></i> Fecha y Hora</h6>
                    <p class="mb-0">${event.start.toLocaleDateString()} - ${event.start.toLocaleTimeString()} a ${event.end.toLocaleTimeString()}</p>
                </div>
                <div class="mb-3">
                    <h6><i class="bi bi-tag"></i> Estado</h6>
                    <span class="badge bg-${getEstadoColor(props.estado)}">${props.estado}</span>
                </div>
                <div class="mb-3">
                    <h6><i class="bi bi-cash"></i> Precio</h6>
                    <p class="mb-0">$${new Intl.NumberFormat().format(props.precio)}</p>
                </div>
            `;

            document.getElementById('detalleCitaContent').innerHTML = html;

            // Botones de acción según el estado
            const footer = document.getElementById('modalFooter');
            footer.innerHTML = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';

            if (props.estado === 'pendiente') {
                footer.innerHTML += `
                    <button type="button" class="btn btn-success" onclick="cambiarEstado(${event.id}, 'confirmada')">
                        <i class="bi bi-check-circle"></i> Confirmar
                    </button>
                `;
            } else if (props.estado === 'confirmada') {
                footer.innerHTML += `
                    <button type="button" class="btn btn-primary" onclick="cambiarEstado(${event.id}, 'en_proceso')">
                        <i class="bi bi-play-circle"></i> Iniciar
                    </button>
                `;
            } else if (props.estado === 'en_proceso') {
                footer.innerHTML += `
                    <button type="button" class="btn btn-success" onclick="cambiarEstado(${event.id}, 'completada')">
                        <i class="bi bi-check-circle-fill"></i> Completar
                    </button>
                `;
            }

            footer.innerHTML += `
                <a href="<?= base_url('empleado/citas/ver/') ?>${event.id}" class="btn btn-info">
                    <i class="bi bi-eye"></i> Ver Detalle
                </a>
            `;

            modalDetalle.show();
        }

        function cambiarEstado(idCita, nuevoEstado) {
            if (confirm('¿Estás seguro de cambiar el estado de esta cita?')) {
                fetch(`<?= base_url('empleado/citas/actualizar-estado/') ?>${idCita}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `estado=${nuevoEstado}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        modalDetalle.hide();
                        calendar.refetchEvents();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar el estado');
                });
            }
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
