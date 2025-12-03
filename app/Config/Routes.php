<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================
// RUTAS PÚBLICAS
// ============================================

$routes->get('/', 'Home::index');

// ============================================
// RUTAS DE AUTENTICACIÓN (SIN PROTECCIÓN)
// ============================================

$routes->group('', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::intentarLogin');
    $routes->get('registro', 'Auth::registro');
    $routes->post('registro', 'Auth::intentarRegistro');
    $routes->get('logout', 'Auth::logout');
    $routes->get('recuperar-password', 'Auth::recuperarPassword');
});

// ============================================
// RUTAS PROTEGIDAS CON AUTENTICACIÓN
// ============================================

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('cambiar-password', 'Auth::cambiarPassword');
    $routes->post('actualizar-password', 'Auth::actualizarPassword');
});

// ============================================
// RUTAS DEL ADMINISTRADOR
// ============================================

$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Gestión de Clientes
    $routes->get('clientes', 'Admin\Clientes::index');
    $routes->get('clientes/crear', 'Admin\Clientes::crear');
    $routes->post('clientes/guardar', 'Admin\Clientes::guardar');
    $routes->get('clientes/editar/(:num)', 'Admin\Clientes::editar/$1');
    $routes->post('clientes/actualizar/(:num)', 'Admin\Clientes::actualizar/$1');
    $routes->get('clientes/eliminar/(:num)', 'Admin\Clientes::eliminar/$1');

    // Gestión de Empleados
    $routes->get('empleados', 'Admin\Empleados::index');
    $routes->get('empleados/crear', 'Admin\Empleados::crear');
    $routes->post('empleados/guardar', 'Admin\Empleados::guardar');
    $routes->get('empleados/editar/(:num)', 'Admin\Empleados::editar/$1');
    $routes->post('empleados/actualizar/(:num)', 'Admin\Empleados::actualizar/$1');
    $routes->get('empleados/eliminar/(:num)', 'Admin\Empleados::eliminar/$1');

    // Gestión de Usuarios (antiguo)
    $routes->get('usuarios', 'Admin\Usuarios::index');
    $routes->get('usuarios/crear', 'Admin\Usuarios::crear');
    $routes->post('usuarios/guardar', 'Admin\Usuarios::guardar');
    $routes->get('usuarios/editar/(:num)', 'Admin\Usuarios::editar/$1');
    $routes->post('usuarios/actualizar/(:num)', 'Admin\Usuarios::actualizar/$1');
    $routes->get('usuarios/eliminar/(:num)', 'Admin\Usuarios::eliminar/$1');

    // Gestión de Servicios
    $routes->get('servicios', 'Admin\Servicios::index');
    $routes->get('servicios/crear', 'Admin\Servicios::crear');
    $routes->post('servicios/guardar', 'Admin\Servicios::guardar');
    $routes->get('servicios/editar/(:num)', 'Admin\Servicios::editar/$1');
    $routes->post('servicios/actualizar/(:num)', 'Admin\Servicios::actualizar/$1');
    $routes->get('servicios/eliminar/(:num)', 'Admin\Servicios::eliminar/$1');

    // Gestión de Citas
    $routes->get('citas', 'Admin\Citas::index');
    $routes->get('citas/ver/(:num)', 'Admin\Citas::ver/$1');

    // Reportes
    $routes->get('reportes', 'Admin\Reportes::index');
});

// ============================================
// RUTAS DEL EMPLEADO
// ============================================

$routes->group('empleado', ['filter' => 'empleado'], function($routes) {
    $routes->get('dashboard', 'Empleado\Dashboard::index');
    $routes->get('agenda', 'Empleado\Agenda::index');
    $routes->get('citas', 'Empleado\Citas::index');
    $routes->get('citas/ver/(:num)', 'Empleado\Citas::ver/$1');
    $routes->post('citas/actualizar-estado/(:num)', 'Empleado\Citas::actualizarEstado/$1');
});

// ============================================
// RUTAS DEL CLIENTE
// ============================================

$routes->group('cliente', ['filter' => 'cliente'], function($routes) {
    $routes->get('dashboard', 'Cliente\Dashboard::index');
    $routes->get('agendar', 'Cliente\Citas::agendar');
    $routes->post('agendar/guardar', 'Cliente\Citas::guardarCita');
    $routes->get('mis-citas', 'Cliente\Citas::index');
    $routes->get('citas/ver/(:num)', 'Cliente\Citas::ver/$1');
    $routes->get('citas/cancelar/(:num)', 'Cliente\Citas::cancelar/$1');
    $routes->get('perfil', 'Cliente\Perfil::index');
    $routes->post('perfil/actualizar', 'Cliente\Perfil::actualizar');
});