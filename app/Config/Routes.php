<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rutas para personas no logueadas (invitados)
$routes->get('/', 'Home::index', ['filter' => 'guest']);

$routes->get('inicio', 'Home::index', ['filter' => 'guest']);

$routes->get('registro', 'Home::registroUsuario', ['filter' => 'guest']);


$routes->post('verificar_usuario', 'Usuarios_controller::buscarUsuario');

$routes->post('guardar_usuario', 'Usuarios_controller::guardarUsuario');

$routes->get('cerrar_sesion', 'Usuarios_controller::cerrarSesion');

// Rutas para técnicos (solo accesibles para Técnicos)  
$routes->get('tecnico', 'Home::tecnico', ['filter' => 'tecnico']);


// Rutas para administración de equipos (solo accesibles para Admin)
$routes->get('principal', 'Home::principal', ['filter' => 'admin']);

$routes->get('agregar', 'Equipos_controller::mostrarFormularioRegistro', ['filter' => 'admin']);

$routes->get('listado', 'Equipos_controller::listadoEquipos', ['filter' => 'admin']);

$routes->post('registrar_equipo', 'Equipos_controller::registrarEquipo', ['filter' => 'admin']);

$routes->post('actualizar/(:num)', 'Equipos_controller::editarEquipo/$1', ['filter' => 'admin']);

$routes->post('eliminar/(:num)', 'Equipos_controller::eliminarEquipo/$1', ['filter' => 'admin']);

$routes->get('diagnostico', 'Diagnosticos_controller::index', ['filter' => 'tecnico']);
$routes->post('guardar_diagnostico', 'Diagnosticos_controller::guardarDiagnostico', ['filter' => 'tecnico']);