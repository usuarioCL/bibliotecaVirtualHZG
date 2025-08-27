<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Inicio de sesión
$routes->get('/login', 'LoginController::loginForm');
// Procesar inicio de sesión
$routes->post('/login', 'LoginController::login');

// Registro de Usuarios
// Ruta para el registro
$routes->get('/registro', 'RegistroController::RegistroForm');
// Procesar registro
$routes->post('/registro', 'RegistroController::Registro');

// Rutas por nivel de Usuario
// Rutas para el panel de administración
$routes->get('/admin', 'AdminController::dashboard');
// Rutas para el panel del docente
//$routes->get('/docente', 'DocenteController::dashboard');
//Libros
$routes->get('/recurso', 'RecursoController::buscar');


$routes->get('/login', 'LoginController::loginInterfaz');
$routes->post('/login', 'LoginController::login');
$routes->get('/registro', 'LoginController::Registro');
$routes->post('/registro', 'LoginController::guardarRegistro');