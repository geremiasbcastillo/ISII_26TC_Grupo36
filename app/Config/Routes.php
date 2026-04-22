<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rutas para personas no logueadas (invitados)
$routes->get('/', 'Home::index', ['filter' => 'guest']);

$routes->get('inicio', 'Home::index', ['filter' => 'guest']);

$routes->get('registro', 'Home::registro_usuario', ['filter' => 'guest']);


$routes->post('verificar_usuario', 'Usuarios_controller::buscar_usuario');

$routes->post('guardar_usuario', 'Usuarios_controller::guardar_usuario');

$routes->get('cerrar_sesion', 'Usuarios_controller::cerrar_sesion');

// Rutas para técnicos (solo accesibles para Técnicos)  
$routes->get('tecnico', 'Home::tecnico', ['filter' => 'tecnico']);


// Rutas para administración de equipos (solo accesibles para Admin)
$routes->get('principal', 'Home::principal', ['filter' => 'admin']);

$routes->get('agregar', 'Equipos_controller::formulario_registro', ['filter' => 'admin']);

$routes->get('listado', 'Equipos_controller::listado_equipos', ['filter' => 'admin']);

$routes->post('registrar_equipo', 'Equipos_controller::registrarEquipo', ['filter' => 'admin']);

$routes->post('actualizar/(:num)', 'Equipos_controller::editar_equipo/$1', ['filter' => 'admin']);

$routes->post('eliminar/(:num)', 'Equipos_controller::eliminarEquipo/$1', ['filter' => 'admin']);

