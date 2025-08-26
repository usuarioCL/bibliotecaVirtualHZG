<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
//Buscar
$routes->get('/buscar', 'BuscarController::index');
$routes->get('/login', 'LoginController::loginInterfaz');
$routes->post('/login', 'LoginController::login');
$routes->get('/registro', 'LoginController::Registro');
$routes->post('/registro', 'LoginController::guardarRegistro');
