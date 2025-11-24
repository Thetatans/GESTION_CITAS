<?php

// Declarar el namespace (espacio de nombres) del controlador
// Esto organiza el código en la carpeta Admin
namespace App\Controllers\Admin;

// Importar las clases necesarias
use App\Controllers\BaseController;
use App\Models\UsuarioModel;

/**
 * Controlador Dashboard Admin
 *
 * Gestiona el panel principal del administrador
 *
 * Funcionalidades:
 * - Mostrar dashboard con estadísticas
 * - Accesos rápidos a secciones principales
 * - Información del administrador logueado
 *
 * Protección:
 * - Requiere autenticación (filter: admin)
 * - Solo accesible por usuarios con rol 'admin'
 */
class Dashboard extends BaseController
{
    /**
     * Método index()
     *
     * Muestra la página principal del panel de administración
     *
     * Proceso:
     * 1. Obtiene el ID del usuario desde la sesión
     * 2. Busca los datos del usuario en la base de datos
     * 3. Prepara los datos para la vista
     * 4. Carga la vista del dashboard
     *
     * Datos enviados a la vista:
     * - titulo: Título de la página
     * - usuario_nombre: Email del administrador
     *
     * @return string Vista renderizada (admin/dashboard)
     */
    public function index()
    {
        // PASO 1: OBTENER DATOS DEL USUARIO
        // Crear instancia del modelo de usuario
        $usuarioModel = new UsuarioModel();

        // Buscar el usuario por su ID almacenado en sesión
        // session()->get('usuario_id') obtiene el ID guardado al hacer login
        $usuario = $usuarioModel->find(session()->get('usuario_id'));

        // PASO 2: PREPARAR DATOS PARA LA VISTA
        $data = [
            // Título que aparecerá en la página
            'titulo' => 'Panel de Administración',

            // Nombre del usuario para mostrar en el navbar
            // Si existe el usuario, usa su email, sino usa 'Administrador' por defecto
            'usuario_nombre' => $usuario ? $usuario['email'] : 'Administrador'
        ];

        // PASO 3: CARGAR Y RETORNAR LA VISTA
        // view() es una función helper de CodeIgniter
        // Carga la vista ubicada en app/Views/admin/dashboard.php
        // Le pasa el array $data con la información necesaria
        return view('admin/dashboard', $data);
    }
}
