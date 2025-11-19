<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('conexion', 'Conexion::index');
$routes->get('testdb', 'TestDB::index');
$routes->get('datos/json', 'Datos::json');
$routes->get('datos/tabla/(:segment)', 'Datos::tabla/$1');