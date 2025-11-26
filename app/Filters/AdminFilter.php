<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminFilter
 * 
 * Filtro que verifica si el usuario es administrador
 * Solo usuarios con rol 'admin' pueden pasar
 * 
 * Se aplica a:
 * - Panel de administración
 * - Gestión de usuarios
 * - Configuración del sistema
 * - Reportes administrativos
 */
class AdminFilter implements FilterInterface
{
    /**
     * Verificar antes de llegar al controlador
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // ========================================
        // PASO 1: VERIFICAR QUE ESTÉ LOGUEADO
        // ========================================
        
        // Primero verificar si hay sesión activa
        if (!session()->has('logueado')) {
            // Si no está logueado, enviar al login
            return redirect()->to('/login')
                           ->with('error', 'Debes iniciar sesión');
        }

        // ========================================
        // PASO 2: VERIFICAR QUE SEA ADMINISTRADOR
        // ========================================

        // Obtener el rol del usuario de la sesión
        $rol = session()->get('usuario_rol');

        // Verificar si el rol es 'admin' o 'administrador'
        // Soporta tanto el nombre antiguo como el nuevo
        if ($rol !== 'admin' && $rol !== 'administrador') {
            // Si NO es admin, CERRAR SESIÓN y redirigir al login
            // Esto previene intentos de acceso no autorizado manipulando URLs

            // Log del intento de acceso no autorizado
            log_message('warning', "Intento de acceso no autorizado a área admin por usuario con rol: {$rol}");

            // Cargar helper de cookies
            helper('cookie');

            // Eliminar cookie de "recordarme" ANTES de destruir sesión
            delete_cookie('recordar_usuario');

            // Destruir TODOS los datos de sesión
            session()->remove('usuario_id');
            session()->remove('usuario_email');
            session()->remove('usuario_rol');
            session()->remove('logueado');

            // Destruir completamente la sesión
            session()->destroy();

            // Redirigir al login con mensaje de error
            return redirect()->to('/login')
                           ->with('error', 'No estás autorizado para acceder a esa sección. Tu sesión ha sido cerrada por seguridad.')
                           ->with('tipo_error', 'acceso_no_autorizado');
        }

        // Si es admin, permitir acceso
        // El flujo continúa al controlador
    }

    /**
     * Método after (no usado)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después
    }
}