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
        if (session()->get('usuario_rol') !== 'cliente') {
            return redirect()->back()
                           ->with('error', 'No tienes permisos para acceder a esta sección');
        }
        
        // Si es cliente, permitir acceso
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después
    }
}