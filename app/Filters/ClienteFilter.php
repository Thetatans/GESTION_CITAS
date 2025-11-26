<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * ClienteFilter
 * 
 * Filtro para áreas exclusivas de clientes
 * Solo usuarios con rol 'cliente' pueden acceder
 * 
 * Se aplica a:
 * - Agendar citas
 * - Ver historial de citas
 * - Perfil del cliente
 */
class ClienteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si está logueado
        if (!session()->has('logueado')) {
            return redirect()->to('/login')
                           ->with('error', 'Debes iniciar sesión');
        }

        // Verificar que sea cliente
        // En este caso, NO permitimos que admin acceda
        // porque las áreas de cliente son específicas para ellos
        $rol = session()->get('usuario_rol');

        if ($rol !== 'cliente') {
            // Si NO es cliente, CERRAR SESIÓN
            // Esto previene que un empleado o admin intente acceder a áreas de cliente

            // Log del intento de acceso no autorizado
            log_message('warning', "Intento de acceso no autorizado a área cliente por usuario con rol: {$rol}");

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

        // Si es cliente, permitir acceso
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después
    }
}