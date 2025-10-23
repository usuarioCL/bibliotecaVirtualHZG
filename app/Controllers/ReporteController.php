<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Controlador de Reportes y Estadísticas
 * 
 * Este controlador maneja todas las vistas y funcionalidades relacionadas
 * con reportes, estadísticas y análisis de datos del sistema de biblioteca.
 */
class ReporteController extends BaseController
{
    protected $prestamoModel;
    protected $recursoModel;
    protected $usuarioModel;

    /**
     * Inicialización del controlador
     */
    public function __construct()
    {
        // Inicializar modelos necesarios
        try {
            $this->prestamoModel = new \App\Models\PrestamoModel();
            $this->recursoModel = new \App\Models\RecursoModel();
            $this->usuarioModel = new \App\Models\usuarioModel();
        } catch (\Exception $e) {
            log_message('error', 'Error inicializando modelos: ' . $e->getMessage());
        }
    }

    /**
     * Reporte de Préstamos por Usuario
     * Muestra estadísticas detalladas de préstamos agrupados por usuario
     * 
     * @return string|\CodeIgniter\HTTP\ResponseInterface
     */
    public function prestamosUsuarios()
    {
        // Datos básicos para evitar errores
        $data = [
            'title' => 'Reporte - Préstamos por Usuario | Sistema Biblioteca',
            'estadisticas' => [
                'total_usuarios' => 0,
                'total_prestamos' => 0,
                'prestamos_pendientes' => 0,
                'prestamos_vencidos' => 0,
                'promedio_mensual' => 0,
                'crecimiento_mensual' => '0%'
            ],
            'top_usuarios' => [],
            'usuarios_prestamos' => [],
            'tendencias_mensuales' => [],
            'filtros' => [
                'niveles' => ['Inicial', 'Primaria', 'Secundaria'],
                'grados' => ['1', '2', '3', '4', '5', '6']
            ]
        ];

        // Intentar obtener datos reales si es posible
        try {
            if (isset($this->prestamoModel) && $this->prestamoModel) {
                $estadisticas = $this->prestamoModel->getEstadisticasGeneralesUsuarios();
                if ($estadisticas && is_array($estadisticas)) {
                    $data['estadisticas'] = $estadisticas;
                }
                
                $topUsuarios = $this->prestamoModel->getTopUsuariosActivos(5);
                if ($topUsuarios && is_array($topUsuarios)) {
                    $data['top_usuarios'] = $topUsuarios;
                }
                
                $usuariosPrestamos = $this->prestamoModel->getEstadisticasDetalladasUsuarios([]);
                if ($usuariosPrestamos && is_array($usuariosPrestamos)) {
                    $data['usuarios_prestamos'] = $usuariosPrestamos;
                }
            }
        } catch (\Exception $e) {
            $data['error'] = 'Error al conectar con la base de datos: ' . $e->getMessage();
        }

        return view('Administrador/reportes/prestamos-usuarios', $data);
    }

    /**
     * Reporte de Recursos Populares
     * Muestra ranking y estadísticas de los recursos más solicitados
     * 
     * @return string
     */
    public function recursosPopulares()
    {
        try {
            $data = [
                'title' => 'Reporte - Recursos Populares | Sistema Biblioteca',
                'breadcrumb' => [
                    'Inicio' => base_url('admin'),
                    'Reportes y Estadísticas' => '#',
                    'Recursos Populares' => ''
                ],
                // Métricas de popularidad
                'metricas' => [
                    'recurso_mas_popular' => 'El Principito',
                    'total_prestamos' => 1456,
                    'promedio_puntuacion' => 8.5,
                    'crecimiento' => '+15%'
                ],
                // Top 10 recursos más populares
                'top_recursos' => [
                    [
                        'posicion' => 1,
                        'titulo' => 'El Principito',
                        'autor' => 'Antoine de Saint-Exupéry',
                        'categoria' => 'Literatura',
                        'prestamos' => 127,
                        'puntuacion' => 4.8
                    ],
                    [
                        'posicion' => 2,
                        'titulo' => 'Cien años de soledad',
                        'autor' => 'Gabriel García Márquez',
                        'categoria' => 'Literatura',
                        'prestamos' => 121,
                        'puntuacion' => 4.7
                    ],
                    // ... más recursos
                ],
                // Distribución por categorías
                'categorias_distribucion' => [
                    'Literatura' => 35,
                    'Matemáticas' => 22,
                    'Ciencias' => 18,
                    'Historia' => 15,
                    'Arte' => 6,
                    'Otros' => 4
                ],
                // Lista completa de recursos
                'recursos_completos' => [], // Se cargaría desde el modelo
                'filtros' => [
                    'periodos' => [
                        '7' => 'Últimos 7 días',
                        '30' => 'Último mes',
                        '90' => 'Últimos 3 meses',
                        '180' => 'Últimos 6 meses',
                        '365' => 'Último año',
                        'all' => 'Todo el tiempo'
                    ],
                    'categorias' => [], // Se cargaría desde el modelo
                    'tipos' => ['fisico' => 'Físico', 'digital' => 'Digital']
                ]
            ];

            if ($this->request->isAJAX()) {
                return view('Administrador/reportes/recursos-populares', $data);
            }

            return view('Administrador/reportes/recursos-populares', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en ReporteController::recursosPopulares: ' . $e->getMessage());
            
            return view('Administrador/reportes/recursos-populares', [
                'title' => 'Error - Recursos Populares',
                'error' => 'Error al cargar los datos del reporte: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reporte de Inventario
     * Muestra el estado actual del inventario con estadísticas de disponibilidad
     * 
     * @return string
     */
    public function inventario()
    {
        try {
            $data = [
                'title' => 'Reporte - Estado del Inventario | Sistema Biblioteca',
                'breadcrumb' => [
                    'Inicio' => base_url('admin'),
                    'Reportes y Estadísticas' => '#',
                    'Estado del Inventario' => ''
                ],
                // Resumen del inventario
                'resumen' => [
                    'total_recursos' => 1248,
                    'disponibles' => 1089,
                    'prestados' => 142,
                    'perdidos' => 17,
                    'valor_total' => 89420,
                    'ultima_actualizacion' => date('Y-m-d H:i:s'),
                    'porcentaje_disponible' => 87.3,
                    'porcentaje_prestado' => 11.4,
                    'porcentaje_perdido' => 1.3
                ],
                // Distribución por estado
                'distribucion_estado' => [
                    'disponible' => 1089,
                    'prestado' => 142,
                    'perdido' => 17
                ],
                // Alertas del sistema
                'alertas' => [
                    [
                        'tipo' => 'warning',
                        'mensaje' => '17 recursos marcados como perdidos o dañados requieren revisión',
                        'cantidad' => 17
                    ],
                    [
                        'tipo' => 'info',
                        'mensaje' => '8 recursos con préstamos vencidos pendientes de devolución',
                        'cantidad' => 8
                    ],
                    [
                        'tipo' => 'success',
                        'mensaje' => 'Inventario actualizado correctamente. No hay inconsistencias detectadas',
                        'cantidad' => 0
                    ]
                ],
                // Movimientos recientes
                'movimientos_recientes' => [
                    [
                        'tipo' => 'devolucion',
                        'recurso' => 'El Principito',
                        'usuario' => 'María González',
                        'fecha' => '2 horas',
                        'estado' => 'disponible'
                    ],
                    [
                        'tipo' => 'prestamo',
                        'recurso' => 'Álgebra de Baldor',
                        'usuario' => 'Carlos Mendoza',
                        'fecha' => '4 horas',
                        'estado' => 'prestado'
                    ],
                    // ... más movimientos
                ],
                // Inventario completo
                'inventario_completo' => [], // Se cargaría desde el modelo
                'filtros' => [
                    'estados' => ['disponible', 'prestado', 'perdido', 'danado'],
                    'tipos' => ['fisico', 'digital'],
                    'categorias' => [], // Se cargaría desde el modelo
                    'ubicaciones' => ['estante-a', 'estante-b', 'estante-c', 'deposito']
                ]
            ];

            if ($this->request->isAJAX()) {
                return view('Administrador/reportes/inventario', $data);
            }

            return view('Administrador/reportes/inventario', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en ReporteController::inventario: ' . $e->getMessage());
            
            return view('Administrador/reportes/inventario', [
                'title' => 'Error - Reporte de Inventario',
                'error' => 'Error al cargar los datos del inventario: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Método para exportar datos de reportes (JSON)
     * Para ser implementado cuando se requiera funcionalidad de exportación
     * 
     * @param string $tipo Tipo de reporte a exportar
     * @param string $formato Formato de exportación (excel, pdf, csv)
     */
    public function exportar($tipo, $formato = 'excel')
    {
        // TODO: Implementar lógica de exportación
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Funcionalidad de exportación en desarrollo',
            'tipo' => $tipo,
            'formato' => $formato
        ]);
    }

    /**
     * Método para obtener datos filtrados vía AJAX
     * Para ser implementado cuando se requiera filtrado dinámico
     * 
     * @param string $reporte Tipo de reporte
     */
    public function filtrar($reporte)
    {
        // TODO: Implementar lógica de filtrado AJAX
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Funcionalidad de filtrado en desarrollo',
            'reporte' => $reporte
        ]);
    }

    /**
     * Método para generar gráficos dinámicos
     * Para ser implementado cuando se requieran gráficos interactivos
     */
    public function grafico($tipo)
    {
        try {
            if ($tipo === 'tendencias-mensuales') {
                $tendencias = $this->prestamoModel->getTendenciasMensuales(12);
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => $tendencias
                ]);
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tipo de gráfico no soportado',
                'tipo' => $tipo
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al generar gráfico: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener detalle completo de un usuario específico
     */
    public function detalleUsuario($idpersona)
    {
        try {
            $detalle = $this->prestamoModel->getDetalleCompletoUsuario($idpersona);
            
            if (!$detalle) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $detalle
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en detalleUsuario: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener detalle del usuario: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Aplicar filtros y obtener datos actualizados
     */
    public function aplicarFiltros()
    {
        try {
            $filtros = [
                'fecha_desde' => $this->request->getPost('fecha_desde'),
                'fecha_hasta' => $this->request->getPost('fecha_hasta'),
                'nivel' => $this->request->getPost('nivel'),
                'grado' => $this->request->getPost('grado')
            ];

            // Obtener datos filtrados
            $estadisticas = $this->prestamoModel->getEstadisticasGeneralesUsuarios();
            $topUsuarios = $this->prestamoModel->getTopUsuariosActivos(5);
            $usuariosPrestamos = $this->prestamoModel->getEstadisticasDetalladasUsuarios($filtros);
            $tendenciasMensuales = $this->prestamoModel->getTendenciasMensuales(12);

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'estadisticas' => $estadisticas,
                    'top_usuarios' => $topUsuarios,
                    'usuarios_prestamos' => $usuariosPrestamos,
                    'tendencias_mensuales' => $tendenciasMensuales
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en aplicarFiltros: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al aplicar filtros: ' . $e->getMessage()
            ]);
        }
    }
}