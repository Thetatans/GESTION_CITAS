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
        
        // Verificar si el rol es 'admin'
        if ($rol !== 'admin') {
            // Si NO es admin, bloquear acceso
            // redirect()->back() vuelve a la página anterior
            return redirect()->back()
                           ->with('error', 'No tienes permisos para acceder a esta sección');
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