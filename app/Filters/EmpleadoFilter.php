<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * EmpleadoFilter
 * 
 * Filtro para áreas de empleados
 * Permite acceso a empleados Y administradores
 * (Los admins pueden ver todo)
 * 
 * Se aplica a:
 * - Agenda del empleado
 * - Gestión de citas asignadas
 * - Horarios de trabajo
 */
class EmpleadoFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si está logueado
        if (!session()->has('logueado')) {
            return redirect()->to('/login')
                           ->with('error', 'Debes iniciar sesión');
        }

        // ========================================
        // PERMITIR ACCESO A EMPLEADO Y ADMIN
        // ========================================

        // Obtener el rol del usuario
        $rol = session()->get('usuario_rol');

        // Permitir si es empleado O admin/administrador
        // Los admins pueden acceder a áreas de empleados para supervisar
        // Soporta tanto 'admin' como 'administrador'
        if ($rol !== 'empleado' && $rol !== 'admin' && $rol !== 'administrador') {
            // Si no es ni empleado ni admin, CERRAR SESIÓN
            // Esto previene intentos de acceso no autorizado

            // Log del intento de acceso no autorizado
            log_message('warning', "Intento de acceso no autorizado a área empleado por usuario con rol: {$rol}");

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

        // Si es empleado o admin, permitir acceso
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después
    }
}