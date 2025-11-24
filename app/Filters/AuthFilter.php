<?php

// Namespace de los filtros
namespace App\Filters;

// Importar interfaces necesarias
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter
 * 
 * Filtro que verifica si el usuario está autenticado
 * Se aplica a rutas que requieren login
 * 
 * Ejemplo de uso:
 * - Panel de administración
 * - Perfil del usuario
 * - Cualquier área privada
 */
class AuthFilter implements FilterInterface
{
    /**
     * Método que se ejecuta ANTES de llegar al controlador
     * 
     * Aquí verificamos si el usuario tiene permiso de acceder
     * 
     * @param RequestInterface $request Petición HTTP
     * @param array|null $arguments Argumentos adicionales (no usados aquí)
     * @return mixed Redirección si no está autenticado, void si sí
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si existe la sesión de usuario
        // session()->has() verifica si existe una variable en sesión
        // session()->get() obtiene el valor de la variable
        
        // Condición: No está logueado O la variable logueado es false
        if (!session()->has('logueado') || !session()->get('logueado')) {
            // Si no está autenticado:
            // 1. Redirigir al login
            // 2. Mostrar mensaje de error
            // 3. with() envía datos de una sola vez (flash data)
            return redirect()->to('/login')
                           ->with('error', 'Debes iniciar sesión para acceder');
        }
        
        // Si está autenticado, no hacer nada
        // El flujo continúa normalmente al controlador
    }

    /**
     * Método que se ejecuta DESPUÉS del controlador
     * 
     * No necesitamos hacer nada después en este filtro
     * 
     * @param RequestInterface $request Petición HTTP
     * @param ResponseInterface $response Respuesta HTTP
     * @param array|null $arguments Argumentos adicionales
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después
        // Este método es obligatorio por la interfaz
    }
}