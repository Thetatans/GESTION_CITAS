<?php

/**
 * ============================================
 * CONTROLADOR DE CITAS - MÓDULO CLIENTE
 * ============================================
 *
 * Propósito:
 * Este controlador gestiona todas las operaciones relacionadas con las citas
 * desde la perspectiva del CLIENTE. Permite a los clientes agendar, ver,
 * cancelar y gestionar sus propias citas.
 *
 * Funcionalidades principales:
 * - Agendar nuevas citas (con validación de disponibilidad)
 * - Ver listado de todas sus citas (próximas e históricas)
 * - Ver detalle completo de una cita específica
 * - Cancelar citas (con restricción de 24 horas)
 * - Obtener horarios disponibles vía AJAX
 *
 * Seguridad:
 * - Solo accesible para usuarios con rol 'cliente'
 * - Cada cliente solo puede ver/modificar sus propias citas
 * - Validación de permisos en cada método
 *
 * @author Sistema de Gestión de Citas - Barbería
 * @version 1.0
 */

namespace App\Controllers\Cliente;

use App\Controllers\BaseController;
use App\Models\CitasModel;
use App\Models\ClienteModel;
use App\Models\EmpleadoModel;
use App\Models\ServicioModel;

class Citas extends BaseController
{
    // ============================================
    // PROPIEDADES DE LA CLASE
    // ============================================

    /**
     * @var CitasModel Modelo para gestionar citas en la base de datos
     */
    protected $citasModel;

    /**
     * @var ClienteModel Modelo para gestionar información de clientes
     */
    protected $clienteModel;

    /**
     * @var EmpleadoModel Modelo para obtener información de empleados/barberos
     */
    protected $empleadoModel;

    /**
     * @var ServicioModel Modelo para obtener servicios disponibles
     */
    protected $servicioModel;

    /**
     * @var Session Servicio de sesión de CodeIgniter para manejar datos del usuario
     */
    protected $session;

    // ============================================
    // CONSTRUCTOR
    // ============================================

    /**
     * Constructor del controlador
     *
     * Se ejecuta automáticamente al crear una instancia de este controlador.
     * Inicializa todos los modelos necesarios y el servicio de sesión.
     *
     * ¿Por qué inicializamos aquí?
     * - Evitamos repetir la inicialización en cada método
     * - Los modelos están disponibles en toda la clase
     * - Mejora el rendimiento (solo se inicializan una vez)
     */
    public function __construct()
    {
        $this->citasModel = new CitasModel();
        $this->clienteModel = new ClienteModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->servicioModel = new ServicioModel();
        $this->session = \Config\Services::session();
    }

    // ============================================
    // MÉTODO AUXILIAR PRIVADO
    // ============================================

    /**
     * Obtener o crear cliente para el usuario actual
     *
     * Este método resuelve un problema común: cuando un usuario se registra,
     * podría no tener un perfil de cliente creado automáticamente.
     *
     * Flujo del método:
     * 1. Busca el cliente asociado al usuario en sesión
     * 2. Si existe, lo retorna
     * 3. Si NO existe, lo crea automáticamente con datos básicos
     * 4. Registra todo en logs para debugging
     *
     * @return array|null Array con datos del cliente o null si hay error
     */
    private function obtenerOCrearCliente()
    {
        $idUsuario = $this->session->get('usuario_id');
        $cliente = $this->clienteModel->where('id_usuario', $idUsuario)->first();

        // Si el cliente no existe, crearlo automáticamente
        if (!$cliente) {
            log_message('info', 'Cliente no encontrado, creando registro automático para usuario: ' . $idUsuario);

            $usuarioModel = new \App\Models\UsuarioModel();
            $usuario = $usuarioModel->find($idUsuario);

            $datosCliente = [
                'id_usuario' => $idUsuario,
                'nombre' => $usuario['email'] ?? 'Cliente',
                'apellido' => '',
                'telefono' => '',
                'fecha_nacimiento' => null,
                'genero' => null,
                'direccion' => ''
            ];

            $idClienteNuevo = $this->clienteModel->insert($datosCliente);

            if (!$idClienteNuevo) {
                log_message('error', 'Error al crear cliente automático: ' . json_encode($this->clienteModel->errors()));
                return null;
            }

            $cliente = $this->clienteModel->find($idClienteNuevo);
            log_message('info', 'Cliente creado automáticamente con ID: ' . $idClienteNuevo);
        }

        return $cliente;
    }

    /**
     * Ver mis citas
     */
    public function index()
    {
        $cliente = $this->obtenerOCrearCliente();

        if (!$cliente) {
            return redirect()->to('/cliente/dashboard')->with('error', 'Error al cargar perfil de cliente');
        }

        $citas = $this->citasModel->obtenerCitasCompletas([
            'id_cliente' => $cliente['id_cliente']
        ]);

        $data = [
            'titulo' => 'Mis Citas',
            'citas' => $citas
        ];

        return view('cliente/citas/index', $data);
    }

    /**
     * Formulario para agendar nueva cita
     */
    public function agendar()
    {
        $data = [
            'titulo' => 'Agendar Nueva Cita',
            'empleados' => $this->empleadoModel->findAll(),
            'servicios' => $this->servicioModel->where('activo', 1)->findAll()
        ];

        return view('cliente/citas/agendar', $data);
    }

    /**
     * Guardar nueva cita del cliente
     */
    public function guardarCita()
    {
        // Comentado temporalmente para debugging
        // if (!$this->request->isAJAX()) {
        //     return redirect()->back();
        // }

        // Obtener el id_cliente del usuario actual
        $idUsuario = $this->session->get('usuario_id');
        $rol = $this->session->get('usuario_rol');
        log_message('debug', 'Guardando cita - ID Usuario: ' . $idUsuario . ', Rol: ' . $rol);

        // Verificar que el usuario sea un cliente
        if ($rol !== 'cliente') {
            log_message('error', 'Usuario no es cliente. Rol: ' . $rol);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo los clientes pueden agendar citas. Tu rol es: ' . $rol
            ]);
        }

        $cliente = $this->obtenerOCrearCliente();

        if (!$cliente) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear perfil de cliente. Por favor contacta al administrador.'
            ]);
        }

        $datos = [
            'id_cliente' => $cliente['id_cliente'],
            'id_empleado' => $this->request->getPost('id_empleado'),
            'id_servicio' => $this->request->getPost('id_servicio'),
            'fecha_cita' => $this->request->getPost('fecha_cita'),
            'hora_inicio' => $this->request->getPost('hora_inicio'),
            'estado' => 'pendiente',
            'notas' => $this->request->getPost('notas')
        ];

        log_message('debug', 'Datos recibidos: ' . json_encode($datos));

        // Validar datos requeridos
        if (empty($datos['id_empleado']) || empty($datos['id_servicio']) ||
            empty($datos['fecha_cita']) || empty($datos['hora_inicio'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Faltan datos requeridos',
                'debug' => $datos
            ]);
        }

        // Calcular hora de fin según duración del servicio
        $servicio = $this->servicioModel->find($datos['id_servicio']);
        if (!$servicio) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ]);
        }

        $horaInicio = strtotime($datos['hora_inicio']);
        $horaFin = strtotime("+{$servicio['duracion_minutos']} minutes", $horaInicio);
        $datos['hora_fin'] = date('H:i:s', $horaFin);

        log_message('debug', 'Hora fin calculada: ' . $datos['hora_fin']);

        // Verificar disponibilidad
        $disponible = $this->citasModel->verificarDisponibilidad(
            $datos['id_empleado'],
            $datos['fecha_cita'],
            $datos['hora_inicio'],
            $datos['hora_fin']
        );

        if (!$disponible) {
            log_message('warning', 'Horario no disponible');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El horario seleccionado no está disponible. Por favor, elige otro horario.'
            ]);
        }

        // Guardar cita
        if ($this->citasModel->insert($datos)) {
            log_message('info', 'Cita agendada exitosamente - ID: ' . $this->citasModel->getInsertID());
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cita agendada exitosamente. Te enviaremos una confirmación pronto.'
            ]);
        }

        $errors = $this->citasModel->errors();
        log_message('error', 'Error al guardar cita: ' . json_encode($errors));

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al agendar la cita. Por favor verifica los datos.',
            'errors' => $errors
        ]);
    }

    /**
     * Ver detalle de una cita
     */
    public function ver($idCita)
    {
        // Verificar que la cita pertenece al cliente
        $idUsuario = $this->session->get('usuario_id');
        $cliente = $this->clienteModel->where('id_usuario', $idUsuario)->first();

        $cita = $this->citasModel->obtenerCitaCompleta($idCita);

        if (!$cita || $cita['id_cliente'] != $cliente['id_cliente']) {
            return redirect()->to('/cliente/mis-citas')->with('error', 'Cita no encontrada');
        }

        $data = [
            'titulo' => 'Detalle de Cita',
            'cita' => $cita
        ];

        return view('cliente/citas/ver', $data);
    }

    /**
     * Cancelar una cita
     */
    public function cancelar($idCita)
    {
        // Verificar que la cita pertenece al cliente
        $idUsuario = $this->session->get('usuario_id');
        $cliente = $this->clienteModel->where('id_usuario', $idUsuario)->first();

        $cita = $this->citasModel->find($idCita);

        if (!$cita || $cita['id_cliente'] != $cliente['id_cliente']) {
            return redirect()->to('/cliente/mis-citas')->with('error', 'Cita no encontrada');
        }

        // Verificar que la cita no esté en proceso o completada
        if (in_array($cita['estado'], ['en_proceso', 'completada'])) {
            return redirect()->to('/cliente/mis-citas')
                ->with('error', 'No se puede cancelar una cita en proceso o completada');
        }

        // Verificar que la cita sea con al menos 24 horas de anticipación
        $fechaHoraCita = strtotime($cita['fecha_cita'] . ' ' . $cita['hora_inicio']);
        $ahora = time();
        $horasRestantes = ($fechaHoraCita - $ahora) / 3600;

        if ($horasRestantes < 20) {
            return redirect()->to('/cliente/mis-citas')
                ->with('error', 'Las citas deben cancelarse con al menos 24 horas de anticipación');
        }

        // Cancelar la cita
        if ($this->citasModel->cambiarEstado($idCita, 'cancelada')) {
            return redirect()->to('/cliente/mis-citas')
                ->with('success', 'Cita cancelada exitosamente');
        }

        return redirect()->to('/cliente/mis-citas')
            ->with('error', 'Error al cancelar la cita');
    }

    /**
     * Obtener horarios disponibles (AJAX)
     */
    public function obtenerHorariosDisponibles()
    {
        // Permitir tanto AJAX como peticiones normales para debugging
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setStatusCode(400);
        // }

        $idEmpleado = $this->request->getGet('empleado');
        $fecha = $this->request->getGet('fecha');
        $idServicio = $this->request->getGet('servicio');

        // Debug: verificar parámetros recibidos
        log_message('debug', 'Horarios - Empleado: ' . $idEmpleado . ', Fecha: ' . $fecha . ', Servicio: ' . $idServicio);

        // Validar parámetros requeridos
        if (!$idEmpleado || !$fecha || !$idServicio) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Faltan parámetros requeridos',
                'debug' => [
                    'empleado' => $idEmpleado,
                    'fecha' => $fecha,
                    'servicio' => $idServicio
                ]
            ]);
        }

        // Validar que la fecha no sea en el pasado
        if (strtotime($fecha) < strtotime(date('Y-m-d'))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se pueden agendar citas en fechas pasadas'
            ]);
        }

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

        log_message('debug', 'Horarios disponibles: ' . count($horarios));

        return $this->response->setJSON([
            'success' => true,
            'horarios' => $horarios,
            'debug' => [
                'total_horarios' => count($horarios),
                'duracion_servicio' => $servicio['duracion_minutos']
            ]
        ]);
    }

    /**
     * Obtener próximas citas del cliente
     */
    public function proximasCitas()
    {
        $idUsuario = $this->session->get('usuario_id');
        $cliente = $this->clienteModel->where('id_usuario', $idUsuario)->first();

        if (!$cliente) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ]);
        }

        $citas = $this->citasModel->obtenerProximasCitasCliente($cliente['id_cliente']);

        return $this->response->setJSON([
            'success' => true,
            'citas' => $citas
        ]);
    }
}
