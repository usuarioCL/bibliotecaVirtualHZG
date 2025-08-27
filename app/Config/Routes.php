<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Libros
$routes->get('/recurso', 'RecursoController::buscar');


$routes->get('/login', 'LoginController::loginForm');
$routes->post('/login', 'LoginController::login');
$routes->get('/registro', 'LoginController::Registro');

$routes->post('/registro', 'LoginController::guardarRegistro');
$routes->post('/registro', 'LoginController::guardarRegistro');

//admin
$routes->get('/admin', 'AdminController::dashboard');