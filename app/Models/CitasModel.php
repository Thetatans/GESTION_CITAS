<?php

/**
 * MODELO DE CITAS
 *
 * Gestiona todas las operaciones de base de datos relacionadas con citas.
 *
 * Responsabilidades:
 * - CRUD completo de citas
 * - Validación de disponibilidad de horarios
 * - Generación de horarios disponibles
 * - Consultas con JOINs (clientes, empleados, servicios)
 * - Formateo de datos para calendario
 * - Gestión de estados de citas
 *
 * @author Sistema de Gestión de Citas - Barbería
 * @version 1.0
 */

namespace App\Models;

use CodeIgniter\Model;

class CitasModel extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'id_cita';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_cliente',
        'id_empleado',
        'id_servicio',
        'fecha_cita',
        'hora_inicio',
        'hora_fin',
        'estado',
        'notas'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'id_cliente' => 'required|integer',
        'id_empleado' => 'required|integer',
        'id_servicio' => 'required|integer',
        'fecha_cita' => 'required|valid_date',
        'hora_inicio' => 'required',
        'hora_fin' => 'required',
        'estado' => 'in_list[pendiente,confirmada,en_proceso,completada,cancelada]'
    ];

    protected $validationMessages = [
        'id_cliente' => [
            'required' => 'El cliente es obligatorio',
            'integer' => 'El ID del cliente debe ser un número'
        ],
        'id_empleado' => [
            'required' => 'El empleado es obligatorio',
            'integer' => 'El ID del empleado debe ser un número'
        ],
        'id_servicio' => [
            'required' => 'El servicio es obligatorio',
            'integer' => 'El ID del servicio debe ser un número'
        ],
        'fecha_cita' => [
            'required' => 'La fecha de la cita es obligatoria',
            'valid_date' => 'La fecha no es válida'
        ],
        'hora_inicio' => [
            'required' => 'La hora de inicio es obligatoria'
        ],
        'hora_fin' => [
            'required' => 'La hora de fin es obligatoria'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Obtener citas con información completa (cliente, empleado, servicio)
     */
    public function obtenerCitasCompletas($filtros = [])
    {
        $builder = $this->db->table('citas');
        $builder->select('
            citas.*,
            clientes.nombre as nombre_cliente,
            clientes.apellido as apellido_cliente,
            clientes.telefono as telefono_cliente,
            usuarios.email as email_cliente,
            empleados.nombre as nombre_empleado,
            empleados.apellido as apellido_empleado,
            empleados.especialidad,
            servicios.nombre as nombre_servicio,
            servicios.precio,
            servicios.duracion_minutos,
            servicios.duracion_minutos as duracion_servicio
        ');
        $builder->join('clientes', 'citas.id_cliente = clientes.id_cliente');
        $builder->join('usuarios', 'clientes.id_usuario = usuarios.id_usuario', 'left');
        $builder->join('empleados', 'citas.id_empleado = empleados.id_empleado');
        $builder->join('servicios', 'citas.id_servicio = servicios.id_servicio');

        // Aplicar filtros
        if (!empty($filtros['id_cliente'])) {
            $builder->where('citas.id_cliente', $filtros['id_cliente']);
        }
        if (!empty($filtros['id_empleado'])) {
            $builder->where('citas.id_empleado', $filtros['id_empleado']);
        }
        if (!empty($filtros['estado'])) {
            $builder->where('citas.estado', $filtros['estado']);
        }
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('citas.fecha_cita >=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('citas.fecha_cita <=', $filtros['fecha_hasta']);
        }

        $builder->orderBy('citas.fecha_cita', 'DESC');
        $builder->orderBy('citas.hora_inicio', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener una cita específica con información completa
     */
    public function obtenerCitaCompleta($idCita)
    {
        $builder = $this->db->table('citas');
        $builder->select('
            citas.*,
            clientes.nombre as nombre_cliente,
            clientes.apellido as apellido_cliente,
            clientes.telefono as telefono_cliente,
            clientes.id_usuario as id_usuario_cliente,
            usuarios.email as email_cliente,
            empleados.nombre as nombre_empleado,
            empleados.apellido as apellido_empleado,
            empleados.telefono as telefono_empleado,
            empleados.especialidad,
            servicios.nombre as nombre_servicio,
            servicios.descripcion as descripcion_servicio,
            servicios.precio,
            servicios.duracion_minutos,
            servicios.duracion_minutos as duracion_servicio
        ');
        $builder->join('clientes', 'citas.id_cliente = clientes.id_cliente');
        $builder->join('usuarios', 'clientes.id_usuario = usuarios.id_usuario', 'left');
        $builder->join('empleados', 'citas.id_empleado = empleados.id_empleado');
        $builder->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $builder->where('citas.id_cita', $idCita);

        return $builder->get()->getRowArray();
    }

    /**
     * Verificar disponibilidad de un empleado en una fecha y hora específica
     */
    public function verificarDisponibilidad($idEmpleado, $fecha, $horaInicio, $horaFin, $idCitaExcluir = null)
    {
        $builder = $this->db->table('citas');
        $builder->where('id_empleado', $idEmpleado);
        $builder->where('fecha_cita', $fecha);
        $builder->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso']);

        // Verificar solapamiento de horarios
        $builder->groupStart();
            $builder->groupStart()
                ->where('hora_inicio <', $horaFin)
                ->where('hora_fin >', $horaInicio)
            ->groupEnd();
        $builder->groupEnd();

        // Excluir una cita específica (útil para ediciones)
        if ($idCitaExcluir !== null) {
            $builder->where('id_cita !=', $idCitaExcluir);
        }

        $citasConflicto = $builder->get()->getResultArray();

        return count($citasConflicto) === 0;
    }

    /**
     * Obtener horarios disponibles para un empleado en una fecha
     */
    public function obtenerHorariosDisponibles($idEmpleado, $fecha, $duracionMinutos)
    {
        // Horario de trabajo: 9:00 AM a 7:00 PM
        $horaApertura = '09:00:00';
        $horaCierre = '19:00:00';
        $intervalo = 20; // minutos

        // Obtener citas del empleado para esa fecha
        $citasOcupadas = $this->where('id_empleado', $idEmpleado)
                              ->where('fecha_cita', $fecha)
                              ->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso'])
                              ->orderBy('hora_inicio', 'ASC')
                              ->findAll();

        $horariosDisponibles = [];
        $horaActual = strtotime($horaApertura);
        $horaFinTrabajo = strtotime($horaCierre);

        while ($horaActual < $horaFinTrabajo) {
            $horaInicioSlot = date('H:i:s', $horaActual);
            $horaFinSlot = date('H:i:s', strtotime("+{$duracionMinutos} minutes", $horaActual));

            // Verificar si este slot está disponible
            $disponible = true;
            foreach ($citasOcupadas as $cita) {
                if ($horaInicioSlot < $cita['hora_fin'] && $horaFinSlot > $cita['hora_inicio']) {
                    $disponible = false;
                    break;
                }
            }

            if ($disponible && strtotime($horaFinSlot) <= $horaFinTrabajo) {
                $horariosDisponibles[] = [
                    'hora_inicio' => $horaInicioSlot,
                    'hora_fin' => $horaFinSlot,
                    'texto' => date('g:i A', $horaActual)
                ];
            }

            $horaActual = strtotime("+{$intervalo} minutes", $horaActual);
        }

        return $horariosDisponibles;
    }

    /**
     * Obtener citas para el calendario (formato FullCalendar)
     */
    public function obtenerCitasParaCalendario($filtros = [])
    {
        $citas = $this->obtenerCitasCompletas($filtros);
        $eventos = [];

        foreach ($citas as $cita) {
            // Colores según estado
            $colores = [
                'pendiente' => '#ffc107',    // amarillo
                'confirmada' => '#0dcaf0',   // cyan
                'en_proceso' => '#0d6efd',   // azul
                'completada' => '#198754',   // verde
                'cancelada' => '#dc3545'     // rojo
            ];

            $nombreCompleto = $cita['nombre_cliente'] . ' ' . $cita['apellido_cliente'];
            $nombreEmpleado = $cita['nombre_empleado'] . ' ' . $cita['apellido_empleado'];

            $eventos[] = [
                'id' => $cita['id_cita'],
                'title' => $nombreCompleto . ' - ' . $cita['nombre_servicio'],
                'start' => $cita['fecha_cita'] . 'T' . $cita['hora_inicio'],
                'end' => $cita['fecha_cita'] . 'T' . $cita['hora_fin'],
                'backgroundColor' => $colores[$cita['estado']] ?? '#6c757d',
                'borderColor' => $colores[$cita['estado']] ?? '#6c757d',
                'extendedProps' => [
                    'cliente' => $nombreCompleto,
                    'empleado' => $nombreEmpleado,
                    'servicio' => $cita['nombre_servicio'],
                    'estado' => $cita['estado'],
                    'telefono' => $cita['telefono_cliente'],
                    'precio' => $cita['precio']
                ]
            ];
        }

        return $eventos;
    }

    /**
     * Cambiar estado de una cita
     */
    public function cambiarEstado($idCita, $nuevoEstado)
    {
        $estadosValidos = ['pendiente', 'confirmada', 'en_proceso', 'completada', 'cancelada'];

        if (!in_array($nuevoEstado, $estadosValidos)) {
            return false;
        }

        return $this->update($idCita, ['estado' => $nuevoEstado]);
    }

    /**
     * Obtener estadísticas de citas
     */
    public function obtenerEstadisticas($fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->db->table('citas');

        if ($fechaInicio) {
            $builder->where('fecha_cita >=', $fechaInicio);
        }
        if ($fechaFin) {
            $builder->where('fecha_cita <=', $fechaFin);
        }

        // Total de citas
        $total = $builder->countAllResults(false);

        // Por estado
        $builder->select('estado, COUNT(*) as total');
        $builder->groupBy('estado');
        $porEstado = $builder->get()->getResultArray();

        return [
            'total' => $total,
            'por_estado' => $porEstado
        ];
    }

    /**
     * Obtener próximas citas de un cliente
     */
    public function obtenerProximasCitasCliente($idCliente, $limite = 5)
    {
        $hoy = date('Y-m-d');
        return $this->obtenerCitasCompletas([
            'id_cliente' => $idCliente,
            'fecha_desde' => $hoy
        ]);
    }

    /**
     * Obtener citas del día para un empleado
     */
    public function obtenerCitasDelDia($idEmpleado, $fecha = null)
    {
        if ($fecha === null) {
            $fecha = date('Y-m-d');
        }

        return $this->obtenerCitasCompletas([
            'id_empleado' => $idEmpleado,
            'fecha_desde' => $fecha,
            'fecha_hasta' => $fecha
        ]);
    }

    /**
     * MÉTODOS PARA REPORTES AVANZADOS - SEMANA 8
     */

    /**
     * Obtener reporte completo por rango de fechas
     */
    public function obtenerReportePorFecha($fechaInicio, $fechaFin)
    {
        $builder = $this->db->table('citas');
        $builder->select('
            citas.*,
            clientes.nombre as nombre_cliente,
            clientes.apellido as apellido_cliente,
            clientes.telefono as telefono_cliente,
            empleados.nombre as nombre_empleado,
            empleados.apellido as apellido_empleado,
            servicios.nombre as nombre_servicio,
            servicios.precio,
            servicios.duracion_minutos
        ');
        $builder->join('clientes', 'citas.id_cliente = clientes.id_cliente');
        $builder->join('empleados', 'citas.id_empleado = empleados.id_empleado');
        $builder->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $builder->where('citas.fecha_cita >=', $fechaInicio);
        $builder->where('citas.fecha_cita <=', $fechaFin);
        $builder->orderBy('citas.fecha_cita', 'DESC');
        $builder->orderBy('citas.hora_inicio', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener reporte por empleado
     */
    public function obtenerReportePorEmpleado($fechaInicio, $fechaFin)
    {
        $builder = $this->db->table('citas');
        $builder->select('
            empleados.id_empleado,
            empleados.nombre,
            empleados.apellido,
            empleados.especialidad,
            COUNT(citas.id_cita) as total_citas,
            SUM(CASE WHEN citas.estado = "completada" THEN 1 ELSE 0 END) as citas_completadas,
            SUM(CASE WHEN citas.estado = "cancelada" THEN 1 ELSE 0 END) as citas_canceladas,
            SUM(CASE WHEN citas.estado = "pendiente" THEN 1 ELSE 0 END) as citas_pendientes,
            SUM(CASE WHEN citas.estado = "completada" THEN servicios.precio ELSE 0 END) as ingresos_generados,
            SUM(CASE WHEN citas.estado = "completada" THEN servicios.duracion_minutos ELSE 0 END) as minutos_trabajados
        ');
        $builder->join('empleados', 'citas.id_empleado = empleados.id_empleado');
        $builder->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $builder->where('citas.fecha_cita >=', $fechaInicio);
        $builder->where('citas.fecha_cita <=', $fechaFin);
        $builder->groupBy('empleados.id_empleado');
        $builder->orderBy('total_citas', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener reporte por servicio
     */
    public function obtenerReportePorServicio($fechaInicio, $fechaFin)
    {
        $builder = $this->db->table('citas');
        $builder->select('
            servicios.id_servicio,
            servicios.nombre,
            servicios.precio,
            servicios.duracion_minutos,
            COUNT(citas.id_cita) as total_citas,
            SUM(CASE WHEN citas.estado = "completada" THEN 1 ELSE 0 END) as citas_completadas,
            SUM(CASE WHEN citas.estado = "cancelada" THEN 1 ELSE 0 END) as citas_canceladas,
            SUM(CASE WHEN citas.estado = "completada" THEN servicios.precio ELSE 0 END) as ingresos_generados
        ');
        $builder->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $builder->where('citas.fecha_cita >=', $fechaInicio);
        $builder->where('citas.fecha_cita <=', $fechaFin);
        $builder->groupBy('servicios.id_servicio');
        $builder->orderBy('total_citas', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener ingresos totales (solo citas completadas)
     */
    public function obtenerIngresosReales($fechaInicio, $fechaFin)
    {
        $builder = $this->db->table('citas');
        $builder->select('SUM(servicios.precio) as total_ingresos');
        $builder->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $builder->where('citas.fecha_cita >=', $fechaInicio);
        $builder->where('citas.fecha_cita <=', $fechaFin);
        $builder->where('citas.estado', 'completada');

        $result = $builder->get()->getRowArray();
        return $result['total_ingresos'] ?? 0;
    }

    /**
     * Obtener citas realizadas (completadas)
     */
    public function obtenerCitasRealizadas($fechaInicio, $fechaFin)
    {
        return $this->obtenerCitasCompletas([
            'fecha_desde' => $fechaInicio,
            'fecha_hasta' => $fechaFin,
            'estado' => 'completada'
        ]);
    }

    /**
     * Obtener citas pendientes (pendientes + confirmadas)
     */
    public function obtenerCitasPendientes($fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->db->table('citas');
        $builder->select('
            citas.*,
            clientes.nombre as nombre_cliente,
            clientes.apellido as apellido_cliente,
            clientes.telefono as telefono_cliente,
            empleados.nombre as nombre_empleado,
            empleados.apellido as apellido_empleado,
            servicios.nombre as nombre_servicio,
            servicios.precio,
            servicios.duracion_minutos
        ');
        $builder->join('clientes', 'citas.id_cliente = clientes.id_cliente');
        $builder->join('empleados', 'citas.id_empleado = empleados.id_empleado');
        $builder->join('servicios', 'citas.id_servicio = servicios.id_servicio');
        $builder->whereIn('citas.estado', ['pendiente', 'confirmada']);

        if ($fechaInicio) {
            $builder->where('citas.fecha_cita >=', $fechaInicio);
        }
        if ($fechaFin) {
            $builder->where('citas.fecha_cita <=', $fechaFin);
        }

        $builder->orderBy('citas.fecha_cita', 'ASC');
        $builder->orderBy('citas.hora_inicio', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas avanzadas para dashboard
     */
    public function obtenerEstadisticasAvanzadas($fechaInicio, $fechaFin)
    {
        // Estadísticas básicas
        $estadisticasBasicas = $this->obtenerEstadisticas($fechaInicio, $fechaFin);

        // Ingresos totales
        $ingresos = $this->obtenerIngresosReales($fechaInicio, $fechaFin);

        // Tasa de cancelación
        $totalCitas = $estadisticasBasicas['total'];
        $citasCanceladas = 0;
        $citasCompletadas = 0;

        foreach ($estadisticasBasicas['por_estado'] as $estado) {
            if ($estado['estado'] == 'cancelada') {
                $citasCanceladas = $estado['total'];
            }
            if ($estado['estado'] == 'completada') {
                $citasCompletadas = $estado['total'];
            }
        }

        $tasaCancelacion = $totalCitas > 0 ? round(($citasCanceladas / $totalCitas) * 100, 2) : 0;
        $tasaCompletacion = $totalCitas > 0 ? round(($citasCompletadas / $totalCitas) * 100, 2) : 0;

        // Promedio de ingresos por cita completada
        $promedioIngreso = $citasCompletadas > 0 ? round($ingresos / $citasCompletadas, 2) : 0;

        return [
            'total_citas' => $totalCitas,
            'citas_completadas' => $citasCompletadas,
            'citas_canceladas' => $citasCanceladas,
            'ingresos_totales' => $ingresos,
            'tasa_cancelacion' => $tasaCancelacion,
            'tasa_completacion' => $tasaCompletacion,
            'promedio_ingreso' => $promedioIngreso,
            'por_estado' => $estadisticasBasicas['por_estado']
        ];
    }

    /**
     * Obtener datos para gráfica de citas por día
     */
    public function obtenerCitasPorDia($fechaInicio, $fechaFin)
    {
        $builder = $this->db->table('citas');
        $builder->select('
            DATE(fecha_cita) as fecha,
            COUNT(*) as total_citas,
            SUM(CASE WHEN estado = "completada" THEN 1 ELSE 0 END) as completadas,
            SUM(CASE WHEN estado = "cancelada" THEN 1 ELSE 0 END) as canceladas
        ');
        $builder->where('fecha_cita >=', $fechaInicio);
        $builder->where('fecha_cita <=', $fechaFin);
        $builder->groupBy('DATE(fecha_cita)');
        $builder->orderBy('fecha_cita', 'ASC');

        return $builder->get()->getResultArray();
    }
}
