<?php

/**
 * ============================================
 * CONTROLADOR DE AGENDA - MÓDULO EMPLEADO
 * ============================================
 *
 * Propósito:
 * Este controlador gestiona la agenda personal del empleado/barbero.
 * Permite visualizar sus citas asignadas en formato de calendario.
 *
 * Funcionalidades principales:
 * - Calendario personal con FullCalendar
 * - Solo muestra citas asignadas al empleado
 * - Vista de mes, semana y día
 * - Carga dinámica de eventos vía AJAX
 *
 * Seguridad:
 * - Solo accesible para usuarios con rol 'empleado'
 * - Cada empleado solo ve sus propias citas
 *
 * @author Sistema de Gestión de Citas - Barbería
 * @version 1.0
 */

namespace App\Controllers\Empleado;

use App\Controllers\BaseController;
use App\Models\CitasModel;
use App\Models\EmpleadoModel;

class Agenda extends BaseController
{
    // ============================================
    // PROPIEDADES DE LA CLASE
    // ============================================

    /**
     * @var CitasModel Modelo para gestionar citas
     */
    protected $citasModel;

    /**
     * @var EmpleadoModel Modelo para gestionar información del empleado
     */
    protected $empleadoModel;

    /**
     * @var Session Servicio de sesión de CodeIgniter
     */
    protected $session;

    // ============================================
    // CONSTRUCTOR
    // ============================================

    /**
     * Constructor del controlador
     *
     * Inicializa los modelos necesarios y el servicio de sesión.
     */
    public function __construct()
    {
        $this->citasModel = new CitasModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->session = \Config\Services::session();
    }

    // ============================================
    // MÉTODOS PÚBLICOS
    // ============================================

    /**
     * Vista principal del calendario/agenda del empleado
     *
     * Muestra el calendario interactivo con FullCalendar.
     * Solo incluye las citas asignadas a este empleado.
     *
     * Flujo:
     * 1. Obtiene el empleado del usuario en sesión
     * 2. Valida que exista
     * 3. Carga la vista del calendario
     * 4. El calendario hace peticiones AJAX para obtener eventos
     *
     * @return mixed Vista del calendario o redirección con error
     */
    public function index()
    {
        // Obtener ID del usuario desde la sesión
        $idUsuario = $this->session->get('usuario_id');

        // Buscar el empleado asociado a este usuario
        $empleado = $this->empleadoModel->where('id_usuario', $idUsuario)->first();

        // Si no existe el empleado, redirigir con error
        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'Empleado no encontrado');
        }

        // Preparar datos para la vista
        $data = [
            'titulo' => 'Mi Agenda',
            'empleado' => $empleado
        ];

        // Cargar vista del calendario
        return view('empleado/agenda/index', $data);
    }

    /**
     * Obtener citas en formato JSON para el calendario (AJAX)
     *
     * Este método es llamado por FullCalendar vía AJAX para obtener
     * los eventos (citas) que se mostrarán en el calendario.
     *
     * Proceso:
     * 1. Obtiene el empleado del usuario en sesión
     * 2. Aplica filtros de fecha si los hay (start, end)
     * 3. Consulta solo las citas asignadas a este empleado
     * 4. Retorna eventos en formato JSON para FullCalendar
     *
     * Parámetros GET aceptados:
     * - start: Fecha de inicio del rango
     * - end: Fecha de fin del rango
     *
     * @return JSON Array de eventos en formato FullCalendar
     */
    public function obtenerCitas()
    {
        // Obtener ID del usuario desde la sesión
        $idUsuario = $this->session->get('usuario_id');

        // Buscar el empleado asociado a este usuario
        $empleado = $this->empleadoModel->where('id_usuario', $idUsuario)->first();

        // Si no existe el empleado, retornar array vacío
        if (!$empleado) {
            return $this->response->setJSON([]);
        }

        // Crear filtro base: solo citas de este empleado
        $filtros = ['id_empleado' => $empleado['id_empleado']];

        // Aplicar filtros de rango de fechas desde FullCalendar
        if ($this->request->getGet('start')) {
            $filtros['fecha_desde'] = date('Y-m-d', strtotime($this->request->getGet('start')));
        }
        if ($this->request->getGet('end')) {
            $filtros['fecha_hasta'] = date('Y-m-d', strtotime($this->request->getGet('end')));
        }

        // Obtener eventos en formato FullCalendar
        $eventos = $this->citasModel->obtenerCitasParaCalendario($filtros);

        // Retornar JSON
        return $this->response->setJSON($eventos);
    }

    /**
     * Vista semanal de la agenda (alternativa)
     *
     * Muestra las citas de la semana en formato de lista/tabla.
     * Útil para una vista más detallada o para imprimir.
     *
     * Proceso:
     * 1. Obtiene el empleado del usuario en sesión
     * 2. Calcula inicio y fin de la semana actual (o especificada)
     * 3. Obtiene todas las citas de esa semana
     * 4. Carga la vista semanal
     *
     * Parámetros GET opcionales:
     * - fecha: Para especificar una semana diferente
     *
     * @return mixed Vista semanal o redirección con error
     */
    public function semanal()
    {
        // Obtener ID del usuario desde la sesión
        $idUsuario = $this->session->get('usuario_id');

        // Buscar el empleado asociado a este usuario
        $empleado = $this->empleadoModel->where('id_usuario', $idUsuario)->first();

        // Si no existe el empleado, redirigir con error
        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'Empleado no encontrado');
        }

        // Obtener semana actual o la especificada desde parámetro GET
        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d');

        // Calcular inicio (lunes) y fin (domingo) de la semana
        $inicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fecha)));
        $finSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fecha)));

        // Obtener todas las citas de este empleado en esa semana
        $citas = $this->citasModel->obtenerCitasCompletas([
            'id_empleado' => $empleado['id_empleado'],
            'fecha_desde' => $inicioSemana,
            'fecha_hasta' => $finSemana
        ]);

        // Preparar datos para la vista
        $data = [
            'titulo' => 'Agenda Semanal',
            'citas' => $citas,
            'inicio_semana' => $inicioSemana,
            'fin_semana' => $finSemana,
            'empleado' => $empleado
        ];

        // Cargar vista semanal
        return view('empleado/agenda/semanal', $data);
    }
}

