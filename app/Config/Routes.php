<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/sobre-plataforma', 'Home::sobrePlataforma');

//Libros
$routes->get('/recurso', 'RecursoController::buscar');

// Inicio de sesión
$routes->get('/login', 'LoginController::loginForm'); // Muestra el formulario de login
$routes->post('/login', 'LoginController::login');    // Procesa el login
$routes->get('/logout', 'LoginController::logout');   // Cierra la sesión

// Registro de Usuarios
$routes->get('/registro', 'RegistroController::RegistroForm'); // Muestra el formulario de registro
$routes->post('/registro', 'RegistroController::procesarRegistro');    // Procesa el registro
$routes->post('/registro/buscar-persona', 'RegistroController::buscarPersona'); // Busca persona por documento
$routes->post('/registro/validar-usuario', 'RegistroController::validarNombreUsuario'); // Valida disponibilidad de usuario

// Gestión de Usuarios con Validación de Matrícula
$routes->get('usuarios', 'UsuarioController::index');                     // Página principal de usuarios
$routes->group('usuarios', function($routes) {
    $routes->get('crear', 'UsuarioController::crear');                    // Formulario de creación
    $routes->post('crear', 'UsuarioController::crear');                   // Procesar creación
    $routes->post('crear-completo', 'UsuarioController::crearCompleto');   // Crear persona y usuario completo
    $routes->get('listar', 'UsuarioController::listar');                  // Listar usuarios (JSON)
    $routes->get('obtener/(:num)', 'UsuarioController::obtener/$1');      // Obtener usuario por ID
    $routes->get('verificar-elegibilidad', 'UsuarioController::verificarElegibilidad'); // Verificar si puede crear usuario
    $routes->get('info-matricula/(:num)', 'UsuarioController::infoMatricula/$1'); // Info matrícula de persona
});

// API para validaciones AJAX
$routes->group('api/usuarios', function($routes) {
    $routes->get('elegibilidad', 'UsuarioController::verificarElegibilidad');
    $routes->get('matricula/(:num)', 'UsuarioController::infoMatricula/$1');
});

// Panel de administración
$routes->get('/admin', 'AdminController::dashboard');
$routes->get('/admin/dashboard-default', 'AdminController::dashboardDefault');
$routes->get('/admin/login', 'AdminController::login');
$routes->get('/admin/register', 'AdminController::register');

//Vistas de administrador
$routes->get('Administrador/vistas/UsuariosRoles', 'AdminController::VistaUsuariosRoles');
$routes->get('Administrador/vistas/PrestamosAlumnos', 'AdminController::VistaPrestamosAlumnos');
$routes->get('Administrador/vistas/ReaccionesUsuarios', 'AdminController::VistaReaccionesUsuarios');
$routes->get('Administrador/vistas/AlumnosSancionados', 'AdminController::VistaAlumnosSancionados');

// Importación de datos
$routes->get('admin/importar-datos', 'AdminController::importarDatos');
$routes->get('admin/descargar-plantilla/(:segment)', 'AdminController::descargarPlantilla/$1');
$routes->post('admin/preview-csv', 'AdminController::previewCsv');
$routes->post('admin/procesar-importacion', 'AdminController::procesarImportacion');

//Recursos
$routes->get('/recursos', 'RecursoController::index');
$routes->get('/recursos/crear', 'RecursoController::crear');
$routes->get('/recursos/crear-modal', 'RecursoController::crearModal');
$routes->post('/recursos/guardar', 'RecursoController::guardar');
$routes->get('/recursos/editar/(:num)', 'RecursoController::editar/$1'); 
$routes->post('/recursos/actualizar/(:num)', 'RecursoController::actualizar/$1');
$routes->get('/recursos/eliminar/(:num)', 'RecursoController::eliminar/$1');
$routes->get('/recursos/detalles/(:num)', 'RecursoController::detalles/$1');

// Buscar recursos
$routes->get('/recursos/buscarRecursos', 'RecursoController::buscarRecursos');
$routes->get('/recursos/filtrosBusqueda', 'RecursoController::filtrosBusqueda');

// Catalogo
$routes->get('/catalogo', 'CatalogoController::index');
$routes->get('catalogo/subcategorias/(:num)', 'CatalogoController::getSubcategoriasPorCategoria/$1');
