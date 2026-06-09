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

$routes->get('diagnostico', 'Diagnosticos_controller::formularioDiagnostico', ['filter' => 'tecnico']);

$routes->post('guardar_diagnostico', 'Diagnosticos_controller::guardarDiagnostico', ['filter' => 'tecnico']);

$routes->get('reparacion', 'Reparaciones_Controller::index', ['filter' => 'tecnico']);

$routes->post('guardar_reparacion', 'Reparaciones_Controller::guardarReparacion', ['filter' => 'tecnico']);

// Rutas para administración de equipos (solo accesibles para Admin)
$routes->get('principal', 'Home::principal', ['filter' => 'admin']);

$routes->get('agregar', 'Equipos_controller::mostrarFormularioRegistro', ['filter' => 'admin']);

$routes->get('listado', 'Equipos_controller::listadoEquipos', ['filter' => 'admin']);

$routes->post('registrar_equipo', 'Equipos_controller::registrarEquipo', ['filter' => 'admin']);

$routes->post('actualizar/(:num)', 'Equipos_controller::editarEquipo/$1', ['filter' => 'admin']);

$routes->post('eliminar/(:num)', 'Equipos_controller::eliminarEquipo/$1', ['filter' => 'admin']);

$routes->post('guardar_repuesto', 'Repuestos_controller::guardarRepuesto', ['filter' => 'admin']); 

$routes->post('guardar_edicion', 'Repuestos_controller::guardar_edicion', ['filter' => 'admin']);

$routes->get('repuestos', 'Home::repuestos', ['filter' => 'admin']);

$routes->get('registrar_repuestos', 'Home::registrar_repuestos', ['filter' => 'admin']);

$routes->get('stock_repuestos', 'Home::stock_repuestos', ['filter' => 'admin']);

$routes->get('actualizar_repuestos', 'Home::actualizar_repuestos', ['filter' => 'admin']);
