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
// MANUAL DE USUARIO (ACCESO PÚBLICO)
// ============================================

$routes->get('manual', 'ManualController::descargar');

// ============================================
// RUTAS PROTEGIDAS CON AUTENTICACIÓN
// ============================================

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('cambiar-password', 'Auth::cambiarPassword');
    $routes->post('actualizar-password', 'Auth::actualizarPassword');
});

// ============================================
// RUTAS API (AJAX)
// ============================================

$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->get('clientes', 'Api\Clientes::listar');
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
    $routes->get('citas/listado', 'Admin\Citas::listado');
    $routes->get('citas/crear', 'Admin\Citas::crear');
    $routes->post('citas/guardar', 'Admin\Citas::guardar');
    $routes->get('citas/editar/(:num)', 'Admin\Citas::editar/$1');
    $routes->post('citas/actualizar/(:num)', 'Admin\Citas::actualizar/$1');
    $routes->get('citas/eliminar/(:num)', 'Admin\Citas::eliminar/$1');
    $routes->get('citas/ver/(:num)', 'Admin\Citas::ver/$1');
    $routes->get('citas/obtener', 'Admin\Citas::obtenerCitas');
    $routes->get('citas/horarios-disponibles', 'Admin\Citas::obtenerHorariosDisponibles');
    $routes->post('citas/cambiar-estado/(:num)', 'Admin\Citas::cambiarEstado/$1');
    $routes->get('citas/estadisticas', 'Admin\Citas::estadisticas');

    // Reportes
    $routes->get('reportes', 'Admin\Reportes::index');
    $routes->get('reportes/por-fecha', 'Admin\Reportes::porFecha');
    $routes->get('reportes/por-empleado', 'Admin\Reportes::porEmpleado');
    $routes->get('reportes/por-servicio', 'Admin\Reportes::porServicio');
    $routes->get('reportes/citas-realizadas', 'Admin\Reportes::citasRealizadas');
    $routes->get('reportes/citas-pendientes', 'Admin\Reportes::citasPendientes');
    $routes->get('reportes/exportarPDF', 'Admin\Reportes::exportarPDF');
    $routes->get('reportes/exportarExcel', 'Admin\Reportes::exportarExcel');
});

// ============================================
// RUTAS DEL EMPLEADO
// ============================================

$routes->group('empleado', ['filter' => 'empleado'], function($routes) {
    $routes->get('dashboard', 'Empleado\Dashboard::index');

    // Agenda
    $routes->get('agenda', 'Empleado\Agenda::index');
    $routes->get('agenda/obtener', 'Empleado\Agenda::obtenerCitas');
    $routes->get('agenda/semanal', 'Empleado\Agenda::semanal');

    // Citas
    $routes->get('citas', 'Empleado\Citas::index');
    $routes->get('citas/dia', 'Empleado\Citas::citasDelDia');
    $routes->get('citas/ver/(:num)', 'Empleado\Citas::ver/$1');
    $routes->post('citas/actualizar-estado/(:num)', 'Empleado\Citas::actualizarEstado/$1');
});

// ============================================
// RUTAS DEL CLIENTE
// ============================================

$routes->group('cliente', ['filter' => 'cliente'], function($routes) {
    $routes->get('dashboard', 'Cliente\Dashboard::index');

    // Agendar Citas
    $routes->get('agendar', 'Cliente\Citas::agendar');
    $routes->post('agendar/guardar', 'Cliente\Citas::guardarCita');

    // Mis Citas
    $routes->get('mis-citas', 'Cliente\Citas::index');
    $routes->get('citas/ver/(:num)', 'Cliente\Citas::ver/$1');
    $routes->get('citas/cancelar/(:num)', 'Cliente\Citas::cancelar/$1');
    $routes->get('citas/horarios-disponibles', 'Cliente\Citas::obtenerHorariosDisponibles');
    $routes->get('citas/proximas', 'Cliente\Citas::proximasCitas');

    // Perfil
    $routes->get('perfil', 'Cliente\Perfil::index');
    $routes->post('perfil/actualizar', 'Cliente\Perfil::actualizar');
});