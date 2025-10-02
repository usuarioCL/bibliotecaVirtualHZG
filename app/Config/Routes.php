<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/sobre-plataforma', 'Home::sobrePlataforma');

// Archivos con CORS
$routes->get('/archivo/pdf/(:any)', 'ArchivoController::pdf/$1');
$routes->get('/archivo/imagen/(:any)', 'ArchivoController::imagen/$1');

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
    $routes->get('buscar-por-dni', 'UsuarioController::buscarPorDni');     // Buscar estudiante por DNI para autocompletado
});

// Gestión de Estudiantes/Matrículas
$routes->get('matriculas', 'MatriculaController::index');                  // Vista principal de estudiantes
$routes->group('matriculas', function($routes) {
    $routes->get('/', 'MatriculaController::index');                       // Lista de estudiantes matriculados
    $routes->get('crear', 'MatriculaController::crear');                   // Formulario para matricular estudiante
    $routes->post('crear', 'MatriculaController::guardar');                // Procesar nueva matrícula
    $routes->get('detalle/(:num)', 'MatriculaController::detalle/$1');     // Detalles de un estudiante
    $routes->get('editar/(:num)', 'MatriculaController::editar/$1');       // Formulario de edición
    $routes->post('actualizar/(:num)', 'MatriculaController::actualizar/$1'); // Actualizar datos del estudiante
    $routes->post('cambiar-estado', 'MatriculaController::cambiarEstado'); // Activar/desactivar matrícula
    $routes->get('filtrar', 'MatriculaController::filtrar');               // Filtrar estudiantes
    $routes->get('exportar', 'MatriculaController::exportar');             // Exportar lista de estudiantes
});

// Gestión de Docentes
$routes->get('docentes', 'DocenteController::index');                      // Vista principal de docentes
$routes->group('docentes', function($routes) {
    $routes->get('/', 'DocenteController::index');                         // Lista de docentes
    $routes->post('guardar', 'DocenteController::guardar');                // Crear nuevo docente
    $routes->get('detalle/(:num)', 'DocenteController::detalle/$1');       // Detalles de un docente
    $routes->post('cambiar-estado', 'DocenteController::cambiarEstado');   // Activar/desactivar docente
    $routes->get('buscar-por-dni', 'DocenteController::buscarPorDni');     // Buscar persona por DNI para autocompletado
    $routes->get('filtrar', 'DocenteController::filtrar');                // Filtrar docentes
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
$routes->get('Administrador/vistas/RecursosPopulares', 'AdminController::VistaRecursosPopulares');

// Importación de datos
$routes->get('admin/importar-datos', 'AdminController::importarDatos');
$routes->get('admin/descargar-plantilla/(:segment)', 'AdminController::descargarPlantilla/$1');
$routes->post('admin/preview-excel', 'AdminController::previewExcel');
$routes->post('admin/preview-csv', 'AdminController::previewCsv');
$routes->post('admin/procesar-importacion', 'AdminController::procesarImportacion');
$routes->post('admin/procesar-importacion-excel', 'AdminController::procesarImportacionExcel');

//Recursos
$routes->get('/recursos', 'RecursoController::index');
$routes->get('/recursos/crear', 'RecursoController::crear');
$routes->get('/recursos/crear-modal', 'RecursoController::crearModal');
$routes->get('/recursos/pdf', 'RecursoController::exportarPdf');
$routes->post('/recursos/guardar', 'RecursoController::guardar');
$routes->get('/recursos/editar/(:num)', 'RecursoController::editar/$1'); 
$routes->get('/recursos/modal-editar/(:num)', 'RecursoController::modalEditar/$1');
$routes->post('/recursos/actualizar/(:num)', 'RecursoController::actualizar/$1');
$routes->get('/recursos/eliminar/(:num)', 'RecursoController::eliminar/$1');
$routes->post('/recursos/eliminar/(:num)', 'RecursoController::eliminar/$1');
$routes->get('/recursos/detalles/(:num)', 'RecursoController::detalles/$1');
$routes->get('/recursos/migrarRutasImagenes', 'RecursoController::migrarRutasImagenes');
$routes->get('/recursos/limpiarRutasImagenes', 'RecursoController::limpiarRutasImagenes');
$routes->get('/recursos/limpiarDuplicados', 'RecursoController::limpiarDuplicados');
$routes->get('/recursos/sincronizarImagenes', 'RecursoController::sincronizarImagenes');
$routes->get('/recursos/actualizarRutasImagenes', 'RecursoController::actualizarRutasImagenes');

// Autores (CRUD)
$routes->group('autores', function($routes) {
    $routes->get('/', 'AutorController::index');
    $routes->get('crear', 'AutorController::crear');
    $routes->post('guardar', 'AutorController::guardar');
    $routes->get('editar/(:num)', 'AutorController::editar/$1');
    $routes->post('actualizar/(:num)', 'AutorController::actualizar/$1');
    $routes->post('eliminar/(:num)', 'AutorController::eliminar/$1');
    $routes->get('buscar', 'AutorController::buscar');
});

// Ruta PDF (deshabilitada temporalmente)
// $routes->get('recurso/ver/(:num)', 'RecursoController::ver/$1');

// Buscar recursos
$routes->get('/recursos/buscarRecursos', 'RecursoController::buscarRecursos');
$routes->get('/recursos/filtrosBusqueda', 'RecursoController::filtrosBusqueda');

// Catalogo
$routes->get('/catalogo', 'CatalogoController::index');
$routes->get('catalogo/subcategorias/(:num)', 'CatalogoController::getSubcategoriasPorCategoria/$1');
$routes->get('catalogo/mis-prestamos', 'CatalogoController::misPrestamos');
$routes->get('catalogo/favoritos', 'CatalogoController::favoritos');
$routes->get('catalogo/insertar-datos-prueba', 'CatalogoController::insertarDatosPrueba');

// Recursos Digitales
$routes->get('/recurso-digital', 'RecursoDigitalController::index');

// Recursos Físicos
$routes->get('/recurso-fisico', 'RecursoFisicoController::index');

// Sanciones
$routes->get('/sanciones', 'SancionController::index');
$routes->get('/sanciones/crear', 'SancionController::crear');
$routes->post('/sanciones/guardar', 'SancionController::guardar');
$routes->get('/sanciones/editar/(:num)', 'SancionController::editar/$1');
$routes->post('/sanciones/actualizar/(:num)', 'SancionController::actualizar/$1');
$routes->post('/sanciones/eliminar/(:num)', 'SancionController::eliminar/$1');
$routes->get('/sanciones/ver/(:num)', 'SancionController::ver/$1');
$routes->get('/sanciones/buscar', 'SancionController::buscar');

// Tipos de sanción
$routes->get('/sanciones/tipos', 'SancionController::tiposSancion');
$routes->post('/sanciones/crear-tipo', 'SancionController::crearTipo');
$routes->post('/sanciones/eliminar-tipo/(:num)', 'SancionController::eliminarTipo/$1');
