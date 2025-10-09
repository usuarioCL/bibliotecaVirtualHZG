<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PrestamoModel;


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
            // En caso de error, mostrar datos de fallback
            log_message('error', 'Error en PrestamoController::index(): ' . $e->getMessage());
            
            $data = [
                'title' => 'Préstamos Activos',
                'prestamos' => $this->getDatosPruebaPrestamos(),
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
        try {
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
            
            $data = [
                'title' => 'Solicitudes Pendientes',
                'solicitudes' => $this->getDatosPruebaSolicitudes(),
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
        $fechaInicio = $this->request->getPost('fechaInicio');
        $fechaDevolucion = $this->request->getPost('fechaDevolucion');
        $motivo = $this->request->getPost('motivo');
        $otroMotivo = $this->request->getPost('otroMotivo');
        $observaciones = $this->request->getPost('observaciones');
        
        // Validar datos obligatorios
        if (!$idRecurso || !$fechaInicio || !$fechaDevolucion || !$motivo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Todos los campos obligatorios deben ser completados'
            ]);
        }
        
        // Si el motivo es "Otro", usar el motivo especificado
        if ($motivo === 'Otro' && !empty($otroMotivo)) {
            $motivo = $otroMotivo;
        }
        
        try {
            // Obtener ID del usuario
            $idUsuario = session()->get('id');
            
            // Obtener matrícula del usuario
            $prestamoModel = new PrestamoModel();
            $idMatricula = $prestamoModel->getMatriculaByUsuario($idUsuario);
            
            if (!$idMatricula) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se encontró matrícula asociada a su usuario'
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
                'fechaprestamo' => $fechaInicio . ' 00:00:00',
                'fechadevolucion' => $fechaDevolucion . ' 23:59:59'
                // Las observaciones se añadirán después con la actualización
            ];
            
            // Insertar el préstamo
            $prestamoModel->insert($prestamo);
            $idPrestamo = $prestamoModel->insertID();
            
            // Crear registro en tabla de solicitudes
            $db = \Config\Database::connect();
            $db->table('solicitud')->insert([
                'validado' => false,
                'idprestamo' => $idPrestamo
            ]);
            
            // Verificamos si la tabla prestamos tiene una columna de observaciones
            // Si no existe, alteramos la consulta para adaptar
            $db = \Config\Database::connect();
            $tablesFields = $db->getFieldData('prestamos');
            $hasObservacionesField = false;
            
            foreach ($tablesFields as $field) {
                if ($field->name === 'observaciones') {
                    $hasObservacionesField = true;
                    break;
                }
            }
            
            if ($hasObservacionesField) {
                // Si existe la columna observaciones, la actualizamos
                $db->table('prestamos')
                    ->where('idprestamo', $idPrestamo)
                    ->update(['observaciones' => "Motivo: $motivo. " . ($observaciones ? "Observaciones: $observaciones" : "")]);
            } else {
                // Si no existe, guardamos la información en una tabla temporal o log
                log_message('info', "Préstamo #$idPrestamo - Motivo: $motivo. Observaciones: $observaciones");
            }
            
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
     * Datos de prueba para préstamos
     */
    protected function getDatosPruebaPrestamos()
    {
        return [
            [
                'idprestamo' => 1,
                'codigo_prestamo' => 'PREST-2023-001',
                'usuario' => 'Juan Pérez',
                'documento' => '12345678',
                'recurso' => 'Historia del Perú',
                'codigo_recurso' => 'LIB-FIS-001',
                'fecha_prestamo' => '2023-06-01',
                'fecha_devolucion' => '2023-06-15',
                'dias_restantes' => -30,
                'estado' => 'Vencido'
            ],
            [
                'idprestamo' => 2,
                'codigo_prestamo' => 'PREST-2023-002',
                'usuario' => 'María García',
                'documento' => '87654321',
                'recurso' => 'Matemáticas 5',
                'codigo_recurso' => 'LIB-FIS-002',
                'fecha_prestamo' => date('Y-m-d'),
                'fecha_devolucion' => date('Y-m-d', strtotime('+7 days')),
                'dias_restantes' => 7,
                'estado' => 'Activo'
            ]
        ];
    }

    /**
     * Datos de prueba para solicitudes
     */
    protected function getDatosPruebaSolicitudes()
    {
        return [
            [
                'idsolicitud' => 1,
                'idprestamo' => 3,
                'usuario' => 'Pedro López',
                'documento' => '45678912',
                'recurso' => 'Ciencias Naturales',
                'codigo_recurso' => 'LIB-FIS-003',
                'fecha_solicitud' => date('Y-m-d H:i:s'),
                'estado' => 'Pendiente'
            ],
            [
                'idsolicitud' => 2,
                'idprestamo' => 4,
                'usuario' => 'Ana Torres',
                'documento' => '78912345',
                'recurso' => 'Historia Universal',
                'codigo_recurso' => 'LIB-FIS-004',
                'fecha_solicitud' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'estado' => 'Pendiente'
            ]
        ];
    }
    
    /**
     * Datos de prueba para devoluciones
     */
    protected function getDatosPruebaDevoluciones()
    {
        return [
            [
                'idprestamo' => 5,
                'codigo_prestamo' => 'PREST-2023-005',
                'usuario' => 'Lucía Mendoza',
                'documento' => '32165498',
                'recurso' => 'Comunicación 3',
                'codigo_recurso' => 'LIB-FIS-005',
                'fecha_prestamo' => date('Y-m-d', strtotime('-14 days')),
                'fecha_devolucion' => date('Y-m-d'),
                'estado' => 'Devuelto',
                'retraso' => 'No',
                'multa' => 'No aplica'
            ],
            [
                'idprestamo' => 6,
                'codigo_prestamo' => 'PREST-2023-006',
                'usuario' => 'Carlos Ruiz',
                'documento' => '65498732',
                'recurso' => 'Arte y Cultura',
                'codigo_recurso' => 'LIB-FIS-006',
                'fecha_prestamo' => date('Y-m-d', strtotime('-30 days')),
                'fecha_devolucion' => date('Y-m-d'),
                'estado' => 'Devuelto con retraso',
                'retraso' => 'Sí (10 días)',
                'multa' => 'S/. 10.00'
            ]
        ];
    }
    
    /**
     * Datos de prueba para historial
     */
    protected function getDatosPruebaHistorial()
    {
        return [
            [
                'idprestamo' => 7,
                'codigo_prestamo' => 'PREST-2023-007',
                'usuario' => 'Diana Castro',
                'documento' => '78945612',
                'recurso' => 'Física Moderna',
                'codigo_recurso' => 'LIB-FIS-007',
                'fecha_prestamo' => '2023-05-10',
                'fecha_devolucion' => '2023-05-24',
                'fecha_retorno' => '2023-05-23',
                'estado' => 'Devuelto a tiempo'
            ],
            [
                'idprestamo' => 8,
                'codigo_prestamo' => 'PREST-2023-008',
                'usuario' => 'Roberto Díaz',
                'documento' => '15975346',
                'recurso' => 'Química Básica',
                'codigo_recurso' => 'LIB-FIS-008',
                'fecha_prestamo' => '2023-04-05',
                'fecha_devolucion' => '2023-04-19',
                'fecha_retorno' => '2023-04-25',
                'estado' => 'Devuelto con retraso'
            ],
            [
                'idprestamo' => 9,
                'codigo_prestamo' => 'PREST-2023-009',
                'usuario' => 'Mónica Álvarez',
                'documento' => '36925814',
                'recurso' => 'Geografía Mundial',
                'codigo_recurso' => 'LIB-FIS-009',
                'fecha_prestamo' => '2023-03-15',
                'fecha_devolucion' => '2023-03-29',
                'fecha_retorno' => null,
                'estado' => 'No devuelto (perdido)'
            ]
        ];
    }
}
