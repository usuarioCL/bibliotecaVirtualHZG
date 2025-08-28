<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Libros
$routes->get('/recurso', 'RecursoController::buscar');

// Inicio de sesión
$routes->get('/login', 'LoginController::loginForm'); // Muestra el formulario de login
$routes->post('/login', 'LoginController::login');    // Procesa el login

// Registro de Usuarios
$routes->get('/registro', 'RegistroController::RegistroForm'); // Muestra el formulario de registro
$routes->post('/registro', 'RegistroController::Registro');    // Procesa el registro

// Panel de administración
$routes->get('/admin', 'AdminController::dashboard');
$routes->get('/admin/login', 'AdminController::login');
$routes->get('/admin/register', 'AdminController::register');

//Recursos
$routes->get('/recursos', 'RecursoController::index');
$routes->get('/recursos/crear', 'RecursoController::crear');
$routes->post('/recursos/guardar', 'RecursoController::guardar');
$routes->get('/recursos/editar/(:num)', 'RecursoController::editar/$1'); 
$routes->post('/recursos/actualizar/(:num)', 'RecursoController::actualizar/$1');
$routes->get('/recursos/eliminar/(:num)', 'RecursoController::eliminar/$1');

$routes->get('/recursos/buscarRecursos', 'RecursoController::buscarRecursos');
