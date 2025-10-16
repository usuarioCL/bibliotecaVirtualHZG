<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Ruta para obtener sanciones activas vía AJAX
$routes->get('sanciones/activas-ajax', 'SancionAjaxController::activasAjax');
