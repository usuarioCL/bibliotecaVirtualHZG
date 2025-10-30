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
            
            // Obtener solicitudes de préstamo
            $solicitudesPrestamo = $this->prestamoModel->getSolicitudesPendientes();
            
            // Obtener solicitudes de renovación pendientes
            $solicitudesRenovacion = $this->prestamoModel->getSolicitudesRenovacionPendientes();
            
            // Agregar tipo a cada solicitud de préstamo
            foreach ($solicitudesPrestamo as &$solicitud) {
                $solicitud['tipo_solicitud'] = 'prestamo';
            }
            
            // Agregar tipo a cada solicitud de renovación
            foreach ($solicitudesRenovacion as &$solicitud) {
                $solicitud['tipo_solicitud'] = 'renovacion';
            }
            
            // Combinar ambas listas en una sola
            $todasSolicitudes = array_merge($solicitudesPrestamo, $solicitudesRenovacion);
            
            // Ordenar por prioridad y fecha
            usort($todasSolicitudes, function($a, $b) {
                // Prioridad: Alta > Media > Normal
                $prioridadValor = ['Alta' => 3, 'Media' => 2, 'Normal' => 1];
                $prioA = $prioridadValor[$a['prioridad']] ?? 1;
                $prioB = $prioridadValor[$b['prioridad']] ?? 1;
                
                if ($prioA != $prioB) {
                    return $prioB - $prioA; // Descendente (Alta primero)
                }
                
                // Si tienen la misma prioridad, ordenar por fecha (más antiguas primero)
                return strtotime($a['fecha_solicitud']) - strtotime($b['fecha_solicitud']);
            });
            
            // Calcular estadísticas combinadas
            $totalSolicitudes = count($todasSolicitudes);
            
            $estadisticas = [
                'total_solicitudes' => $totalSolicitudes,
                'solicitudes_prestamo' => count($solicitudesPrestamo),
                'solicitudes_renovacion' => count($solicitudesRenovacion),
                'hoy' => count(array_filter($todasSolicitudes, function($s) {
                    return date('Y-m-d', strtotime($s['fecha_solicitud'])) == date('Y-m-d');
                })),
                'esta_semana' => count(array_filter($todasSolicitudes, function($s) {
                    return date('Y-W', strtotime($s['fecha_solicitud'])) == date('Y-W');
                })),
                'esperando_aprobacion' => $totalSolicitudes
            ];

            $data = [
                'title' => 'Solicitudes Pendientes',
                'solicitudes' => $todasSolicitudes,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/solicitudes', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::solicitudes(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // En caso de error, mostrar una lista vacía y estadísticas en cero
            $data = [
                'title' => 'Solicitudes Pendientes',
                'solicitudes' => [],
                'estadisticas' => [
                    'total_solicitudes' => 0,
                    'solicitudes_prestamo' => 0,
                    'solicitudes_renovacion' => 0,
                    'hoy' => 0,
                    'esta_semana' => 0,
                    'esperando_aprobacion' => 0
                ]
            ];

            return view('Administrador/prestamos/solicitudes', $data);
        }
    }

    /**
     * Verificar si un usuario tiene sanciones activas
     */
    public function verificarSanciones()
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

        try {
            $db = \Config\Database::connect();
            
            // Obtener el ID del usuario desde la sesión
            $idUsuario = session()->get('id');
            $nombreUsuario = session()->get('nomuser');
            
            // Si no hay ID en la sesión, buscar por nombre de usuario
            if (!$idUsuario && $nombreUsuario) {
                $usuario = $db->table('usuarios')
                    ->where('nomuser', $nombreUsuario)
                    ->get()->getRow();
                    
                if ($usuario) {
                    $idUsuario = $usuario->idusuario;
                }
            }
            
            if (!$idUsuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }
            
            // Obtener el idpersona del usuario
            $usuario = $db->table('usuarios')
                ->select('idpersona')
                ->where('idusuario', $idUsuario)
                ->get()->getRow();
            
            if (!$usuario || !$usuario->idpersona) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de usuario incompletos'
                ]);
            }
            
            // Verificar si tiene sanciones activas
            $sanciones = $db->table('sanciones s')
                ->select('s.*, ts.tiposancion')
                ->join('tiposancion ts', 'ts.idtiposancion = s.idtiposancion')
                ->where('s.idpersona', $usuario->idpersona)
                ->where('s.estado_sancion', 'activa')
                ->get()
                ->getResultArray();
            
            if (!empty($sanciones)) {
                // El usuario tiene sanciones activas
                $detallesSanciones = [];
                foreach ($sanciones as $sancion) {
                    $detallesSanciones[] = [
                        'tipo' => $sancion['tiposancion'],
                        'detalle' => $sancion['detallesancion'],
                        'fecha_inicio' => $sancion['fecha_inicio'],
                        'fecha_vencimiento' => $sancion['fecha_vencimiento']
                    ];
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'sancionado' => true,
                    'sanciones' => $detallesSanciones,
                    'message' => 'Usuario con sanciones activas'
                ]);
            }
            
            // El usuario no tiene sanciones activas
            return $this->response->setJSON([
                'success' => true,
                'sancionado' => false,
                'message' => 'Usuario sin sanciones'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::verificarSanciones(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al verificar sanciones'
            ]);
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
        $fechaInicio = $this->request->getPost('fechaInicio');
        $fechaEntrega = $this->request->getPost('fechaEntrega');
        $cantidad = $this->request->getPost('cantidadLibros') ?? 1;
        
        // Validar datos obligatorios
        if (!$idRecurso || !$fechaInicio || !$fechaEntrega) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Todos los campos obligatorios deben ser completados'
            ]);
        }
        
        // Validar cantidad
        $cantidad = (int)$cantidad;
        if ($cantidad < 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'La cantidad debe ser al menos 1'
            ]);
        }
        
        // Log para debugging
        log_message('info', 'Procesando solicitud de préstamo - Usuario: ' . session()->get('nomuser') . 
                   ', Recurso: ' . $idRecurso . ', Cantidad: ' . $cantidad . 
                   ', Fecha inicio: ' . $fechaInicio . ', Fecha entrega: ' . $fechaEntrega);
        
        // Validar fechas
        try {
            $fechaInicioObj = new DateTime($fechaInicio);
            $fechaEntregaObj = new DateTime($fechaEntrega);
            $hoy = new DateTime();
            $hoy->setTime(0, 0, 0);
            
            // Validar que la fecha de inicio no sea anterior a hoy
            if ($fechaInicioObj < $hoy) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La fecha de inicio no puede ser anterior a hoy'
                ]);
            }
            
            // Validar que sean días hábiles
            $diaInicio = $fechaInicioObj->format('w'); // 0=domingo, 6=sábado
            $diaEntrega = $fechaEntregaObj->format('w');
            
            if ($diaInicio == 0 || $diaInicio == 6) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La fecha de inicio debe ser un día hábil (lunes a viernes)'
                ]);
            }
            
            if ($diaEntrega == 0 || $diaEntrega == 6) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La fecha de entrega debe ser un día hábil (lunes a viernes)'
                ]);
            }
            
            // Validar que la fecha de entrega sea posterior a la de inicio
            if ($fechaEntregaObj <= $fechaInicioObj) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La fecha de entrega debe ser posterior a la fecha de inicio'
                ]);
            }
            
            // Validar que no sea más de 7 días
            $diff = $fechaInicioObj->diff($fechaEntregaObj);
            if ($diff->days > 7) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El préstamo no puede durar más de 7 días'
                ]);
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Fechas inválidas'
            ]);
        }
        
        // Crear fecha y hora completa para el préstamo (8:00 AM del día de inicio)
        $fechaHoraPrestamo = $fechaInicio . ' 08:00:00';
        
        // La devolución será a las 13:00 PM del día de entrega
        $fechaHoraDevolucion = $fechaEntrega . ' 13:00:00';
        
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
            
            // Validar cantidad solicitada vs stock disponible
            if ($cantidad > $stock) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Solo hay {$stock} ejemplar(es) disponible(s). No se puede solicitar {$cantidad} ejemplar(es)."
                ]);
            }
            
            // Validar que solo los docentes y administradores puedan solicitar múltiples ejemplares
            if ($cantidad > 1 && !in_array($nivelAcceso, ['docente', 'admin'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Solo los docentes y administradores pueden solicitar múltiples ejemplares del mismo recurso'
                ]);
            }
            
            // Crear una sola solicitud para todos los libros requeridos
            $db = \Config\Database::connect();
            
            // Preparar los datos de la solicitud, incluyendo la cantidad en el motivo_rechazo temporalmente
            // (usaremos este campo para almacenar la cantidad hasta que se procese)
            $cantidadInfo = $cantidad > 1 ? "Cantidad solicitada: $cantidad ejemplares" : null;
            
            $result = $db->table('solicitud')->insert([
                'idmatricula' => $idMatricula,
                'idusuario' => $idUsuario,
                'idrecurso' => $idRecurso,
                'fechaprestamo' => $fechaHoraPrestamo,
                'fechadevolucion' => $fechaHoraDevolucion,
                'validado' => false,
                'idprestamo' => null,  // Se asignará cuando se apruebe
                'motivo_rechazo' => $cantidadInfo  // Almacenar temporalmente la cantidad aquí
            ]);
            
            if (!$result) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo crear la solicitud. Verifique los datos e intente nuevamente.'
                ]);
            }
            
            // Registrar en historial de usuario si existe el helper
            try {
                helper('historial');
                if (function_exists('registrar_accion')) {
                    // Verificar si es un array o un objeto
                    $titulo = is_array($recurso) ? $recurso['titulo'] : $recurso->titulo;
                    if ($cantidad === 1) {
                        registrar_accion("Solicitó préstamo del recurso #$idRecurso: $titulo");
                    } else {
                        registrar_accion("Solicitó préstamo de $cantidad ejemplares del recurso #$idRecurso: $titulo");
                    }
                }
            } catch (\Exception $e) {
                // Si el helper no existe, simplemente continuar sin registrar
                log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
            }
            
            $mensajeExito = $cantidad === 1 
                ? 'Solicitud de préstamo enviada correctamente'
                : "Solicitud de préstamo enviada correctamente para $cantidad ejemplares";
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $mensajeExito,
                'data' => [
                    'cantidad_solicitada' => $cantidad,
                    'solicitud_id' => $db->insertID()
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al procesar solicitud de préstamo: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            log_message('error', 'Datos recibidos: ' . json_encode([
                'idRecurso' => $idRecurso,
                'fechaInicio' => $fechaInicio,
                'fechaEntrega' => $fechaEntrega,
                'cantidad' => $cantidad,
                'nivelAcceso' => $nivelAcceso ?? 'no definido',
                'idUsuario' => $idUsuario ?? 'no definido'
            ]));
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ha ocurrido un error al procesar su solicitud. Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Devoluciones - Redirige a historial (funcionalidad consolidada)
     */
    public function devoluciones()
    {
        // Redirigir a historial ya que las funcionalidades se consolidaron
        return redirect()->to(base_url('/historial-prestamos'));
    }

    /**
     * Historial Completo
     */
    public function historial()
    {
        try {
            $historial = $this->prestamoModel->getHistorialCompleto();
            $estadisticas = $this->prestamoModel->getEstadisticasHistorial();
            
            // Debug temporal: log información del historial
            log_message('info', 'Historial obtenido: ' . count($historial) . ' registros');
            if (!empty($historial)) {
                $primerRegistro = $historial[0];
                log_message('info', 'Primer registro - ID: ' . ($primerRegistro['id'] ?? 'N/A') . 
                           ', Observaciones: "' . ($primerRegistro['observaciones'] ?? 'NULL') . '"');
            }

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
     * Datos de prueba para devoluciones cuando hay error
     */
    private function getDatosPruebaDevoluciones()
    {
        return [];
    }

    /**
     * Datos de prueba para historial cuando hay error
     */
    private function getDatosPruebaHistorial()
    {
        return [
            [
                'id' => 1,
                'codigo_prestamo' => 'PREST-2025-001',
                'usuario' => 'Juan Pérez',
                'documento' => '12345678',
                'recurso' => 'Cálculo Diferencial',
                'codigo_ejemplar' => 'LIB-FIS-001',
                'fecha_prestamo' => '2025-10-01 08:00:00',
                'fecha_devolucion' => '2025-10-15 10:30:00',
                'fecha_vencimiento' => '2025-10-15 13:00:00',
                'cantidad' => 1,
                'estado_final' => 'Devuelto',
                'dias_prestamo' => 14,
                'dias_retraso' => 0,
                'horas_retraso_total' => 0,
                'renovaciones' => 0,
                'estado_ejemplar' => 'Bueno',
                'observaciones' => 'Libro devuelto en excelente estado, sin daños visibles.'
            ],
            [
                'id' => 2,
                'codigo_prestamo' => 'PREST-2025-002',
                'usuario' => 'María García',
                'documento' => '87654321',
                'recurso' => 'Álgebra Lineal',
                'codigo_ejemplar' => 'LIB-FIS-002',
                'fecha_prestamo' => '2025-10-05 09:00:00',
                'fecha_devolucion' => '2025-10-20 14:00:00',
                'fecha_vencimiento' => '2025-10-19 13:00:00',
                'cantidad' => 2,
                'estado_final' => 'Devuelto con retraso',
                'dias_prestamo' => 15,
                'dias_retraso' => 1,
                'horas_retraso_total' => 25,
                'renovaciones' => 1,
                'estado_ejemplar' => 'Regular',
                'observaciones' => null // Sin observaciones para probar ambos casos
            ]
        ];
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
            if ($resultado['success']) {
                try {
                    helper('historial');
                    if (function_exists('registrar_accion')) {
                        registrar_accion(
                            'Aprobación de Solicitud de Préstamo',
                            session()->get('nomuser'),
                            null,
                            session()->get('nivelacceso'),
                            "Solicitud #{$idsolicitud} aprobada exitosamente"
                        );
                    }
                } catch (\Exception $e) {
                    log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
                }
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
            if ($resultado['success']) {
                try {
                    helper('historial');
                    if (function_exists('registrar_accion')) {
                        registrar_accion(
                            'Rechazo de Solicitud de Préstamo',
                            session()->get('nomuser'),
                            null,
                            session()->get('nivelacceso'),
                            "Solicitud #{$idsolicitud} rechazada." . (!empty($motivo) ? " Motivo: {$motivo}" : " Sin motivo especificado.")
                        );
                    }
                } catch (\Exception $e) {
                    log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
                }
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
            if ($resultados['aprobadas'] > 0) {
                try {
                    helper('historial');
                    if (function_exists('registrar_accion')) {
                        registrar_accion(
                            'Aprobación Masiva de Solicitudes',
                            session()->get('nomuser'),
                            null,
                            session()->get('nivelacceso'),
                            "Aprobadas: {$resultados['aprobadas']} solicitudes. Rechazadas: {$resultados['rechazadas']}"
                        );
                    }
                } catch (\Exception $e) {
                    log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
                }
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
     * Rechazar múltiples solicitudes
     */
    public function rechazarTodas()
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

        try {
            // Obtener IDs de solicitudes y motivo
            $solicitudesParam = $this->request->getPost('solicitudes');
            $motivo = $this->request->getPost('motivo') ?? '';
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
            log_message('info', 'PrestamoController::rechazarTodas() - IDs recibidos: ' . json_encode($idsolicitudes));
            log_message('info', 'PrestamoController::rechazarTodas() - Motivo: ' . $motivo);
            
            // Validar que tenemos IDs para procesar
            if (empty($idsolicitudes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se proporcionaron IDs de solicitudes para rechazar'
                ]);
            }
            
            // Rechazar solicitudes
            $resultados = $this->prestamoModel->rechazarSolicitudesMultiples($idsolicitudes, $motivo);
            
            // Log de debug
            log_message('info', 'PrestamoController::rechazarTodas() - Resultados: ' . json_encode($resultados));
            
            // Registrar acción en historial si existe el helper
            if ($resultados['rechazadas'] > 0) {
                try {
                    helper('historial');
                    if (function_exists('registrar_accion')) {
                        $motivoTexto = !empty($motivo) ? " Motivo: {$motivo}" : ' Sin motivo especificado';
                        registrar_accion(
                            'Rechazo Masivo de Solicitudes',
                            session()->get('nomuser'),
                            null,
                            session()->get('nivelacceso'),
                            "Rechazadas: {$resultados['rechazadas']} solicitudes.{$motivoTexto}"
                        );
                    }
                } catch (\Exception $e) {
                    log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Se rechazaron {$resultados['rechazadas']} solicitudes exitosamente",
                'data' => $resultados
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::rechazarTodas(): ' . $e->getMessage());
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
     * Obtener tipos de sanción desde la base de datos
     */
    public function obtenerTiposSancion()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            $builder = $db->table('tiposancion');
            $tiposSancion = $builder->select('idtiposancion, tiposancion')
                                    ->orderBy('tiposancion', 'ASC')
                                    ->get()
                                    ->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'data' => $tiposSancion
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener tipos de sanción: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cargar los tipos de sanción',
                'data' => []
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
        $estadoDevolucion = $this->request->getPost('estado_devolucion') ?? 'bueno';
        $idtiposancion = $this->request->getPost('idtiposancion');
        $detalleIncidencia = $this->request->getPost('detalle_incidencia') ?? '';
        $observaciones = $this->request->getPost('observaciones') ?? '';
        
        if (!$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido'
            ]);
        }

        // Validar que si hay incidencia, se proporcione el tipo de sanción
        if ($estadoDevolucion === 'con_incidencia' && !$idtiposancion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe seleccionar el tipo de incidencia'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart(); // Iniciar transacción
            
            // Procesar la devolución normal
            $resultado = $this->prestamoModel->procesarDevolucion($idprestamo, $observaciones);
            
            if (!$resultado['success']) {
                $db->transRollback();
                return $this->response->setJSON($resultado);
            }
            
            // Si hay incidencia, crear sanción
            $sancionAplicada = false;
            $tipoSancionNombre = '';
            
            if ($estadoDevolucion === 'con_incidencia' && $idtiposancion) {
                // Obtener información del préstamo para la sanción (con JOIN para obtener idpersona)
                $prestamoQuery = $db->table('prestamos p')
                    ->select('m.idpersona')
                    ->join('matriculas m', 'm.idmatricula = p.idmatricula', 'inner')
                    ->where('p.idprestamo', $idprestamo)
                    ->get();
                $prestamo = $prestamoQuery ? $prestamoQuery->getRowArray() : null;


                if (!$prestamo) {
                    log_message('error', '[DEVOLUCION] No se encontró el préstamo para sanción. idprestamo recibido: ' . print_r($idprestamo, true));
                    // Mostrar los préstamos activos para depuración
                    $pruebaQuery = $db->table('prestamos')->select('idprestamo, idpersona, estado')->get();
                    if ($pruebaQuery !== false) {
                        $prueba = $pruebaQuery->getResultArray();
                        log_message('error', '[DEVOLUCION] Préstamos en BD: ' . print_r($prueba, true));
                    } else {
                        log_message('error', '[DEVOLUCION] Error al consultar préstamos: ' . $db->error());
                    }
                    $db->transRollback();
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No se encontró el préstamo para registrar la sanción. ID recibido: ' . $idprestamo
                    ]);
                }

                // Obtener nombre del tipo de sanción
                $tipoSancionQuery = $db->table('tiposancion')
                    ->select('tiposancion')
                    ->where('idtiposancion', $idtiposancion)
                    ->get();
                $tipoSancion = $tipoSancionQuery ? $tipoSancionQuery->getRowArray() : null;

                $tipoSancionNombre = $tipoSancion['tiposancion'] ?? 'Sanción';

                // Determinar duración de la sanción según el tipo
                $duracionDias = $this->calcularDuracionSancion($idtiposancion);
                $fechaInicio = date('Y-m-d');
                $fechaVencimiento = $duracionDias > 0 
                    ? date('Y-m-d', strtotime("+{$duracionDias} days"))
                    : null;

                // Crear el registro de sanción
                $dataSancion = [
                    'idtiposancion' => $idtiposancion,
                    'idpersona' => $prestamo['idpersona'],
                    'idprestamo' => $idprestamo, // NUEVA COLUMNA: Relacionar sanción con préstamo
                    'detallesancion' => $detalleIncidencia ?: $tipoSancionNombre,
                    'fecha_sancion' => date('Y-m-d'),
                    'fecha_inicio' => $fechaInicio,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'estado_sancion' => 'activa',
                    'duracion_dias' => $duracionDias,
                    'usuario_registra' => session()->get('idusuario'),
                    'observaciones' => $observaciones
                ];

                $insertado = $db->table('sanciones')->insert($dataSancion);
                $sancionAplicada = $insertado ? true : false;

                // Log para debugging
                if ($sancionAplicada) {
                    log_message('info', "Sanción aplicada - Tipo: {$tipoSancionNombre}, Usuario: {$prestamo['idpersona']}, Préstamo: {$idprestamo}");
                }
            }
            
            $db->transComplete(); // Completar transacción
            
            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al procesar la devolución'
                ]);
            }
            
            // Registrar acción en historial
            if ($resultado['success']) {
                try {
                    helper('historial');
                    if (function_exists('registrar_accion')) {
                        $detalleAccion = "Préstamo #{$idprestamo} devuelto";
                        if ($sancionAplicada) {
                            $detalleAccion .= " con incidencia: {$tipoSancionNombre}";
                        }
                        if ($observaciones) {
                            $detalleAccion .= ". Observaciones: {$observaciones}";
                        }
                        
                        registrar_accion(
                            'Devolución de Préstamo',
                            session()->get('nomuser'),
                            null,
                            session()->get('nivelacceso'),
                            $detalleAccion
                        );
                    }
                } catch (\Exception $e) {
                    log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
                }
            }
            
            // Preparar respuesta personalizada
            $resultado['sancion_aplicada'] = $sancionAplicada;
            $resultado['tipo_sancion'] = $tipoSancionNombre;
            
            if ($sancionAplicada) {
                $resultado['message'] = "Devolución procesada correctamente. Se ha aplicado una sanción por: {$tipoSancionNombre}";
            }
            
            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::procesarDevolucion(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Calcular duración de sanción según el tipo
     */
    private function calcularDuracionSancion($idtiposancion)
    {
        // Duraciones sugeridas según el tipo de sanción
        switch ($idtiposancion) {
            case 1: // Retraso en devolución
                return 7; // 7 días
            case 2: // Pérdida de material
                return 90; // 90 días (hasta reposición)
            case 3: // Daño al material
                return 30; // 30 días
            case 4: // Incumplimiento de normas
                return 15; // 15 días
            case 5: // Comportamiento inadecuado
                return 14; // 14 días
            default:
                return 7; // Por defecto 7 días
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
            if ($resultado['success']) {
                try {
                    helper('historial');
                    if (function_exists('registrar_accion')) {
                        registrar_accion(
                            'Cancelación de Préstamo',
                            session()->get('nomuser'),
                            null,
                            session()->get('nivelacceso'),
                            "Préstamo #{$idprestamo} cancelado. Motivo: {$motivo}"
                        );
                    }
                } catch (\Exception $e) {
                    log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
                }
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
     * Solicitar renovación de un préstamo (para usuarios normales)
     */
    public function solicitarRenovacion()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        // Obtener datos del formulario - Los datos vienen como JSON
        $json = $this->request->getJSON(true); // true para obtener como array
        
        // Si no viene como JSON, intentar con POST normal
        if (empty($json)) {
            $idprestamo = $this->request->getPost('idprestamo');
            $motivo = $this->request->getPost('motivo') ?? '';
            $nuevaFechaDevolucion = $this->request->getPost('nueva_fecha_devolucion');
            $nuevaFechaPrestamo = $this->request->getPost('nueva_fecha_prestamo');
        } else {
            $idprestamo = $json['idprestamo'] ?? null;
            $motivo = $json['motivo'] ?? '';
            $nuevaFechaDevolucion = $json['nueva_fecha_devolucion'] ?? null;
            $nuevaFechaPrestamo = $json['nueva_fecha_prestamo'] ?? null;
        }
        
        // Log para debugging
        log_message('info', 'Datos recibidos en solicitarRenovacion: ' . json_encode([
            'idprestamo' => $idprestamo,
            'nueva_fecha_prestamo' => $nuevaFechaPrestamo,
            'nueva_fecha_devolucion' => $nuevaFechaDevolucion,
            'motivo' => $motivo,
            'json_recibido' => $json
        ]));
        
        if (!$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido. Datos recibidos: ' . json_encode($json)
            ]);
        }
        
        if (!$nuevaFechaDevolucion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nueva fecha de devolución requerida'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            
            // Verificar que el préstamo existe y pertenece al usuario
            $prestamo = $db->table('prestamos p')
                ->select('p.*, m.idpersona')
                ->join('matriculas m', 'm.idmatricula = p.idmatricula')
                ->join('usuarios u', 'u.idpersona = m.idpersona')
                ->where('p.idprestamo', $idprestamo)
                ->where('u.idusuario', session()->get('idusuario'))
                ->where('p.fechahoraretorno IS NULL', null, false)
                ->get()
                ->getRow();

            if (!$prestamo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Préstamo no encontrado o no autorizado'
                ]);
            }

            // Verificar que no exista una solicitud de renovación pendiente
            $solicitudExistente = $db->table('solicitudes_renovacion')
                ->where('idprestamo', $idprestamo)
                ->where('estado', 'pendiente')
                ->countAllResults();

            if ($solicitudExistente > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya existe una solicitud de renovación pendiente para este préstamo'
                ]);
            }

            // Crear tabla de solicitudes de renovación si no existe
            if (!$db->tableExists('solicitudes_renovacion')) {
                $forge = \Config\Database::forge();
                $forge->addField([
                    'idsolicitud_renovacion' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'auto_increment' => true
                    ],
                    'idprestamo' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true
                    ],
                    'idusuario_solicita' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true
                    ],
                    'fecha_solicitud' => [
                        'type' => 'DATETIME',
                        'null' => false
                    ],
                    'fecha_vencimiento_actual' => [
                        'type' => 'DATETIME',
                        'null' => false
                    ],
                    'nueva_fecha_inicio' => [
                        'type' => 'DATE',
                        'null' => true
                    ],
                    'nueva_fecha_devolucion' => [
                        'type' => 'DATE',
                        'null' => false
                    ],
                    'motivo' => [
                        'type' => 'TEXT',
                        'null' => true
                    ],
                    'estado' => [
                        'type' => 'ENUM',
                        'constraint' => ['pendiente', 'aprobada', 'rechazada'],
                        'default' => 'pendiente'
                    ],
                    'idusuario_procesa' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'null' => true
                    ],
                    'fecha_procesado' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'motivo_rechazo' => [
                        'type' => 'TEXT',
                        'null' => true
                    ]
                ]);
                $forge->addKey('idsolicitud_renovacion', true);
                $forge->createTable('solicitudes_renovacion', true);
            }

            // Insertar solicitud de renovación
            $dataSolicitud = [
                'idprestamo' => $idprestamo,
                'idusuario_solicita' => session()->get('idusuario'),
                'fecha_solicitud' => date('Y-m-d H:i:s'),
                'fecha_vencimiento_actual' => $prestamo->fechadevolucion,
                'nueva_fecha_inicio' => $nuevaFechaPrestamo,
                'nueva_fecha_devolucion' => $nuevaFechaDevolucion,
                'motivo' => $motivo,
                'estado' => 'pendiente'
            ];
            
            log_message('info', 'Insertando solicitud de renovación: ' . json_encode($dataSolicitud));

            $db->table('solicitudes_renovacion')->insert($dataSolicitud);

            // Registrar en historial
            try {
                helper('historial');
                if (function_exists('registrar_accion')) {
                    registrar_accion("Solicitó renovación del préstamo #{$idprestamo} hasta {$nuevaFechaDevolucion}");
                }
            } catch (\Exception $e) {
                log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Solicitud de renovación enviada correctamente. Será revisada por un administrador.',
                'tipo' => 'solicitud'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::solicitarRenovacion(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Aprobar solicitud de renovación (Admin/Docente)
     */
    public function aprobarRenovacion()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud inválida'
            ]);
        }

        // Verificar autenticación y permisos
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
                'message' => 'No tiene permisos para aprobar renovaciones'
            ]);
        }

        // Obtener datos del JSON
        $json = $this->request->getJSON(true);
        $idsolicitudRenovacion = $json['idsolicitud_renovacion'] ?? null;
        $idprestamo = $json['idprestamo'] ?? null;

        if (!$idsolicitudRenovacion || !$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        try {
            $db = \Config\Database::connect();

            // Obtener detalles de la solicitud
            $solicitud = $db->table('solicitudes_renovacion')
                ->where('idsolicitud_renovacion', $idsolicitudRenovacion)
                ->where('estado', 'pendiente')
                ->get()
                ->getRow();

            if (!$solicitud) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Solicitud no encontrada o ya procesada'
                ]);
            }

            // Usar el método del modelo para renovar el préstamo
            $resultado = $this->prestamoModel->renovarPrestamoConFecha(
                $idprestamo,
                $solicitud->nueva_fecha_devolucion,
                $solicitud->motivo ?? '',
                $solicitud->nueva_fecha_inicio
            );

            if (!$resultado['success']) {
                return $this->response->setJSON($resultado);
            }

            // Obtener ID de usuario de sesión
            $idusuarioSesion = session()->get('idusuario');
            if (!$idusuarioSesion) {
                // Intentar obtener de otra forma
                $nombreUsuario = session()->get('nomuser');
                if ($nombreUsuario) {
                    $usuario = $db->table('usuarios')
                        ->where('nomuser', $nombreUsuario)
                        ->get()->getRow();
                    if ($usuario) {
                        $idusuarioSesion = $usuario->idusuario;
                        session()->set('idusuario', $idusuarioSesion);
                    }
                }
            }

            // Actualizar estado de la solicitud
            $db->table('solicitudes_renovacion')
                ->where('idsolicitud_renovacion', $idsolicitudRenovacion)
                ->update([
                    'estado' => 'aprobada',
                    'idusuario_procesa' => $idusuarioSesion,
                    'fecha_procesado' => date('Y-m-d H:i:s')
                ]);

            // Registrar en historial
            try {
                helper('historial');
                if (function_exists('registrar_accion')) {
                    registrar_accion(
                        'Aprobación de Renovación de Préstamo',
                        session()->get('nomuser'),
                        null,
                        session()->get('nivelacceso'),
                        "Renovación de préstamo #{$idprestamo} aprobada exitosamente"
                    );
                }
            } catch (\Exception $e) {
                log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Renovación aprobada correctamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::aprobarRenovacion(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al aprobar la renovación: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rechazar solicitud de renovación (Admin/Docente)
     */
    public function rechazarRenovacion()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud inválida'
            ]);
        }

        // Verificar autenticación y permisos
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
                'message' => 'No tiene permisos para rechazar renovaciones'
            ]);
        }

        // Obtener datos del JSON
        $json = $this->request->getJSON(true);
        $idsolicitudRenovacion = $json['idsolicitud_renovacion'] ?? null;
        $motivoRechazo = $json['motivo_rechazo'] ?? 'No especificado';

        if (!$idsolicitudRenovacion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de solicitud requerido'
            ]);
        }

        try {
            $db = \Config\Database::connect();

            // Verificar que la solicitud existe y está pendiente
            $solicitud = $db->table('solicitudes_renovacion')
                ->where('idsolicitud_renovacion', $idsolicitudRenovacion)
                ->where('estado', 'pendiente')
                ->get()
                ->getRow();

            if (!$solicitud) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Solicitud no encontrada o ya procesada'
                ]);
            }

            // Obtener ID de usuario de sesión
            $idusuarioSesion = session()->get('idusuario');
            if (!$idusuarioSesion) {
                // Intentar obtener de otra forma
                $nombreUsuario = session()->get('nomuser');
                if ($nombreUsuario) {
                    $usuario = $db->table('usuarios')
                        ->where('nomuser', $nombreUsuario)
                        ->get()->getRow();
                    if ($usuario) {
                        $idusuarioSesion = $usuario->idusuario;
                        session()->set('idusuario', $idusuarioSesion);
                    }
                }
            }

            // Actualizar estado de la solicitud
            $db->table('solicitudes_renovacion')
                ->where('idsolicitud_renovacion', $idsolicitudRenovacion)
                ->update([
                    'estado' => 'rechazada',
                    'idusuario_procesa' => $idusuarioSesion,
                    'fecha_procesado' => date('Y-m-d H:i:s'),
                    'motivo_rechazo' => $motivoRechazo
                ]);

            // Registrar en historial
            try {
                helper('historial');
                if (function_exists('registrar_accion')) {
                    registrar_accion(
                        'Rechazo de Renovación de Préstamo',
                        session()->get('nomuser'),
                        null,
                        session()->get('nivelacceso'),
                        "Renovación de préstamo #{$solicitud->idprestamo} rechazada"
                    );
                }
            } catch (\Exception $e) {
                log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Solicitud de renovación rechazada'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::rechazarRenovacion(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al rechazar la renovación'
            ]);
        }
    }

    /**
     * Renovar un préstamo activo (solo admin/docente)
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
        $nuevaFechaPrestamo = $this->request->getPost('nueva_fecha_prestamo');
        
        // Log para debugging
        log_message('info', 'Datos recibidos para renovación: ' . json_encode([
            'idprestamo' => $idprestamo,
            'nueva_fecha_prestamo' => $nuevaFechaPrestamo,
            'nueva_fecha_devolucion' => $nuevaFechaDevolucion,
            'motivo' => $motivo
        ]));
        
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

        // Validar que las fechas sean válidas
        try {
            $fechaDevolucion = new \DateTime($nuevaFechaDevolucion);
            $hoy = new \DateTime();
            
            // Si se proporciona fecha de préstamo, validarla también
            if ($nuevaFechaPrestamo) {
                $fechaPrestamo = new \DateTime($nuevaFechaPrestamo);
                
                // Validar que fecha de devolución sea posterior a fecha de préstamo
                if ($fechaDevolucion <= $fechaPrestamo) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'La fecha de devolución debe ser posterior a la fecha de inicio'
                    ]);
                }
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Fechas inválidas: ' . $e->getMessage()
            ]);
        }

        try {
            $resultado = $this->prestamoModel->renovarPrestamoConFecha($idprestamo, $nuevaFechaDevolucion, $motivo, $nuevaFechaPrestamo);
            
            // Log del resultado
            log_message('info', 'Resultado de renovación: ' . json_encode($resultado));
            
            // Registrar acción en historial si existe el helper
            if ($resultado['success']) {
                try {
                    helper('historial');
                    if (function_exists('registrar_accion')) {
                        registrar_accion(
                            'Renovación de Préstamo',
                            session()->get('nomuser'),
                            null,
                            session()->get('nivelacceso'),
                            "Préstamo #{$idprestamo} renovado. Nueva fecha inicio: {$nuevaFechaPrestamo}, Nueva fecha fin: {$nuevaFechaDevolucion}. Motivo: {$motivo}"
                        );
                    }
                } catch (\Exception $e) {
                    log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
                }
            }
            
            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::renovarPrestamo(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mostrar formulario de renovación de préstamo
     */
    public function formularioRenovacion($idprestamo = null)
    {
        // Verificar si el usuario está autenticado
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(403)->setBody('No autorizado');
        }

        if (!$idprestamo) {
            return $this->response->setStatusCode(400)->setBody('ID de préstamo requerido');
        }

        try {
            // Cargar el modelo de recursos
            $recursoModel = new \App\Models\RecursoModel();
            
            // Obtener detalles del préstamo con información del usuario que tiene el préstamo
            $prestamo = $this->prestamoModel
                ->select('prestamos.*, usuarios.idusuario as usuario_prestamo')
                ->join('matriculas', 'matriculas.idmatricula = prestamos.idmatricula', 'left')
                ->join('usuarios', 'usuarios.idpersona = matriculas.idpersona', 'left')
                ->where('prestamos.idprestamo', $idprestamo)
                ->first();

            if (!$prestamo) {
                return $this->response->setStatusCode(404)->setBody('Préstamo no encontrado');
            }

            // Obtener información del recurso
            $recurso = $recursoModel
                ->select('recursos.idrecurso, recursos.titulo, recursos.isbn, recursos_fisicos.portada')
                ->join('recursos_fisicos', 'recursos_fisicos.idrecurso = recursos.idrecurso', 'left')
                ->where('recursos.idrecurso', $prestamo['idrecurso'])
                ->first();

            // Combinar información del préstamo y recurso
            if ($recurso) {
                $prestamo['titulo'] = $recurso['titulo'];
                $prestamo['isbn'] = $recurso['isbn'] ?? '';
                $prestamo['portada'] = $recurso['portada'] ?? '';
            }

            // Verificar que el préstamo pertenece al usuario (excepto admin)
            $nivelAcceso = session()->get('nivelacceso');
            if ($nivelAcceso !== 'admin' && $prestamo['usuario_prestamo'] != session()->get('idusuario')) {
                return $this->response->setStatusCode(403)->setBody('No tiene permisos para renovar este préstamo');
            }

            // Verificar que el préstamo está activo
            if (!empty($prestamo['fechahoraretorno'])) {
                return $this->response->setStatusCode(400)->setBody('El préstamo ya ha sido devuelto');
            }

            // Cargar la vista del formulario de renovación
            $data = [
                'prestamo' => $prestamo
            ];

            return view('prestamos/formulario_renovacion', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::formularioRenovacion(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setBody('Error al cargar el formulario: ' . $e->getMessage());
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

    /**
     * Crear un nuevo préstamo
     */
    public function crearPrestamo()
    {
        // Verificar que sea una petición AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            
            // Obtener datos del formulario
            $idusuario = $this->request->getPost('idusuario');
            $idrecurso = $this->request->getPost('idejemplar'); // El frontend envía como idejemplar
            $fechaPrestamo = $this->request->getPost('fechaPrestamo');
            $horaInicio = $this->request->getPost('horaInicio');
            $horaFin = $this->request->getPost('horaFin');
            $observaciones = $this->request->getPost('observaciones');

            // Validaciones
            if (empty($idusuario) || empty($idrecurso) || empty($fechaPrestamo)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Faltan datos requeridos (usuario, recurso o fecha de préstamo)'
                ]);
            }
            
            if (empty($horaInicio) || empty($horaFin)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Faltan las horas de inicio y fin del préstamo'
                ]);
            }

            // Obtener información del usuario
            $usuario = $db->table('usuarios u')
                ->select('u.idusuario, u.nivelacceso, u.idpersona')
                ->where('u.idusuario', $idusuario)
                ->get()->getRow();

            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }

            // Obtener idmatricula del usuario
            $idmatricula = null;
            if ($usuario->nivelacceso === 'estudiante') {
                $idmatricula = $this->prestamoModel->getMatriculaByUsuario($usuario->idusuario);
                if (!$idmatricula) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No se encontró matrícula activa para el estudiante'
                    ]);
                }
            } else {
                // Para admin y docente, buscar o crear matrícula básica
                $matricula = $db->table('matriculas')
                    ->where('idpersona', $usuario->idpersona)
                    ->where('estadomatricula', true)
                    ->get()->getRow();

                if (!$matricula) {
                    // Si no existe matrícula, crear una básica (requiere un grupo por defecto)
                    $grupoDefault = $db->table('grupos')->orderBy('idgrupo', 'ASC')->get()->getRow();
                    if (!$grupoDefault) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'No hay grupos disponibles en el sistema. Contacte al administrador.'
                        ]);
                    }

                    $db->table('matriculas')->insert([
                        'idgrupo' => $grupoDefault->idgrupo,
                        'idpersona' => $usuario->idpersona,
                        'fechamatricula' => date('Y-m-d'),
                        'estadomatricula' => true
                    ]);
                    $idmatricula = $db->insertID();
                } else {
                    $idmatricula = $matricula->idmatricula;
                }
            }

            // Verificar disponibilidad del recurso
            $recurso = $db->table('recursos')
                ->where('idrecurso', $idrecurso)
                ->get()->getRow();

            if (!$recurso) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El recurso no existe'
                ]);
            }

            if ($recurso->estado !== 'disponible' || $recurso->stock <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El recurso no está disponible para préstamo'
                ]);
            }

            // Preparar fechas con hora de inicio y fin
            $fechaPrestamoCompleta = $fechaPrestamo . ' ' . $horaInicio . ':00';
            $fechaDevolucionCompleta = $fechaPrestamo . ' ' . $horaFin . ':00';

            // Crear el préstamo
            $prestamo = [
                'idmatricula' => $idmatricula,
                'idusuario' => $idusuario, // Usuario que recibe el préstamo
                'idrecurso' => $idrecurso,
                'fechaprestamo' => $fechaPrestamoCompleta,
                'fechadevolucion' => $fechaDevolucionCompleta,
                'fechahoravalidacion' => date('Y-m-d H:i:s') // Validado inmediatamente por admin
            ];

            $this->prestamoModel->insert($prestamo);
            $idPrestamo = $this->prestamoModel->insertID();

            // Actualizar stock del recurso
            $db->table('recursos')
                ->where('idrecurso', $idrecurso)
                ->set('stock', 'stock - 1', false)
                ->update();

            // Si el stock llega a 0, cambiar estado a no disponible
            $recursoActualizado = $db->table('recursos')
                ->where('idrecurso', $idrecurso)
                ->get()->getRow();

            if ($recursoActualizado->stock <= 0) {
                $db->table('recursos')
                    ->where('idrecurso', $idrecurso)
                    ->update(['estado' => 'no disponible']);
            }

            // Registrar en historial si existe el helper
            try {
                helper('historial');
                if (function_exists('registrar_accion')) {
                    registrar_accion("Creó préstamo #$idPrestamo del recurso: {$recurso->titulo}");
                }
            } catch (\Exception $e) {
                log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
            }

            // Obtener información del usuario que recibe el préstamo
            $personaUsuario = $db->table('personas per')
                ->select('per.nombres, per.apellidos')
                ->where('per.idpersona', $usuario->idpersona)
                ->get()->getRow();

            $nombreCompleto = $personaUsuario ? trim($personaUsuario->nombres . ' ' . $personaUsuario->apellidos) : 'Usuario desconocido';

            // Formatear fecha de devolución
            $fechaDevFormateada = date('d/m/Y H:i', strtotime($fechaDevolucionCompleta));

            // Generar código de préstamo
            $codigoPrestamo = 'PREST-' . str_pad($idPrestamo, 6, '0', STR_PAD_LEFT);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Préstamo creado exitosamente',
                'idprestamo' => $idPrestamo,
                'codigo_prestamo' => $codigoPrestamo,
                'fecha_devolucion' => $fechaDevFormateada,
                'usuario' => $nombreCompleto
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::crearPrestamo(): ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear el préstamo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Procesar devolución con estado del recurso y generación de sanciones
     */
    public function procesarDevolucionCompleta()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar autenticación y permisos
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
        $estadoRecurso = $this->request->getPost('estado_recurso') ?? 'bueno';
        $observaciones = $this->request->getPost('observaciones') ?? '';
        
        if (!$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido'
            ]);
        }

        try {
            $resultado = $this->prestamoModel->procesarDevolucionCompleta($idprestamo, $estadoRecurso, $observaciones);
            
            // Registrar acción en historial
            if ($resultado['success']) {
                try {
                    helper('historial');
                    if (function_exists('registrar_accion')) {
                        $detalle = "Préstamo #{$idprestamo} devuelto. Estado: {$estadoRecurso}";
                        if ($resultado['con_retraso']) {
                            $detalle .= ". Retraso: {$resultado['dias_retraso']} días. Multa: $" . number_format($resultado['multa']);
                        }
                        registrar_accion($detalle);
                    }
                } catch (\Exception $e) {
                    log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
                }
            }
            
            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::procesarDevolucionCompleta(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener detalle completo de una devolución
     */
    public function obtenerDetalleDevolucion()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        // Obtener ID del préstamo
        $idprestamo = $this->request->getPost('idprestamo') ?? $this->request->getGet('idprestamo');
        
        if (!$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido'
            ]);
        }

        try {
            $detalle = $this->prestamoModel->getDetalleDevolucion($idprestamo);
            
            if (!$detalle) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Devolución no encontrada'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $detalle
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::obtenerDetalleDevolucion(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Buscar préstamo por código para procesar devolución
     */
    public function buscarPrestamoPorCodigo()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $codigo = $this->request->getPost('codigo');
        
        if (empty($codigo)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe proporcionar un código de préstamo'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            
            // Buscar préstamo activo por código aproximado
            $sql = "SELECT 
                        p.idprestamo,
                        CONCAT('PREST-', LPAD(p.idprestamo, 6, '0')) as codigo,
                        CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                        per.numerodoc as documento,
                        r.titulo as recurso,
                        r.isbn,
                        p.fechaprestamo,
                        p.fechadevolucion,
                        DATEDIFF(NOW(), p.fechadevolucion) as dias_diferencia
                    FROM prestamos p
                    JOIN matriculas m ON m.idmatricula = p.idmatricula
                    JOIN personas per ON per.idpersona = m.idpersona
                    JOIN recursos r ON r.idrecurso = p.idrecurso
                    WHERE p.fechahoraretorno IS NULL
                    AND (p.idprestamo = ? OR CONCAT('PREST-', LPAD(p.idprestamo, 6, '0')) = ?)
                    LIMIT 1";
            
            // Extraer número del código si viene en formato PREST-XXXXXX
            $numero = preg_replace('/[^0-9]/', '', $codigo);
            
            $query = $db->query($sql, [$numero, $codigo]);
            $prestamo = $query->getRowArray();
            
            if (!$prestamo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se encontró un préstamo activo con ese código'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $prestamo
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::buscarPrestamoPorCodigo(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al buscar el préstamo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener observaciones de un préstamo desde los logs
     */
    public function obtenerObservaciones()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        // Obtener ID del préstamo
        $idprestamo = $this->request->getPost('idprestamo') ?? $this->request->getGet('idprestamo');
        
        if (!$idprestamo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de préstamo requerido'
            ]);
        }

        try {
            $observaciones = $this->prestamoModel->obtenerObservacionesDesdeLog($idprestamo);
            
            if (empty($observaciones)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se encontraron observaciones para este préstamo'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'idprestamo' => $idprestamo,
                    'observaciones' => $observaciones
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::obtenerObservaciones(): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener las observaciones: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar un registro del historial
     */
    public function eliminarHistorial()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar autenticación y permisos
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
                'message' => 'No tiene permisos para eliminar registros del historial'
            ]);
        }

        // Obtener datos
        $id = $this->request->getPost('id');
        $tipo = $this->request->getPost('tipo'); // 'prestamo' o 'solicitud'
        
        // Log para debugging
        log_message('info', 'PrestamoController::eliminarHistorial - ID: ' . $id . ', Tipo: ' . $tipo);
        
        if (!$id || !$tipo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            
            if ($tipo === 'solicitud') {
                // Eliminar solicitud rechazada
                $resultado = $db->table('solicitud')
                    ->where('idsolicitud', $id)
                    ->where('validado', true)
                    ->where('idprestamo IS NULL', null, false)
                    ->delete();
                
                if (!$resultado) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No se encontró la solicitud o no puede ser eliminada'
                    ]);
                }
                
                $mensaje = 'Solicitud rechazada eliminada del historial';
                $tipoRegistro = 'Solicitud';
                
            } else {
                // Eliminar préstamo devuelto
                // Verificar que el préstamo esté devuelto
                $prestamo = $db->table('prestamos')
                    ->where('idprestamo', $id)
                    ->get()
                    ->getRowArray();
                
                // Log para debugging
                log_message('info', 'PrestamoController::eliminarHistorial - Préstamo encontrado: ' . json_encode($prestamo));
                
                if (!$prestamo) {
                    log_message('error', 'PrestamoController::eliminarHistorial - Préstamo no encontrado con ID: ' . $id);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Préstamo no encontrado con ID: ' . $id
                    ]);
                }
                
                // Verificar si el préstamo está validado (aprobado)
                if ($prestamo['fechahoravalidacion'] === null || $prestamo['fechahoravalidacion'] === '') {
                    log_message('warning', 'PrestamoController::eliminarHistorial - Intento de eliminar préstamo no validado. ID: ' . $id . ', fechahoravalidacion: ' . ($prestamo['fechahoravalidacion'] ?? 'NULL'));
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No se puede eliminar un préstamo que no ha sido validado. Solo se pueden eliminar préstamos aprobados.'
                    ]);
                }
                
                // Usar transacción para eliminar todo relacionado
                $db->transStart();
                
                log_message('info', 'PrestamoController::eliminarHistorial - Iniciando transacción para préstamo ID: ' . $id);
                
                // Si el préstamo está activo (no devuelto), restaurar el stock del recurso
                if ($prestamo['fechahoraretorno'] === null || $prestamo['fechahoraretorno'] === '') {
                    log_message('info', 'PrestamoController::eliminarHistorial - Restaurando stock para recurso ID: ' . $prestamo['idrecurso']);
                    
                    // Restaurar stock del recurso
                    $resultadoStock = $db->table('recursos')
                        ->where('idrecurso', $prestamo['idrecurso'])
                        ->set('stock', 'stock + 1', false)
                        ->update();
                    
                    log_message('info', 'PrestamoController::eliminarHistorial - Resultado actualización stock: ' . ($resultadoStock ? 'OK' : 'ERROR'));
                    
                    // Si el stock se restaura, cambiar estado a disponible
                    $recursoActualizado = $db->table('recursos')
                        ->where('idrecurso', $prestamo['idrecurso'])
                        ->get()->getRow();
                    
                    if ($recursoActualizado && $recursoActualizado->stock > 0) {
                        $resultadoEstado = $db->table('recursos')
                            ->where('idrecurso', $prestamo['idrecurso'])
                            ->update(['estado' => 'disponible']);
                        
                        log_message('info', 'PrestamoController::eliminarHistorial - Resultado actualización estado: ' . ($resultadoEstado ? 'OK' : 'ERROR'));
                    }
                }
                
                // 1. Marcar solicitudes vinculadas como "préstamo eliminado del historial"
                //    Esto las distingue de las solicitudes realmente rechazadas
                log_message('info', 'PrestamoController::eliminarHistorial - Actualizando solicitudes vinculadas');
                $resultadoSolicitudes = $db->table('solicitud')
                    ->where('idprestamo', $id)
                    ->update([
                        'idprestamo' => null,
                        'motivo_rechazo' => 'PRESTAMO_ELIMINADO_HISTORIAL: El préstamo asociado fue eliminado del historial por un administrador.'
                    ]);
                log_message('info', 'PrestamoController::eliminarHistorial - Resultado actualización solicitudes: ' . ($resultadoSolicitudes ? 'OK' : 'ERROR'));
                
                // 2. Desvincular sanciones del préstamo (poner idprestamo en NULL)
                //    Las sanciones se mantienen en el historial del usuario
                log_message('info', 'PrestamoController::eliminarHistorial - Desvinculando sanciones');
                $resultadoSanciones = $db->table('sanciones')
                    ->where('idprestamo', $id)
                    ->update(['idprestamo' => null]);
                log_message('info', 'PrestamoController::eliminarHistorial - Resultado desvinculación sanciones: ' . ($resultadoSanciones ? 'OK' : 'ERROR'));
                
                // 3. Eliminar renovaciones relacionadas (son solo registros administrativos del préstamo)
                log_message('info', 'PrestamoController::eliminarHistorial - Eliminando renovaciones');
                $resultadoRenovaciones = $db->table('renovaciones_prestamo')
                    ->where('idprestamo', $id)
                    ->delete();
                log_message('info', 'PrestamoController::eliminarHistorial - Resultado eliminación renovaciones: ' . ($resultadoRenovaciones ? 'OK' : 'ERROR'));
                
                // 4. Eliminar notificaciones relacionadas (tienen clave foránea)
                log_message('info', 'PrestamoController::eliminarHistorial - Eliminando notificaciones');
                $resultadoNotificaciones = $db->table('notificaciones')
                    ->where('idprestamo', $id)
                    ->delete();
                log_message('info', 'PrestamoController::eliminarHistorial - Resultado eliminación notificaciones: ' . ($resultadoNotificaciones ? 'OK' : 'ERROR'));
                
                // 5. Finalmente, eliminar el préstamo del historial
                log_message('info', 'PrestamoController::eliminarHistorial - Eliminando préstamo');
                try {
                    // Intentar eliminación directa primero
                    $resultadoPrestamo = $db->table('prestamos')
                        ->where('idprestamo', $id)
                        ->delete();
                    log_message('info', 'PrestamoController::eliminarHistorial - Resultado eliminación préstamo: ' . ($resultadoPrestamo ? 'OK' : 'ERROR'));
                } catch (\Exception $e) {
                    log_message('error', 'PrestamoController::eliminarHistorial - Error específico al eliminar préstamo: ' . $e->getMessage());
                    
                    // Si falla la eliminación directa, intentar eliminación lógica
                    log_message('info', 'PrestamoController::eliminarHistorial - Intentando eliminación lógica');
                    try {
                        // Agregar un campo para marcar como eliminado (si no existe, crear uno temporal)
                        $resultadoPrestamo = $db->table('prestamos')
                            ->where('idprestamo', $id)
                            ->update(['observaciones_devolucion' => 'ELIMINADO_DEL_HISTORIAL: ' . date('Y-m-d H:i:s')]);
                        log_message('info', 'PrestamoController::eliminarHistorial - Resultado eliminación lógica: ' . ($resultadoPrestamo ? 'OK' : 'ERROR'));
                    } catch (\Exception $e2) {
                        log_message('error', 'PrestamoController::eliminarHistorial - Error en eliminación lógica: ' . $e2->getMessage());
                        throw $e; // Lanzar el error original
                    }
                }
                
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    $error = $db->error();
                    log_message('error', 'PrestamoController::eliminarHistorial - Error en transacción: ' . json_encode($error));
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al eliminar el préstamo: ' . ($error['message'] ?? 'Error desconocido')
                    ]);
                }
                
                log_message('info', 'PrestamoController::eliminarHistorial - Transacción completada exitosamente para préstamo ID: ' . $id);
                
                $mensaje = 'Préstamo eliminado del historial';
                $tipoRegistro = 'Préstamo';
            }
            
            // Registrar acción en historial
            try {
                helper('historial');
                if (function_exists('registrar_accion')) {
                    registrar_accion(
                        'Eliminación de Registro del Historial',
                        session()->get('nomuser'),
                        null,
                        session()->get('nivelacceso'),
                        "{$tipoRegistro} #{$id} eliminado del historial"
                    );
                }
            } catch (\Exception $e) {
                log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $mensaje
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::eliminarHistorial(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el registro: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar todo el historial de préstamos (solo devueltos y solicitudes rechazadas)
     */
    public function eliminarTodoHistorial()
    {
        // Verificar si es una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        // Verificar autenticación y permisos (solo admin)
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $nivelAcceso = session()->get('nivelacceso');
        if ($nivelAcceso !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo los administradores pueden eliminar todo el historial'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            // Contar registros antes de eliminar
            $countPrestamos = $db->table('prestamos')
                ->where('fechahoraretorno IS NOT NULL', null, false)
                ->countAllResults();
            
            $countSolicitudes = $db->table('solicitud')
                ->where('validado', true)
                ->where('idprestamo IS NULL', null, false)
                ->countAllResults();
            
            $countRenovaciones = $db->table('renovaciones_prestamo')->countAllResults();
            
            log_message('info', "Iniciando eliminación completa del historial - Admin: " . session()->get('nomuser'));
            log_message('info', "Registros a eliminar - Préstamos: {$countPrestamos}, Solicitudes: {$countSolicitudes}, Renovaciones: {$countRenovaciones}");
            
            // 1. Marcar todas las solicitudes de préstamos devueltos como "eliminados del historial"
            $db->table('solicitud')
                ->set([
                    'idprestamo' => null,
                    'motivo_rechazo' => 'PRESTAMO_ELIMINADO_HISTORIAL: Préstamo eliminado durante limpieza masiva del historial por administrador.'
                ])
                ->where('idprestamo IN (SELECT idprestamo FROM prestamos WHERE fechahoraretorno IS NOT NULL)', null, false)
                ->update();
            
            // 2. Desvincular todas las sanciones de préstamos devueltos (mantener las sanciones)
            $db->table('sanciones')
                ->set('idprestamo', null)
                ->where('idprestamo IN (SELECT idprestamo FROM prestamos WHERE fechahoraretorno IS NOT NULL)', null, false)
                ->update();
            
            // 3. Eliminar todas las renovaciones (son registros administrativos)
            $db->table('renovaciones_prestamo')->truncate();
            
            // 4. Eliminar solo las solicitudes realmente rechazadas (no las que son préstamos eliminados)
            $db->table('solicitud')
                ->where('validado', true)
                ->where('idprestamo IS NULL', null, false)
                ->where('motivo_rechazo IS NOT NULL', null, false)
                ->where('motivo_rechazo NOT LIKE', 'PRESTAMO_ELIMINADO_HISTORIAL:%')
                ->delete();
            
            // 5. Eliminar todos los préstamos devueltos
            $db->table('prestamos')
                ->where('fechahoraretorno IS NOT NULL', null, false)
                ->delete();
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de eliminación masiva');
            }
            
            // Registrar acción en historial
            try {
                helper('historial');
                if (function_exists('registrar_accion')) {
                    registrar_accion(
                        'Eliminación Completa del Historial',
                        session()->get('nomuser'),
                        null,
                        session()->get('nivelacceso'),
                        "Historial completo eliminado - Préstamos: {$countPrestamos}, Solicitudes: {$countSolicitudes}, Renovaciones: {$countRenovaciones}"
                    );
                }
            } catch (\Exception $e) {
                log_message('debug', 'Helper historial no disponible: ' . $e->getMessage());
            }
            
            log_message('info', "Historial completo eliminado exitosamente por: " . session()->get('nomuser'));
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'El historial completo ha sido eliminado exitosamente',
                'detalles' => [
                    'prestamos' => $countPrestamos,
                    'solicitudes' => $countSolicitudes,
                    'renovaciones' => $countRenovaciones
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::eliminarTodoHistorial(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el historial completo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener detalles de un préstamo para mostrar en modal
     */
    public function detalles($idprestamo)
    {
        try {
            log_message('info', "Solicitando detalles del préstamo ID: {$idprestamo}");
            
            $prestamo = $this->prestamoModel->getDetallePrestamo($idprestamo);
            
            if (!$prestamo) {
                log_message('warning', "No se encontró el préstamo con ID: {$idprestamo}");
                return view('partials/prestamo_detalles_error', [
                    'mensaje' => 'No se encontró el préstamo solicitado'
                ]);
            }

            log_message('info', "Detalles del préstamo {$idprestamo} cargados correctamente");
            return view('partials/prestamo_detalles', ['prestamo' => $prestamo]);
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::detalles(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return view('partials/prestamo_detalles_error', [
                'mensaje' => 'Error al cargar los detalles: ' . $e->getMessage()
            ]);
        }
    }
}




