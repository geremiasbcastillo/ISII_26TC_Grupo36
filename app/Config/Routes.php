<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('inicio', 'Home::index');

$routes->post('verificar_usuario', 'Usuarios_controller::buscar_usuario');

$routes->get('principal', 'Home::principal');


$routes->get('agregar', 'Equipos_controller::formulario_registro');

$routes->post('registrar_equipo', 'Equipos_controller::guardar');