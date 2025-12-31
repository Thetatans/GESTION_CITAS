<?php

namespace App\Controllers\Empleado;

use App\Controllers\BaseController;
use App\Models\CitasModel;
use App\Models\EmpleadoModel;

class Citas extends BaseController
{
    protected $citasModel;
    protected $empleadoModel;
    protected $session;

    public function __construct()
    {
        $this->citasModel = new CitasModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Ver todas mis citas asignadas
     */
    public function index()
    {
        // Obtener el id_empleado del usuario actual
        $idUsuario = $this->session->get('usuario_id');
        $empleado = $this->empleadoModel->where('id_usuario', $idUsuario)->first();

        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'Empleado no encontrado');
        }

        $filtros = ['id_empleado' => $empleado['id_empleado']];

        // Filtros adicionales
        if ($this->request->getGet('estado')) {
            $filtros['estado'] = $this->request->getGet('estado');
        }
        if ($this->request->getGet('fecha')) {
            $filtros['fecha_desde'] = $this->request->getGet('fecha');
            $filtros['fecha_hasta'] = $this->request->getGet('fecha');
        }

        $citas = $this->citasModel->obtenerCitasCompletas($filtros);

        $data = [
            'titulo' => 'Mis Citas',
            'citas' => $citas,
            'filtros' => $filtros
        ];

        return view('empleado/citas/index', $data);
    }

    /**
     * Ver detalle de una cita
     */
    public function ver($idCita)
    {
        // Verificar que la cita pertenece al empleado
        $idUsuario = $this->session->get('usuario_id');
        $empleado = $this->empleadoModel->where('id_usuario', $idUsuario)->first();

        $cita = $this->citasModel->obtenerCitaCompleta($idCita);

        if (!$cita || $cita['id_empleado'] != $empleado['id_empleado']) {
            return redirect()->to('/empleado/citas')->with('error', 'Cita no encontrada');
        }

        $data = [
            'titulo' => 'Detalle de Cita',
            'cita' => $cita
        ];

        return view('empleado/citas/ver', $data);
    }

    /**
     * Actualizar estado de una cita
     */
    public function actualizarEstado($idCita)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Verificar que la cita pertenece al empleado
        $idUsuario = $this->session->get('usuario_id');
        $empleado = $this->empleadoModel->where('id_usuario', $idUsuario)->first();

        $cita = $this->citasModel->find($idCita);

        if (!$cita || $cita['id_empleado'] != $empleado['id_empleado']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cita no encontrada'
            ]);
        }

        $nuevoEstado = $this->request->getPost('estado');

        // Validar transiciones de estado permitidas para empleados
        $transicionesPermitidas = [
            'pendiente' => ['confirmada', 'cancelada'],
            'confirmada' => ['en_proceso', 'cancelada'],
            'en_proceso' => ['completada'],
        ];

        $estadoActual = $cita['estado'];
        if (!isset($transicionesPermitidas[$estadoActual]) ||
            !in_array($nuevoEstado, $transicionesPermitidas[$estadoActual])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Transición de estado no permitida'
            ]);
        }

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
     * Obtener citas del día actual
     */
    public function citasDelDia()
    {
        $idUsuario = $this->session->get('usuario_id');
        $empleado = $this->empleadoModel->where('id_usuario', $idUsuario)->first();

        if (!$empleado) {
            return redirect()->to('/empleado/dashboard')->with('error', 'Empleado no encontrado');
        }

        $citas = $this->citasModel->obtenerCitasDelDia($empleado['id_empleado']);

        $data = [
            'titulo' => 'Citas de Hoy',
            'citas' => $citas,
            'fecha' => date('Y-m-d')
        ];

        return view('empleado/citas/dia', $data);
    }
}
