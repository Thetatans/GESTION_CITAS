<?php

// Declarar el namespace (espacio de nombres) del controlador
// Esto organiza el código en la carpeta Cliente
namespace App\Controllers\Cliente;

// Importar las clases necesarias
use App\Controllers\BaseController;
use App\Models\ClienteModel;
use App\Models\CitasModel;

/**
 * Controlador Dashboard Cliente
 *
 * Gestiona el panel principal del cliente
 *
 * Funcionalidades:
 * - Mostrar dashboard con próximas citas
 * - Acceso rápido a agendar cita
 * - Ver historial de citas
 * - Información del cliente logueado
 *
 * Protección:
 * - Requiere autenticación (filter: cliente)
 * - Solo accesible por usuarios con rol 'cliente'
 */
class Dashboard extends BaseController
{
    /**
     * Método index()
     *
     * Muestra la página principal del panel de cliente
     *
     * Proceso:
     * 1. Obtiene el ID del usuario desde la sesión
     * 2. Busca los datos del cliente en la base de datos
     * 3. Prepara los datos para la vista
     * 4. Carga la vista del dashboard
     *
     * Datos enviados a la vista:
     * - titulo: Título de la página
     * - usuario_nombre: Nombre completo del cliente o su email
     *
     * @return string Vista renderizada (cliente/dashboard)
     */
    public function index()
    {
        // PASO 1: OBTENER DATOS DEL CLIENTE
        $clienteModel = new ClienteModel();
        $citasModel = new CitasModel();

        // Buscar el cliente por su id_usuario almacenado en sesión
        $cliente = $clienteModel->where('id_usuario', session()->get('usuario_id'))->first();

        // Si no existe cliente, crear uno automáticamente
        if (!$cliente) {
            $usuarioId = session()->get('usuario_id');
            $usuarioEmail = session()->get('usuario_email');

            $datosCliente = [
                'id_usuario' => $usuarioId,
                'nombre' => $usuarioEmail,
                'apellido' => '',
                'telefono' => '',
                'fecha_nacimiento' => null,
                'genero' => null,
                'direccion' => ''
            ];

            $idClienteNuevo = $clienteModel->insert($datosCliente);
            $cliente = $clienteModel->find($idClienteNuevo);
        }

        // PASO 2: OBTENER PRÓXIMAS CITAS
        $proximasCitas = [];
        if ($cliente) {
            $proximasCitas = $citasModel->obtenerProximasCitasCliente($cliente['id_cliente']);
        }

        // PASO 3: PREPARAR DATOS PARA LA VISTA
        $data = [
            'titulo' => 'Mi Dashboard',
            'usuario_nombre' => $cliente ? $cliente['nombre'] . ' ' . $cliente['apellido'] : session()->get('usuario_email'),
            'proximasCitas' => $proximasCitas
        ];

        // PASO 4: CARGAR Y RETORNAR LA VISTA
        return view('cliente/dashboard', $data);
    }
}
