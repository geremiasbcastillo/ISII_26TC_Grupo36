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

$routes->get('agregar', 'Equipos_controller::formulario_registro');

$routes->get('listado', 'Equipos_controller::listado_equipos');

$routes->post('registrar_equipo', 'Equipos_controller::guardar');

$routes->post('actualizar/(:num)', 'Equipos_controller::editar_equipo/$1');

$routes->post('eliminar/(:num)', 'Equipos_controller::eliminar_equipo/$1');  
