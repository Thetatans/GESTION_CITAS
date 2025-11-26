<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * VerificarEstadoUsuario
 *
 * Filtro que verifica en CADA petición si el usuario sigue activo
 * Si un admin cambia el estado a inactivo mientras está navegando,
 * se cierra su sesión automáticamente
 *
 * Este filtro previene que usuarios inactivos/suspendidos/despedidos
 * continúen usando el sistema después de ser desactivados
 */
class VerificarEstadoUsuario implements FilterInterface
{
    /**
     * Verificar estado del usuario ANTES de cada petición
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cargar helper de cookies
        helper('cookie');

        // Solo verificar si el usuario está logueado
        if (!session()->has('usuario_id') || !session()->has('logueado')) {
            // Si no está logueado, no hacer nada
            return;
        }

        // Obtener el ID del usuario de la sesión
        $usuario_id = session()->get('usuario_id');

        // Consultar el estado actual del usuario en la base de datos
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');
        $usuario = $builder->select('id_usuario, email, estado, activo')
                          ->where('id_usuario', $usuario_id)
                          ->get()
                          ->getRowArray();

        // Si el usuario no existe en la base de datos
        if (!$usuario) {
            // Cerrar sesión y redirigir
            $this->cerrarSesion();
            return redirect()->to('/login')
                           ->with('error', 'Tu cuenta ya no existe en el sistema.')
                           ->with('error_estado', 'eliminado');
        }

        // Verificar el campo 'estado' (sistema nuevo)
        $estadoInvalido = false;
        $estadoActual = '';

        if (isset($usuario['estado']) && $usuario['estado'] !== 'activo') {
            $estadoInvalido = true;
            $estadoActual = $usuario['estado'];
        }

        // Verificar el campo 'activo' (sistema antiguo)
        if (isset($usuario['activo']) && $usuario['activo'] == 0) {
            $estadoInvalido = true;
            $estadoActual = $estadoActual ?: 'inactivo';
        }

        // Si el usuario NO está activo, cerrar su sesión
        if ($estadoInvalido) {
            // Log del evento
            log_message('info', "Usuario ID {$usuario_id} ({$usuario['email']}) fue desconectado automáticamente. Estado: {$estadoActual}");

            // Preparar mensaje según el estado
            $mensajes = [
                'inactivo' => 'Tu cuenta ha sido desactivada. Ya no puedes acceder al sistema.',
                'suspendido' => 'Tu cuenta ha sido suspendida. Por favor, contacta al administrador.',
                'despedido' => 'Tu cuenta ha sido desactivada permanentemente.',
            ];

            $mensaje = $mensajes[$estadoActual] ?? 'Tu cuenta ya no está activa. Contacta al administrador.';

            // Cerrar sesión completamente
            $this->cerrarSesion();

            // Redirigir al login con mensaje
            return redirect()->to('/login')
                           ->with('error', $mensaje)
                           ->with('error_estado', $estadoActual);
        }

        // Si está activo, permitir continuar
        return;
    }

    /**
     * Método after (no usado)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después
    }

    /**
     * Cerrar sesión completamente
     * Método auxiliar privado para limpiar toda la sesión
     */
    private function cerrarSesion()
    {
        // Eliminar cookie de "recordarme" ANTES de destruir la sesión
        delete_cookie('recordar_usuario');

        // Destruir variables de sesión una por una
        session()->remove('usuario_id');
        session()->remove('usuario_email');
        session()->remove('usuario_rol');
        session()->remove('logueado');

        // Destruir completamente la sesión
        // NOTA: Después de destroy(), no se puede hacer regenerate()
        session()->destroy();
    }
}
