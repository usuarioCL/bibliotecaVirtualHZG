<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PrestamoModel;
use DateTime;


class PrestamoController extends Controller
{
    protected $prestamoModel;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->prestamoModel = new PrestamoModel();
    }

    /**
     * Página principal - Préstamos Activos
     */
    public function index()
    {
        try {
            $prestamos = $this->prestamoModel->getPrestamosActivos();
            $estadisticas = $this->prestamoModel->getEstadisticasPrestamos();

            $data = [
                'title' => 'Préstamos Activos',
                'prestamos' => $prestamos,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/index', $data);
        } catch (\Exception $e) {
            // En caso de error, mostrar lista vacía y estadísticas en cero
            log_message('error', 'Error en PrestamoController::index(): ' . $e->getMessage());
            
            $data = [
                'title' => 'Préstamos Activos',
                'prestamos' => [],
                'estadisticas' => [
                    'total_prestamos' => 0,
                    'vencidos_hoy' => 0,
                    'proximos_vencer' => 0,
                    'renovaciones_pendientes' => 0
                ]
            ];

            return view('Administrador/prestamos/index', $data);
        }
    }

    /**
     * Solicitudes Pendientes
     */
    public function solicitudes()
    {
        // Verificar autenticación antes de continuar
        if (!session()->get('logged_in')) {
            log_message('warning', 'Usuario no autenticado intentando acceder a solicitudes');
            return redirect()->to(base_url('login'));
        }

        try {
            log_message('info', 'Accediendo a solicitudes pendientes - Usuario: ' . session()->get('nomuser'));
            $solicitudes = $this->prestamoModel->getSolicitudesPendientes();
            
            $estadisticas = [
                'total_solicitudes' => count($solicitudes),
                'hoy' => count(array_filter($solicitudes, function($s) {
                    return date('Y-m-d', strtotime($s['fecha_solicitud'])) == date('Y-m-d');
                })),
                'esta_semana' => count(array_filter($solicitudes, function($s) {
                    return date('Y-W', strtotime($s['fecha_solicitud'])) == date('Y-W');
                })),
                'esperando_aprobacion' => count($solicitudes)
            ];

            $data = [
                'title' => 'Solicitudes Pendientes',
                'solicitudes' => $solicitudes,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/solicitudes', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::solicitudes(): ' . $e->getMessage());
            
            // En caso de error, mostrar una lista vacía y estadísticas en cero
            $data = [
                'title' => 'Solicitudes Pendientes',
                'solicitudes' => [],
                'estadisticas' => [
                    'total_solicitudes' => 0,
                    'hoy' => 0,
                    'esta_semana' => 0,
                    'esperando_aprobacion' => 0
                ]
            ];

            return view('Administrador/prestamos/solicitudes', $data);
        }
    }

    /**
     * Muestra el formulario de solicitud de préstamo para un recurso específico
     * Se usa para mostrar en la interfaz del usuario final
     */
    public function formulario($idRecurso = null)
    {
        if (!$idRecurso) {
            return $this->response->setStatusCode(404)->setBody('Recurso no especificado');
        }

        // Cargar el modelo de recursos para obtener detalles del recurso
        $recursoModel = new \App\Models\RecursoModel();
        $recurso = $recursoModel->find($idRecurso);

        if (!$recurso) {
            return $this->response->setStatusCode(404)->setBody('Recurso no encontrado');
        }

        // Preparar datos para la vista
        $data = [
            'recurso' => $recurso,
            'idRecurso' => $idRecurso
        ];

        // Renderizar solo la vista parcial del formulario
        return view('prestamos/formulario', $data);
    }
    


    /**
     * Procesa la solicitud de préstamo desde el formulario
     */
    public function solicitar()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud inválida'
            ]);
        }
        
        // Verificar si el usuario está autenticado
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión para solicitar un préstamo'
            ]);
        }
        
        // Obtener datos del formulario
        $idRecurso = $this->request->getPost('idRecurso');
        $fechaPrestamo = $this->request->getPost('fechaPrestamo');
        $horaInicio = $this->request->getPost('horaInicio');
        $horaFin = $this->request->getPost('horaFin');
        
        // Validar datos obligatorios
        if (!$idRecurso || !$fechaPrestamo || !$horaInicio || !$horaFin) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Todos los campos obligatorios deben ser completados'
            ]);
        }
        
        // Las validaciones se manejan en el frontend con validación inline
        // Solo creamos los objetos DateTime para el procesamiento
        $horaInicioObj = DateTime::createFromFormat('H:i', $horaInicio);
        $horaFinObj = DateTime::createFromFormat('H:i', $horaFin);
        
        // Crear fecha y hora completa para el préstamo
        $fechaHoraPrestamo = $fechaPrestamo . ' ' . $horaInicio . ':00';
        
        // La devolución será el mismo día a la hora de fin especificada
        $fechaHoraDevolucion = $fechaPrestamo . ' ' . $horaFin . ':00';
        
        try {
            // Obtener datos del usuario
            $idUsuario = session()->get('id');
            $nivelAcceso = session()->get('nivelacceso');
            $nombreUsuario = session()->get('nomuser');
            
            $db = \Config\Database::connect();
            
            // Si no hay ID en la sesión, buscar por nombre de usuario
            if (!$idUsuario && $nombreUsuario) {
                $usuario = $db->table('usuarios')
                    ->where('nomuser', $nombreUsuario)
                    ->get()->getRow();
                    
                if ($usuario) {
                    $idUsuario = $usuario->idusuario;
                    // Actualizar la sesión con el ID correcto
                    session()->set('id', $idUsuario);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Usuario no encontrado'
                    ]);
                }
            }
            
            // Verificar que tenemos los datos necesarios
            if (!$idUsuario || !$nivelAcceso) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de sesión incompletos'
                ]);
            }
            
            // Asegurar que existe una matrícula básica para usar
            $matriculaBasica = $db->table('matriculas')
                ->orderBy('idmatricula', 'ASC')
                ->get()->getRow();
                
            if (!$matriculaBasica) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No hay matrículas disponibles en el sistema. Por favor contacte al administrador.'
                ]);
            }
            
            // Para administradores y docentes, usar la primera matrícula disponible
            if ($nivelAcceso === 'admin' || $nivelAcceso === 'docente') {
                $idMatricula = $matriculaBasica->idmatricula;
            } else if ($nivelAcceso === 'estudiante') {
                // Obtener matrícula del estudiante
                $prestamoModel = new PrestamoModel();
                $idMatricula = $prestamoModel->getMatriculaByUsuario($idUsuario);
                
                if (!$idMatricula) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No se encontró matrícula activa asociada a su usuario.'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tipo de usuario no válido para solicitar préstamos.'
                ]);
            }
            
            // Verificar disponibilidad del recurso
            $recursoModel = new \App\Models\RecursoModel();
            $recurso = $recursoModel->find($idRecurso);
            
            if (!$recurso) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El recurso no existe'
                ]);
            }
            
            // Verificar si es un array o un objeto
            $estado = is_array($recurso) ? $recurso['estado'] : $recurso->estado;
            $stock = is_array($recurso) ? $recurso['stock'] : $recurso->stock;
            
            if ($estado !== 'disponible' || $stock <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El recurso no está disponible para préstamo en este momento'
                ]);
            }
            
            // Crear registro de préstamo
            $prestamo = [
                'idmatricula' => $idMatricula,
                'idusuario' => $idUsuario,
                'idrecurso' => $idRecurso,
                'fechaprestamo' => $fechaHoraPrestamo,
                'fechadevolucion' => $fechaHoraDevolucion
            ];
            
            // Insertar el préstamo
            $prestamoModel = new PrestamoModel();
            $prestamoModel->insert($prestamo);
            $idPrestamo = $prestamoModel->insertID();
            
            // Crear registro en tabla de solicitudes
            $db = \Config\Database::connect();
            $db->table('solicitud')->insert([
                'validado' => false,
                'idprestamo' => $idPrestamo
            ]);
            
            // Registrar en historial de usuario si existe el helper
            if (function_exists('historial_helper')) {
                helper('historial');
                if (function_exists('registrar_accion')) {
                    // Verificar si es un array o un objeto
                    $titulo = is_array($recurso) ? $recurso['titulo'] : $recurso->titulo;
                    registrar_accion("Solicitó préstamo del recurso #$idRecurso: $titulo");
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Solicitud de préstamo enviada correctamente'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al procesar solicitud de préstamo: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ha ocurrido un error al procesar su solicitud. Por favor, inténtelo nuevamente más tarde.'
            ]);
        }
    }
    
    /**
     * Devoluciones
     */
    public function devoluciones()
    {
        try {
            $devoluciones = $this->prestamoModel->getDevolucionesHoy();
            $estadisticas = $this->prestamoModel->getEstadisticasDevoluciones();

            $data = [
                'title' => 'Devoluciones',
                'devoluciones' => $devoluciones,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/devoluciones', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::devoluciones(): ' . $e->getMessage());
            
            $data = [
                'title' => 'Devoluciones',
                'devoluciones' => $this->getDatosPruebaDevoluciones(),
                'estadisticas' => [
                    'devoluciones_hoy' => 0,
                    'con_retraso' => 0,
                    'danos_reportados' => 0,
                    'multas_generadas' => 0
                ]
            ];

            return view('Administrador/prestamos/devoluciones', $data);
        }
    }

    /**
     * Historial Completo
     */
    public function historial()
    {
        try {
            $historial = $this->prestamoModel->getHistorialCompleto();
            $estadisticas = $this->prestamoModel->getEstadisticasHistorial();

            $data = [
                'title' => 'Historial de Préstamos',
                'historial' => $historial,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/historial', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::historial(): ' . $e->getMessage());
            
            $data = [
                'title' => 'Historial de Préstamos',
                'historial' => $this->getDatosPruebaHistorial(),
                'estadisticas' => [
                    'total_registros' => 0,
                    'este_mes' => 0,
                    'promedio_mensual' => 0,
                    'tasa_devolucion' => 0
                ]
            ];

            return view('Administrador/prestamos/historial', $data);
        }
    }
    
    
    /**
     * Aprobar una solicitud de préstamo
     */
    public function aprobar()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud inválida'
            ]);
        }

        // Verificar si el usuario está autenticado y es admin/docente
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $nivelAcceso = session()->get('nivelacceso');
        if (!in_array($nivelAcceso, ['admin', 'docente'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para aprobar solicitudes'
            ]);
        }

        // Obtener ID de la solicitud
        $idsolicitud = $this->request->getPost('idsolicitud');
        
        if (!$idsolicitud) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de solicitud requerido'
            ]);
        }

        try {
            // Aprobar la solicitud
            $resultado = $this->prestamoModel->aprobarSolicitud($idsolicitud);
            
            // Registrar acción en historial si existe el helper
            if ($resultado['success'] && function_exists('registrar_accion')) {
                helper('historial');
                registrar_accion(
                    'Aprobación de Solicitud de Préstamo',
                    session()->get('nomuser'),
                    null,
                    session()->get('nivelacceso'),
                    "Solicitud #{$idsolicitud} aprobada exitosamente"
                );
            }

            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::aprobar(): ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Rechazar una solicitud de préstamo
     */
    public function rechazar()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud inválida'
            ]);
        }

        // Verificar si el usuario está autenticado y es admin/docente
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $nivelAcceso = session()->get('nivelacceso');
        if (!in_array($nivelAcceso, ['admin', 'docente'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para rechazar solicitudes'
            ]);
        }

        // Obtener datos del formulario
        $idsolicitud = $this->request->getPost('idsolicitud');
        $motivo = $this->request->getPost('motivo') ?? '';
        
        if (!$idsolicitud) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de solicitud requerido'
            ]);
        }

        try {
            // Rechazar la solicitud
            $resultado = $this->prestamoModel->rechazarSolicitud($idsolicitud, $motivo);
            
            // Registrar acción en historial si existe el helper
            if ($resultado['success'] && function_exists('registrar_accion')) {
                helper('historial');
                registrar_accion(
                    'Rechazo de Solicitud de Préstamo',
                    session()->get('nomuser'),
                    null,
                    session()->get('nivelacceso'),
                    "Solicitud #{$idsolicitud} rechazada. Motivo: {$motivo}"
                );
            }

            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::rechazar(): ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Aprobar múltiples solicitudes disponibles
     */
    public function aprobarTodas()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud inválida'
            ]);
        }

        // Verificar si el usuario está autenticado y es admin/docente
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $nivelAcceso = session()->get('nivelacceso');
        if (!in_array($nivelAcceso, ['admin', 'docente'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para aprobar solicitudes'
            ]);
        }

        try {
            // Obtener IDs de solicitudes específicas (opcional)
            $solicitudesParam = $this->request->getPost('solicitudes');
            $idsolicitudes = [];
            
            if (!empty($solicitudesParam)) {
                // Si viene como string JSON, decodificarlo
                if (is_string($solicitudesParam)) {
                    $idsolicitudes = json_decode($solicitudesParam, true) ?? [];
                } else {
                    $idsolicitudes = $solicitudesParam;
                }
            }
            
            // Log de debug
            log_message('info', 'PrestamoController::aprobarTodas() - IDs recibidos: ' . json_encode($idsolicitudes));
            
            // Validar que tenemos IDs para procesar
            if (empty($idsolicitudes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se proporcionaron IDs de solicitudes para aprobar'
                ]);
            }
            
            // Aprobar solicitudes
            $resultados = $this->prestamoModel->aprobarSolicitudesDisponibles($idsolicitudes);
            
            // Log de debug
            log_message('info', 'PrestamoController::aprobarTodas() - Resultados: ' . json_encode($resultados));
            
            // Registrar acción en historial si existe el helper
            if ($resultados['aprobadas'] > 0 && function_exists('registrar_accion')) {
                helper('historial');
                registrar_accion(
                    'Aprobación Masiva de Solicitudes',
                    session()->get('nomuser'),
                    null,
                    session()->get('nivelacceso'),
                    "Aprobadas: {$resultados['aprobadas']} solicitudes. Rechazadas: {$resultados['rechazadas']}"
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Se aprobaron {$resultados['aprobadas']} solicitudes exitosamente",
                'data' => $resultados
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::aprobarTodas(): ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener detalles de una solicitud de préstamo
     */
    public function detalleSolicitud()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud inválida'
            ]);
        }

        // Verificar si el usuario está autenticado
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        // Obtener ID de la solicitud
        $idsolicitud = $this->request->getPost('idsolicitud');
        
        if (!$idsolicitud) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de solicitud requerido'
            ]);
        }

        try {
            // Obtener detalles de la solicitud
            $detalle = $this->prestamoModel->getDetalleSolicitud($idsolicitud);
            
            if (!$detalle) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Solicitud no encontrada'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $detalle
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::detalleSolicitud(): ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Procesar devolución de un préstamo
     */
    public function procesarDevolucion()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar si el usuario está autenticado y es admin/docente
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        $nivelAcceso = session()->get('nivelacceso');
        if (!in_array($nivelAcceso, ['admin', 'docente'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para procesar devoluciones'
            ]);
        }

        // Obtener datos del formulario
        $idprestamo = $this->request->getPost('idprestamo');
        $observaciones = $this->request->getPost('observaciones') ?? '';
        
        if (!$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido'
            ]);
        }

        try {
            $resultado = $this->prestamoModel->procesarDevolucion($idprestamo, $observaciones);
            
            // Registrar acción en historial si existe el helper
            if ($resultado['success'] && function_exists('registrar_accion')) {
                helper('historial');
                registrar_accion(
                    'Devolución de Préstamo',
                    session()->get('nomuser'),
                    null,
                    session()->get('nivelacceso'),
                    "Préstamo #{$idprestamo} devuelto. Observaciones: {$observaciones}"
                );
            }
            
            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::procesarDevolucion(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Cancelar un préstamo activo
     */
    public function cancelar()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud inválida'
            ]);
        }

        // Verificar si el usuario está autenticado y es admin/docente
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $nivelAcceso = session()->get('nivelacceso');
        if (!in_array($nivelAcceso, ['admin', 'docente'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para cancelar préstamos'
            ]);
        }

        // Obtener datos del formulario
        $idprestamo = $this->request->getPost('idprestamo');
        $motivo = $this->request->getPost('motivo') ?? '';
        
        if (!$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido'
            ]);
        }

        try {
            // Cancelar el préstamo
            $resultado = $this->prestamoModel->cancelarPrestamo($idprestamo, $motivo);
            
            // Registrar acción en historial si existe el helper
            if ($resultado['success'] && function_exists('registrar_accion')) {
                helper('historial');
                registrar_accion(
                    'Cancelación de Préstamo',
                    session()->get('nomuser'),
                    null,
                    session()->get('nivelacceso'),
                    "Préstamo #{$idprestamo} cancelado. Motivo: {$motivo}"
                );
            }

            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::cancelar(): ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Renovar un préstamo activo
     */
    public function renovarPrestamo()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar si el usuario está autenticado y es admin/docente
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        $nivelAcceso = session()->get('nivelacceso');
        if (!in_array($nivelAcceso, ['admin', 'docente'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para renovar préstamos'
            ]);
        }

        // Obtener datos del formulario
        $idprestamo = $this->request->getPost('idprestamo');
        $motivo = $this->request->getPost('motivo') ?? '';
        $nuevaFechaDevolucion = $this->request->getPost('nueva_fecha_devolucion');
        
        if (!$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido'
            ]);
        }

        // Validar nueva fecha de devolución
        if (!$nuevaFechaDevolucion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nueva fecha de devolución requerida'
            ]);
        }

        // Validar que la fecha sea válida y posterior a hoy
        try {
            $fechaDevolucion = new \DateTime($nuevaFechaDevolucion);
            $hoy = new \DateTime();
            
            if ($fechaDevolucion <= $hoy) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La fecha de devolución debe ser posterior a hoy'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Fecha de devolución inválida'
            ]);
        }

        try {
            $resultado = $this->prestamoModel->renovarPrestamoConFecha($idprestamo, $nuevaFechaDevolucion, $motivo);
            
            // Registrar acción en historial si existe el helper
            if ($resultado['success'] && function_exists('registrar_accion')) {
                helper('historial');
                registrar_accion(
                    'Renovación de Préstamo',
                    session()->get('nomuser'),
                    null,
                    session()->get('nivelacceso'),
                    "Préstamo #{$idprestamo} renovado hasta {$nuevaFechaDevolucion}. Motivo: {$motivo}"
                );
            }
            
            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::renovarPrestamo(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener detalles completos de un préstamo activo
     */
    public function obtenerDetallePrestamo()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar si el usuario está autenticado
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        // Obtener ID del préstamo
        $idprestamo = $this->request->getPost('idprestamo') ?? $this->request->getGet('idprestamo');
        
        if (!$idprestamo || !is_numeric($idprestamo)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido y debe ser numérico'
            ]);
        }

        try {
            $detalle = $this->prestamoModel->obtenerDetallePrestamo($idprestamo);
            
            if (!$detalle) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Préstamo no encontrado o ya ha sido devuelto'
                ]);
            }

            log_message('info', 'Detalles del préstamo obtenidos correctamente');
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $detalle
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::obtenerDetallePrestamo(): ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }
}
