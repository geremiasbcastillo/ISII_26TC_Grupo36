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

<<<<<<< HEAD
$routes->get('registro', 'Home::registro_usuario');
=======

$routes->get('agregar', 'Equipos_controller::formulario_registro');

$routes->post('registrar_equipo', 'Equipos_controller::guardar');
>>>>>>> 11a3ab7fec398da8330b57a4cf6c11f6321deedc
