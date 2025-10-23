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

// Ruta pública para detalles de recursos (sin autenticación)
$routes->get('/recurso/detalles/(:num)', 'RecursoController::detalles/$1');

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
    $routes->post('actualizar', 'UsuarioController::actualizar');         // Actualizar usuario y persona
    $routes->delete('eliminar/(:num)', 'UsuarioController::eliminar/$1');  // Eliminar usuario por ID
    $routes->delete('eliminar-simple/(:num)', 'UsuarioController::eliminarSimple/$1');  // Eliminación simple para pruebas
    $routes->get('listar', 'UsuarioController::listar');                  // Listar usuarios (JSON)
    $routes->get('obtener/(:num)', 'UsuarioController::obtener/$1');      // Obtener usuario por ID
    $routes->get('buscar-por-dni', 'UsuarioController::buscarPorDni');     // Buscar usuario por DNI
    $routes->get('test/(:any)', 'UsuarioController::test/$1');             // Método de prueba de conectividad
    $routes->get('test', 'UsuarioController::test');                      // Método de prueba sin parámetros
    $routes->get('verificar-elegibilidad', 'UsuarioController::verificarElegibilidad'); // Verificar si puede crear usuario
    $routes->get('info-matricula/(:num)', 'UsuarioController::infoMatricula/$1'); // Info matrícula de persona
    $routes->post('buscar-ajax', 'UsuarioController::buscarAjax');         // Buscar usuarios para préstamos (AJAX)
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

// Ruta para obtener estudiantes
$routes->get('usuarios/estudiantes', 'UsuarioController::estudiantes');

// Panel de administración
$routes->get('/admin', 'AdminController::dashboard');
$routes->get('/admin/dashboard-default', 'AdminController::dashboardDefault');
$routes->get('/admin/login', 'AdminController::login');
$routes->get('/admin/register', 'AdminController::register');

//Vistas de administrador
$routes->get('Administrador/vistas/UsuariosRoles', 'AdminController::VistaUsuariosRoles');

// Rutas para Reportes y Estadísticas
$routes->get('Administrador/vistas/PrestamosAlumnos', 'ReporteController::prestamosUsuarios');
$routes->get('Administrador/vistas/RecursosPopulares', 'ReporteController::recursosPopulares');
$routes->get('reportes/inventario', 'ReporteController::inventario');

// Importación de datos
$routes->get('admin/importar-datos', 'AdminController::importarDatos');
$routes->get('admin/descargar-plantilla/(:segment)', 'AdminController::descargarPlantilla/$1');
$routes->post('admin/preview-excel', 'AdminController::previewExcel');
$routes->post('admin/preview-csv', 'AdminController::previewCsv');
$routes->post('admin/procesar-importacion', 'AdminController::procesarImportacion');
$routes->post('admin/procesar-importacion-excel', 'AdminController::procesarImportacionExcel');

// Administración de datos - Respaldos
$routes->get('admin/backup', 'AdminController::backup');
$routes->post('admin/crear-backup', 'AdminController::crearBackup');
$routes->post('admin/restaurar-backup', 'AdminController::restaurarBackup');
$routes->get('admin/descargar-backup/(:segment)', 'AdminController::descargarBackup/$1');
$routes->delete('admin/eliminar-backup/(:segment)', 'AdminController::eliminarBackup/$1');

// Configuración del sistema
$routes->get('admin/configuracion', 'AdminController::configuracion');
$routes->post('admin/guardar-configuracion', 'AdminController::guardarConfiguracion');
$routes->post('admin/restaurar-configuracion', 'AdminController::restaurarConfiguracion');

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
// Rutas de mantenimiento y debug (remover en producción)
$routes->get('/recursos/actualizarRutasImagenes', 'RecursoController::actualizarRutasImagenes');
$routes->get('/recursos/debugImagenes', 'RecursoController::debugImagenes');
$routes->get('/recursos/testPDF/(:num)', 'RecursoController::testPDF/$1');

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
$routes->post('/recursos/buscar-disponibles-ajax', 'RecursoController::buscarDisponiblesAjax'); // Buscar recursos disponibles para préstamos (AJAX)

// Catalogo
$routes->get('/catalogo', 'CatalogoController::index');
$routes->get('catalogo/subcategorias/(:num)', 'CatalogoController::getSubcategoriasPorCategoria/$1');
$routes->get('catalogo/mis-prestamos', 'CatalogoController::misPrestamos');
$routes->get('catalogo/favoritos', 'CatalogoController::favoritos');
$routes->get('catalogo/insertar-datos-prueba', 'CatalogoController::insertarDatosPrueba');
$routes->post('catalogo/toggle-favorito', 'CatalogoController::toggleFavorito');
$routes->post('catalogo/quitar-favorito', 'CatalogoController::quitarFavorito');

// Recursos Digitales
$routes->get('/recurso-digital', 'RecursoDigitalController::index');

// Recursos Físicos
$routes->get('/recurso-fisico', 'RecursoFisicoController::index');

// Gestión de Editoriales
$routes->get('/editoriales', 'EditorialController::index');
$routes->get('/editoriales/crear', 'EditorialController::crear');
$routes->post('/editoriales/crear', 'EditorialController::crear');
$routes->get('/editoriales/editar/(:num)', 'EditorialController::editar/$1');
$routes->post('/editoriales/editar/(:num)', 'EditorialController::editar/$1');
$routes->post('/editoriales/eliminar/(:num)', 'EditorialController::eliminar/$1');
$routes->get('/editoriales/detalles/(:num)', 'EditorialController::detalles/$1');
$routes->get('/editoriales/getEditorialesAjax', 'EditorialController::getEditorialesAjax');
$routes->get('/editoriales/buscar', 'EditorialController::buscar');
$routes->get('/editoriales/estadisticas', 'EditorialController::estadisticas');
// Rutas AJAX para panel de administración
$routes->get('/editoriales/ajax', 'EditorialController::ajaxIndex');
$routes->get('/editoriales/ajax_detalles/(:num)', 'EditorialController::ajaxDetalles/$1');

// Historial de Usuarios
$routes->get('/historial-usuarios', 'HistorialUsuarioController::index');
$routes->get('/historial-usuarios/ajax', 'HistorialUsuarioController::ajaxIndex');
$routes->get('/historial-usuarios/getHistorialAjax', 'HistorialUsuarioController::getHistorialAjax');
$routes->get('/historial-usuarios/estadisticas', 'HistorialUsuarioController::estadisticas');
$routes->post('/historial-usuarios/registrar', 'HistorialUsuarioController::registrarAccion');

// Ejemplares Físicos
$routes->get('/ejemplares-fisicos/(:num)', 'EjemplarFisicoController::index/$1');
$routes->get('/ejemplares-fisicos/modal/(:num)', 'EjemplarFisicoController::modal/$1');
$routes->post('/ejemplares-fisicos/crear', 'EjemplarFisicoController::crearEjemplares');
$routes->post('/ejemplares-fisicos/actualizar-estado', 'EjemplarFisicoController::actualizarEstado');
$routes->get('/ejemplares-fisicos/buscar', 'EjemplarFisicoController::buscar');
$routes->get('/ejemplares-fisicos/codigo/(:any)', 'EjemplarFisicoController::obtenerPorCodigo/$1');
$routes->delete('/ejemplares-fisicos/eliminar/(:num)', 'EjemplarFisicoController::eliminar/$1');
$routes->post('/ejemplares-fisicos/restaurar/(:num)', 'EjemplarFisicoController::restaurar/$1');
$routes->get('/ejemplares-fisicos/estadisticas/(:num)', 'EjemplarFisicoController::estadisticas/$1');

// Sistema de Sanciones
$routes->get('/sanciones', 'SancionController::activas');
$routes->get('/sanciones/historial', 'SancionController::historial');
$routes->match(['get', 'post'], '/sanciones/crear', 'SancionController::crear');
$routes->post('/sanciones/guardar', 'SancionController::guardarSancion');
$routes->get('/sanciones/ver/(:num)', 'SancionController::ver/$1');
$routes->get('/sanciones/editar/(:num)', 'SancionController::editar/$1');
$routes->post('/sanciones/editar/(:num)', 'SancionController::editar/$1');
$routes->post('/sanciones/eliminar/(:num)', 'SancionController::eliminar/$1');
$routes->post('/sanciones/cambiar-estado', 'SancionController::cambiarEstado');
$routes->get('/sanciones/buscar-personas', 'SancionController::buscarPersonas');
$routes->get('/sanciones/persona/(:num)', 'SancionController::obtenerSancionesPersona/$1');
$routes->get('/sanciones/detalles-levantamiento/(:num)', 'SancionController::obtenerDetallesParaLevantamiento/$1');
$routes->post('/sanciones/levantar', 'SancionController::levantarSancion');
$routes->post('/sanciones/levantar-todas', 'SancionController::levantarTodas');
$routes->get('/sanciones/estadisticas', 'SancionController::estadisticas');
$routes->get('/sanciones/exportar-excel', 'SancionController::exportarExcel');

// Sistema de Notificaciones
$routes->get('/notificaciones/contar', 'NotificacionController::contarNoLeidas');
$routes->get('/notificaciones/obtener', 'NotificacionController::obtenerNotificaciones');
$routes->post('/notificaciones/marcar-leida', 'NotificacionController::marcarLeida');
$routes->post('/notificaciones/marcar-todas-leidas', 'NotificacionController::marcarTodasLeidas');

// Sistema de Préstamos
$routes->get('/prestamos', 'PrestamoController::index');                      // Préstamos Activos
$routes->get('/solicitudes', 'PrestamoController::solicitudes');              // Solicitudes Pendientes
$routes->get('/devoluciones', 'PrestamoController::devoluciones');            // Devoluciones
$routes->get('/historial-prestamos', 'PrestamoController::historial');        // Historial Completo

// Formulario de préstamos (para usuarios)
$routes->get('/prestamo/formulario/(:num)', 'PrestamoController::formulario/$1');  // Formulario de solicitud
$routes->post('/prestamo/solicitar', 'PrestamoController::solicitar');            // Procesar solicitud

// Gestión de solicitudes de préstamo (Admin/Docente)
$routes->post('/prestamos/aprobar', 'PrestamoController::aprobar');               // Aprobar solicitud individual
$routes->post('/prestamos/rechazar', 'PrestamoController::rechazar');             // Rechazar solicitud individual
$routes->post('/prestamos/aprobarTodas', 'PrestamoController::aprobarTodas');     // Aprobar solicitudes masivamente
$routes->post('/prestamos/rechazarTodas', 'PrestamoController::rechazarTodas');   // Rechazar solicitudes masivamente
$routes->post('/prestamos/detalleSolicitud', 'PrestamoController::detalleSolicitud'); // Obtener detalles de solicitud

// Gestión de préstamos activos (Admin/Docente)
$routes->post('/prestamos/crear', 'PrestamoController::crearPrestamo');           // Crear nuevo préstamo
$routes->post('/prestamos/cancelar', 'PrestamoController::cancelar');             // Cancelar préstamo activo
$routes->get('/prestamos/obtener-tipos-sancion', 'PrestamoController::obtenerTiposSancion'); // Obtener tipos de sanción
$routes->post('/prestamos/procesar-devolucion', 'PrestamoController::procesarDevolucion'); // Procesar devolución
$routes->post('/prestamos/renovar', 'PrestamoController::renovarPrestamo');       // Renovar préstamo
$routes->post('/prestamos/detalle', 'PrestamoController::obtenerDetallePrestamo'); // Obtener detalles de préstamo
$routes->post('/prestamos/obtenerDetalleDevolucion', 'PrestamoController::obtenerDetalleDevolucion'); // Obtener detalles de devolución
$routes->post('/prestamos/obtenerObservaciones', 'PrestamoController::obtenerObservaciones'); // Obtener observaciones desde logs
$routes->post('/prestamos/buscarPrestamoPorCodigo', 'PrestamoController::buscarPrestamoPorCodigo'); // Buscar préstamo por código
$routes->post('/prestamos/procesarDevolucionCompleta', 'PrestamoController::procesarDevolucionCompleta'); // Procesar devolución completa
$routes->post('/prestamos/eliminarHistorial', 'PrestamoController::eliminarHistorial'); // Eliminar registro del historial
$routes->post('/prestamos/eliminarTodoHistorial', 'PrestamoController::eliminarTodoHistorial'); // Eliminar todo el historial

// =====================================
// RUTAS PARA MODALES DEL SISTEMA
// =====================================

// Modal: Mi Perfil - Gestión del perfil del usuario actual
$routes->get('/admin/mi-perfil', 'AdminController::miPerfil');

// Modal: Mis Tareas - Gestión de tareas y actividades del usuario
$routes->get('/admin/mis-tareas', 'AdminController::misTareas');

// Modal: Ayuda - Centro de ayuda, documentación y soporte
$routes->get('/admin/ayuda', 'AdminController::ayuda');

// =====================================
// RUTAS PARA GESTIÓN DE CATEGORÍAS
// =====================================

// Vista principal de categorías
$routes->get('/admin/categorias', 'AdminController::categorias');

// CRUD de categorías
$routes->post('/admin/crear-categoria', 'AdminController::crearCategoria');
$routes->post('/admin/editar-categoria/(:num)', 'AdminController::editarCategoria/$1');
$routes->delete('/admin/eliminar-categoria/(:num)', 'AdminController::eliminarCategoria/$1');

// CRUD de subcategorías
$routes->post('/admin/crear-subcategoria', 'AdminController::crearSubcategoria');
$routes->post('/admin/editar-subcategoria/(:num)', 'AdminController::editarSubcategoria/$1');
$routes->delete('/admin/eliminar-subcategoria/(:num)', 'AdminController::eliminarSubcategoria/$1');

// API para obtener subcategorías
$routes->get('/admin/obtener-subcategorias/(:num)', 'AdminController::obtenerSubcategorias/$1');