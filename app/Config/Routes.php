<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('inicio', 'Home::index');

$routes->post('verificar_usuario', 'Usuarios_controller::buscar_usuario');
$routes->post('guardar_usuario', 'Usuarios_controller::guardar_usuario');

$routes->get('principal', 'Home::principal');

$routes->get('registro', 'Home::registro_usuario');
