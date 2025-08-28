<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

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

// Libros
$routes->get('/recurso', 'RecursoController::buscar');
