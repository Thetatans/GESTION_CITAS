<?php

/**
 * CONTROLADOR DE CITAS - MÓDULO ADMINISTRADOR
 *
 * Propósito:
 * Gestión completa de citas desde el panel administrativo.
 * Incluye calendario interactivo, filtros, reportes y estadísticas.
 *
 * Funcionalidades:
 * - Vista de calendario con FullCalendar
 * - Creación, edición y eliminación de citas
 * - Gestión de estados (pendiente, confirmada, en proceso, completada, cancelada)
 * - Validación de disponibilidad de empleados
 * - Generación de estadísticas y reportes
 * - Filtros por empleado, estado y fecha
 *
 * @author Sistema de Gestión de Citas - Barbería
 * @version 1.0
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CitasModel;
use App\Models\ClienteModel;
use App\Models\EmpleadoModel;
use App\Models\ServicioModel;

class Citas extends BaseController
{
    protected $citasModel;
    protected $clienteModel;
    protected $empleadoModel;
    protected $servicioModel;
    protected $session;

    /**
     * Constructor - Inicializa modelos y servicios necesarios
     */
    public function __construct()
    {
        $this->citasModel = new CitasModel();
        $this->clienteModel = new ClienteModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->servicioModel = new ServicioModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Vista principal - Calendario de citas
     */
    public function index()
    {
        $data = [
            'titulo' => 'Gestión de Citas',
            'empleados' => $this->empleadoModel->findAll(),
            'servicios' => $this->servicioModel->where('activo', 1)->findAll()
        ];

        return view('admin/citas/index', $data);
    }

    /**
     * Obtener citas en formato JSON para el calendario
     */
    public function obtenerCitas()
    {
        $filtros = [];

        // Filtros opcionales desde la petición
        if ($this->request->getGet('empleado')) {
            $filtros['id_empleado'] = $this->request->getGet('empleado');
        }
        if ($this->request->getGet('estado')) {
            $filtros['estado'] = $this->request->getGet('estado');
        }
        if ($this->request->getGet('start')) {
            $filtros['fecha_desde'] = date('Y-m-d', strtotime($this->request->getGet('start')));
        }
        if ($this->request->getGet('end')) {
            $filtros['fecha_hasta'] = date('Y-m-d', strtotime($this->request->getGet('end')));
        }

        $eventos = $this->citasModel->obtenerCitasParaCalendario($filtros);

        return $this->response->setJSON($eventos);
    }

    /**
     * Ver listado de citas en formato tabla
     */
    public function listado()
    {
        $filtros = [];

        // Filtros desde el formulario
        if ($this->request->getGet('empleado')) {
            $filtros['id_empleado'] = $this->request->getGet('empleado');
        }
        if ($this->request->getGet('estado')) {
            $filtros['estado'] = $this->request->getGet('estado');
        }
        if ($this->request->getGet('fecha_desde')) {
            $filtros['fecha_desde'] = $this->request->getGet('fecha_desde');
        }
        if ($this->request->getGet('fecha_hasta')) {
            $filtros['fecha_hasta'] = $this->request->getGet('fecha_hasta');
        }

        $citas = $this->citasModel->obtenerCitasCompletas($filtros);

        $data = [
            'titulo' => 'Listado de Citas',
            'citas' => $citas,
            'empleados' => $this->empleadoModel->findAll(),
            'filtros' => $filtros
        ];

        return view('admin/citas/listado', $data);
    }

    /**
     * Ver detalle de una cita
     */
    public function ver($idCita)
    {
        $cita = $this->citasModel->obtenerCitaCompleta($idCita);

        if (!$cita) {
            return redirect()->to('/admin/citas')->with('error', 'Cita no encontrada');
        }

        $data = [
            'titulo' => 'Detalle de Cita',
            'cita' => $cita
        ];

        return view('admin/citas/ver', $data);
    }

    /**
     * Formulario para crear nueva cita
     */
    public function crear()
    {
        $data = [
            'titulo' => 'Crear Nueva Cita',
            'clientes' => $this->clienteModel->findAll(),
            'empleados' => $this->empleadoModel->findAll(),
            'servicios' => $this->servicioModel->where('activo', 1)->findAll()
        ];

        return view('admin/citas/crear', $data);
    }

    /**
     * Guardar nueva cita
     */
    public function guardar()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $datos = [
            'id_cliente' => $this->request->getPost('id_cliente'),
            'id_empleado' => $this->request->getPost('id_empleado'),
            'id_servicio' => $this->request->getPost('id_servicio'),
            'fecha_cita' => $this->request->getPost('fecha_cita'),
            'hora_inicio' => $this->request->getPost('hora_inicio'),
            'estado' => 'pendiente',
            'notas' => $this->request->getPost('notas')
        ];

        // Calcular hora de fin según duración del servicio
        $servicio = $this->servicioModel->find($datos['id_servicio']);
        if ($servicio) {
            $horaInicio = strtotime($datos['hora_inicio']);
            $horaFin = strtotime("+{$servicio['duracion_minutos']} minutes", $horaInicio);
            $datos['hora_fin'] = date('H:i:s', $horaFin);
        }

        // Verificar disponibilidad
        $disponible = $this->citasModel->verificarDisponibilidad(
            $datos['id_empleado'],
            $datos['fecha_cita'],
            $datos['hora_inicio'],
            $datos['hora_fin']
        );

        if (!$disponible) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El empleado no está disponible en ese horario'
            ]);
        }

        // Guardar cita
        if ($this->citasModel->insert($datos)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cita creada exitosamente'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al crear la cita',
            'errors' => $this->citasModel->errors()
        ]);
    }

    /**
     * Formulario para editar cita
     */
    public function editar($idCita)
    {
        $cita = $this->citasModel->find($idCita);

        if (!$cita) {
            return redirect()->to('/admin/citas')->with('error', 'Cita no encontrada');
        }

        $data = [
            'titulo' => 'Editar Cita',
            'cita' => $cita,
            'clientes' => $this->clienteModel->findAll(),
            'empleados' => $this->empleadoModel->findAll(),
            'servicios' => $this->servicioModel->where('activo', 1)->findAll()
        ];

        return view('admin/citas/editar', $data);
    }

    /**
     * Actualizar cita existente
     */
    public function actualizar($idCita)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $datos = [
            'id_cliente' => $this->request->getPost('id_cliente'),
            'id_empleado' => $this->request->getPost('id_empleado'),
            'id_servicio' => $this->request->getPost('id_servicio'),
            'fecha_cita' => $this->request->getPost('fecha_cita'),
            'hora_inicio' => $this->request->getPost('hora_inicio'),
            'notas' => $this->request->getPost('notas')
        ];

        // Calcular hora de fin
        $servicio = $this->servicioModel->find($datos['id_servicio']);
        if ($servicio) {
            $horaInicio = strtotime($datos['hora_inicio']);
            $horaFin = strtotime("+{$servicio['duracion_minutos']} minutes", $horaInicio);
            $datos['hora_fin'] = date('H:i:s', $horaFin);
        }

        // Verificar disponibilidad (excluyendo la cita actual)
        $disponible = $this->citasModel->verificarDisponibilidad(
            $datos['id_empleado'],
            $datos['fecha_cita'],
            $datos['hora_inicio'],
            $datos['hora_fin'],
            $idCita
        );

        if (!$disponible) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El empleado no está disponible en ese horario'
            ]);
        }

        // Actualizar cita
        if ($this->citasModel->update($idCita, $datos)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cita actualizada exitosamente'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al actualizar la cita',
            'errors' => $this->citasModel->errors()
        ]);
    }

    /**
     * Cambiar estado de una cita
     */
    public function cambiarEstado($idCita)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $nuevoEstado = $this->request->getPost('estado');

        if ($this->citasModel->cambiarEstado($idCita, $nuevoEstado)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado actualizado exitosamente'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al actualizar el estado'
        ]);
    }

    /**
     * Eliminar/Cancelar cita
     */
    public function eliminar($idCita)
    {
        // En lugar de eliminar, cancelamos la cita
        if ($this->citasModel->cambiarEstado($idCita, 'cancelada')) {
            return redirect()->to('/admin/citas/listado')->with('success', 'Cita cancelada exitosamente');
        }

        return redirect()->to('/admin/citas/listado')->with('error', 'Error al cancelar la cita');
    }

    /**
     * Obtener horarios disponibles (AJAX)
     */
    public function obtenerHorariosDisponibles()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $idEmpleado = $this->request->getGet('empleado');
        $fecha = $this->request->getGet('fecha');
        $idServicio = $this->request->getGet('servicio');

        $servicio = $this->servicioModel->find($idServicio);
        if (!$servicio) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ]);
        }

        $horarios = $this->citasModel->obtenerHorariosDisponibles(
            $idEmpleado,
            $fecha,
            $servicio['duracion_minutos']
        );

        return $this->response->setJSON([
            'success' => true,
            'horarios' => $horarios
        ]);
    }

    /**
     * Ver estadísticas de citas
     */
    public function estadisticas()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        $estadisticas = $this->citasModel->obtenerEstadisticas($fechaInicio, $fechaFin);

        $data = [
            'titulo' => 'Estadísticas de Citas',
            'estadisticas' => $estadisticas,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        return view('admin/citas/estadisticas', $data);
    }
}
