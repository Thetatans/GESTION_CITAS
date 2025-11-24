<?php

// Declarar el namespace (espacio de nombres) del controlador
// Esto organiza el código en la carpeta Cliente
namespace App\Controllers\Cliente;

// Importar las clases necesarias
use App\Controllers\BaseController;
use App\Models\ClienteModel;

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
        // Crear instancia del modelo de cliente
        $clienteModel = new ClienteModel();

        // Buscar el cliente por su id_usuario almacenado en sesión
        // Usamos where() porque buscamos por id_usuario, no por id_cliente
        // first() retorna solo el primer resultado (o null si no existe)
        $cliente = $clienteModel->where('id_usuario', session()->get('usuario_id'))->first();

        // PASO 2: PREPARAR DATOS PARA LA VISTA
        $data = [
            // Título que aparecerá en la página
            'titulo' => 'Mi Dashboard',

            // Nombre del usuario para mostrar en el navbar
            // Si existe el cliente, muestra su nombre completo (nombre + apellido)
            // Si no existe, usa el email de la sesión como respaldo
            // Esto puede pasar si el usuario existe pero no tiene perfil de cliente creado
            'usuario_nombre' => $cliente ? $cliente['nombre'] . ' ' . $cliente['apellido'] : session()->get('usuario_email')
        ];

        // PASO 3: CARGAR Y RETORNAR LA VISTA
        // view() es una función helper de CodeIgniter
        // Carga la vista ubicada en app/Views/cliente/dashboard.php
        // Le pasa el array $data con la información necesaria
        return view('cliente/dashboard', $data);
    }
}
