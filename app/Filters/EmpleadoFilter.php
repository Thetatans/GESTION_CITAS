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
        
        // Permitir si es empleado O admin
        // Los admins pueden acceder a áreas de empleados para supervisar
        if ($rol !== 'empleado' && $rol !== 'admin') {
            // Si no es ni empleado ni admin, bloquear
            return redirect()->back()
                           ->with('error', 'No tienes permisos para acceder a esta sección');
        }
        
        // Si es empleado o admin, permitir acceso
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después
    }
}