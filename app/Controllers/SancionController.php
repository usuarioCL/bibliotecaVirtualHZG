<?php

namespace App\Controllers;

use App\Models\SancionModel;
use App\Models\TiposancionModel;
use App\Models\personaModel;
use App\Models\usuarioModel;

class SancionController extends BaseController
{
    protected $sancionModel;
    protected $tiposancionModel;
    protected $personaModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->sancionModel = new SancionModel();
        $this->tiposancionModel = new TiposancionModel();
        $this->personaModel = new personaModel();
        $this->usuarioModel = new usuarioModel();
    }

    /**
     * Vista principal de sanciones activas
     */
    public function activas()
    {
        try {
            $filtros = [
                'tipo_sancion' => $this->request->getGet('tipo_sancion') ?? '',
                'nivel' => $this->request->getGet('nivel') ?? '',
                'buscar' => $this->request->getGet('buscar') ?? ''
            ];

            // Obtener datos usando los métodos del modelo
            $sanciones = $this->sancionModel->obtenerSancionesActivas($filtros);
            $tipos_sancion = $this->tiposancionModel->obtenerTiposActivos();
            
            // Obtener niveles educativos únicos
            $niveles_educativos = $this->obtenerNivelesEducativos();

            // Calcular estadísticas usando el método del modelo
            $estadisticas = $this->sancionModel->obtenerEstadisticas();

            $data = [
                'sanciones' => $sanciones,
                'tipos_sancion' => $tipos_sancion,
                'niveles_educativos' => $niveles_educativos,
                'estadisticas' => $estadisticas,
                'filtros' => $filtros
            ];

            // Si es una petición AJAX, retornar solo el contenido parcial
            if ($this->request->isAJAX()) {
                return view('Administrador/sanciones/partials/activas', $data);
            }

            // Si no es AJAX, retornar la vista completa (para casos especiales)
            $data['title'] = 'Sanciones Activas';
            return view('Administrador/sanciones/activas', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error en SancionController::activas: ' . $e->getMessage());
            
            $errorData = [
                'sanciones' => [],
                'tipos_sancion' => [],
                'estadisticas' => ['total' => 0, 'activas' => 0, 'cumplidas' => 0, 'canceladas' => 0],
                'filtros' => [],
                'error' => 'Error al cargar los datos: ' . $e->getMessage()
            ];

            if ($this->request->isAJAX()) {
                return view('Administrador/sanciones/partials/activas', $errorData);
            }

            $errorData['title'] = 'Sanciones Activas';
            return view('Administrador/sanciones/activas', $errorData);
        }
    }

    /**
     * Vista de historial de sanciones
     */
    public function historial()
    {
        try {
            $filtros = [
                'estado' => $this->request->getGet('estado') ?? '',
                'fecha_desde' => $this->request->getGet('fecha_desde') ?? '',
                'fecha_hasta' => $this->request->getGet('fecha_hasta') ?? '',
                'buscar' => $this->request->getGet('buscar') ?? ''
            ];

            // Obtener datos usando los métodos del modelo
            $sanciones = $this->sancionModel->obtenerHistorialSanciones($filtros);

            // Calcular estadísticas usando el método del modelo
            $estadisticas = $this->sancionModel->obtenerEstadisticas();

            $data = [
                'sanciones' => $sanciones,
                'estadisticas' => $estadisticas,
                'filtros' => $filtros
            ];

            // Si es una petición AJAX, retornar solo el contenido parcial
            if ($this->request->isAJAX()) {
                return view('Administrador/sanciones/partials/historial', $data);
            }

            // Si no es AJAX, retornar la vista completa (para casos especiales)
            $data['title'] = 'Historial de Sanciones';
            return view('Administrador/sanciones/historial', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error en SancionController::historial: ' . $e->getMessage());
            
            $errorData = [
                'sanciones' => [],
                'estadisticas' => ['total' => 0, 'activas' => 0, 'cumplidas' => 0, 'canceladas' => 0],
                'filtros' => [],
                'error' => 'Error al cargar los datos: ' . $e->getMessage()
            ];

            if ($this->request->isAJAX()) {
                return view('Administrador/sanciones/partials/historial', $errorData);
            }

            $errorData['title'] = 'Historial de Sanciones';
            return view('Administrador/sanciones/historial', $errorData);
        }
    }

    /**
     * Crear nueva sanción
     */
    public function crear()
    {
        // Debug: verificar método de petición
        log_message('debug', 'SancionController::crear - Método: ' . $this->request->getMethod());
        log_message('debug', 'SancionController::crear - Es AJAX: ' . ($this->request->isAJAX() ? 'Sí' : 'No'));
        
        // Solo permitir peticiones POST
        if ($this->request->getMethod() !== 'post') {
            log_message('error', 'SancionController::crear - Método no permitido: ' . $this->request->getMethod());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Método no permitido: ' . $this->request->getMethod()
            ]);
        }

        try {
            $data = [
                'idtiposancion' => $this->request->getPost('idtiposancion'),
                'idpersona' => $this->request->getPost('idpersona'),
                'detallesancion' => $this->request->getPost('detallesancion'),
                'fecha_sancion' => $this->request->getPost('fecha_sancion') ?: date('Y-m-d'),
                'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null,
                'estado_sancion' => 'activa',
                'usuario_registra' => session('idusuario') ?: 1, // Fallback si no hay sesión
                'observaciones' => $this->request->getPost('observaciones') ?: null
            ];

            // Validar datos requeridos
            if (empty($data['idtiposancion']) || empty($data['idpersona']) || empty($data['detallesancion'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Los campos tipo de sanción, persona y detalles son obligatorios'
                ]);
            }

            // Verificar que la persona existe
            $persona = $this->personaModel->find($data['idpersona']);
            if (!$persona) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La persona seleccionada no existe'
                ]);
            }

            // Verificar que el tipo de sanción existe
            $tipoSancion = $this->tiposancionModel->find($data['idtiposancion']);
            if (!$tipoSancion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El tipo de sanción seleccionado no existe'
                ]);
            }

            if ($this->sancionModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Sanción registrada exitosamente'
                ]);
            } else {
                $errors = $this->sancionModel->errors();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al registrar la sanción: ' . implode(', ', $errors)
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en SancionController::crear: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Guardar nueva sanción (método alternativo)
     */
    public function guardarSancion()
    {
        // Debug: verificar método de petición
        log_message('debug', 'SancionController::guardarSancion - Método: ' . $this->request->getMethod());
        log_message('debug', 'SancionController::guardarSancion - Es AJAX: ' . ($this->request->isAJAX() ? 'Sí' : 'No'));
        
        try {
            $data = [
                'idtiposancion' => $this->request->getPost('idtiposancion'),
                'idpersona' => $this->request->getPost('idpersona'),
                'detallesancion' => $this->request->getPost('detallesancion'),
                'fecha_sancion' => $this->request->getPost('fecha_sancion') ?: date('Y-m-d'),
                'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null,
                'estado_sancion' => 'activa',
                'usuario_registra' => session('idusuario') ?: 1,
                'observaciones' => $this->request->getPost('observaciones') ?: null
            ];

            log_message('debug', 'SancionController::guardarSancion - Datos recibidos: ' . json_encode($data));

            // Validar datos requeridos
            if (empty($data['idtiposancion']) || empty($data['idpersona']) || empty($data['detallesancion'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Los campos tipo de sanción, persona y detalles son obligatorios'
                ]);
            }

            // Verificar que la persona existe
            $persona = $this->personaModel->find($data['idpersona']);
            if (!$persona) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La persona seleccionada no existe'
                ]);
            }

            // Verificar que el tipo de sanción existe
            $tipoSancion = $this->tiposancionModel->find($data['idtiposancion']);
            if (!$tipoSancion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El tipo de sanción seleccionado no existe'
                ]);
            }

            if ($this->sancionModel->insert($data)) {
                log_message('info', 'Sanción creada exitosamente: ' . json_encode($data));
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Sanción registrada exitosamente'
                ]);
            } else {
                $errors = $this->sancionModel->errors();
                log_message('error', 'Error al crear sanción: ' . implode(', ', $errors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al registrar la sanción: ' . implode(', ', $errors)
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en SancionController::guardarSancion: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ver detalles de una sanción
     */
    public function ver($id)
    {
        $sancion = $this->sancionModel->obtenerDetallesCompletos($id);
        
        if (!$sancion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sanción no encontrada'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'sancion' => $sancion
        ]);
    }

    /**
     * Editar sanción
     */
    public function editar($id)
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'idtiposancion' => $this->request->getPost('idtiposancion'),
                'detallesancion' => $this->request->getPost('detallesancion'),
                'fecha_sancion' => $this->request->getPost('fecha_sancion'),
                'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
                'estado_sancion' => $this->request->getPost('estado_sancion'),
                'observaciones' => $this->request->getPost('observaciones')
            ];

            if ($this->sancionModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Sanción actualizada exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar la sanción: ' . implode(', ', $this->sancionModel->errors())
                ]);
            }
        }

        $data = [
            'sancion' => $this->sancionModel->obtenerDetallesCompletos($id),
            'tipos_sancion' => $this->tiposancionModel->obtenerTiposActivos()
        ];

        return view('Administrador/sanciones/editar', $data);
    }

    /**
     * Eliminar sanción
     */
    public function eliminar($id)
    {
        if ($this->sancionModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sanción eliminada exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar la sanción'
            ]);
        }
    }

    /**
     * Cambiar estado de sanción
     */
    public function cambiarEstado()
    {
        $id = $this->request->getPost('id');
        $nuevoEstado = $this->request->getPost('estado');
        $observaciones = $this->request->getPost('observaciones');

        if ($this->sancionModel->cambiarEstado($id, $nuevoEstado, $observaciones)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado de sanción actualizado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar el estado de la sanción'
            ]);
        }
    }

    /**
     * Buscar personas para autocompletado
     */
    public function buscarPersonas()
    {
        $query = $this->request->getGet('q');
        
        $personas = $this->personaModel->select('idpersona, nombres, apellidos, numerodoc, tipodoc')
            ->groupStart()
                ->like('nombres', $query)
                ->orLike('apellidos', $query)
                ->orLike('numerodoc', $query)
            ->groupEnd()
            ->limit(10)
            ->findAll();

        $resultado = [];
        foreach ($personas as $persona) {
            $resultado[] = [
                'id' => $persona['idpersona'],
                'text' => $persona['nombres'] . ' ' . $persona['apellidos'],
                'documento' => $persona['tipodoc'] . ': ' . $persona['numerodoc']
            ];
        }

        return $this->response->setJSON($resultado);
    }

    /**
     * Obtener estadísticas para dashboard
     */
    public function estadisticas()
    {
        $estadisticas = $this->sancionModel->obtenerEstadisticas();
        $proximasVencer = $this->sancionModel->obtenerSancionesProximasAVencer();

        return $this->response->setJSON([
            'success' => true,
            'estadisticas' => $estadisticas,
            'proximas_vencer' => $proximasVencer
        ]);
    }

    /**
     * Exportar sanciones a Excel
     */
    public function exportarExcel()
    {
        $filtros = [
            'tipo_sancion' => $this->request->getGet('tipo_sancion'),
            'nivel' => $this->request->getGet('nivel'),
            'buscar' => $this->request->getGet('buscar')
        ];

        $sanciones = $this->sancionModel->obtenerSancionesActivas($filtros);

        // Aquí implementarías la lógica de exportación a Excel
        // Por ahora retornamos un JSON con los datos
        return $this->response->setJSON([
            'success' => true,
            'data' => $sanciones,
            'message' => 'Datos preparados para exportación'
        ]);
    }

    /**
     * Obtener todas las sanciones de una persona específica
     */
    public function obtenerSancionesPersona($idpersona)
    {
        try {
            $sanciones = $this->sancionModel->obtenerSancionesPorPersona($idpersona);
            
            return $this->response->setJSON([
                'success' => true,
                'sanciones' => $sanciones
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener sanciones de persona: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener las sanciones de la persona'
            ]);
        }
    }

    /**
     * Levantar sanción antes de tiempo
     */
    public function levantarSancion()
    {
        try {
            $idsancion = $this->request->getPost('idsancion');
            $motivoLevantamiento = $this->request->getPost('motivo_levantamiento');
            $usuarioLevanta = session('idusuario') ?: 1;

            // Validar datos
            if (empty($idsancion) || empty($motivoLevantamiento)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El ID de sanción y el motivo son obligatorios'
                ]);
            }

            // Verificar que la sanción existe y puede ser levantada
            if (!$this->sancionModel->puedeLevantarSancion($idsancion)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Esta sanción no puede ser levantada o no existe'
                ]);
            }

            // Obtener detalles de la sanción antes del levantamiento
            $sancion = $this->sancionModel->obtenerDetallesCompletos($idsancion);
            
            // Levantar la sanción
            if ($this->sancionModel->levantarSancion($idsancion, $motivoLevantamiento, $usuarioLevanta)) {
                log_message('info', "Sanción {$idsancion} levantada por usuario {$usuarioLevanta}. Motivo: {$motivoLevantamiento}");
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Sanción levantada exitosamente',
                    'sancion' => [
                        'persona' => $sancion['nombre_completo'],
                        'tipo' => $sancion['tiposancion'],
                        'fecha_levantamiento' => date('Y-m-d H:i:s')
                    ]
                ]);
            } else {
                $errors = $this->sancionModel->errors();
                log_message('error', 'Error al levantar sanción: ' . implode(', ', $errors));
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al levantar la sanción: ' . implode(', ', $errors)
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en levantarSancion: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener detalles de una sanción para levantamiento
     */
    public function obtenerDetallesParaLevantamiento($id)
    {
        try {
            $sancion = $this->sancionModel->obtenerDetallesCompletos($id);
            
            if (!$sancion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sanción no encontrada'
                ]);
            }

            if ($sancion['estado_sancion'] !== 'activa') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Esta sanción no está activa y no puede ser levantada'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'sancion' => $sancion
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener detalles para levantamiento: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener los detalles de la sanción'
            ]);
        }
    }

    /**
     * Obtener niveles educativos únicos
     */
    private function obtenerNivelesEducativos()
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->query("
                SELECT DISTINCT g.nivel 
                FROM grupos g 
                JOIN matriculas m ON g.idgrupo = m.idgrupo 
                JOIN personas p ON m.idpersona = p.idpersona 
                JOIN sanciones s ON s.idpersona = p.idpersona 
                WHERE s.estado_sancion = 'activa' 
                AND g.nivel IS NOT NULL 
                AND g.nivel != '' 
                ORDER BY g.nivel
            ");
            
            $niveles = $query->getResultArray();
            return array_column($niveles, 'nivel');
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener niveles educativos: ' . $e->getMessage());
            return ['Primaria', 'Secundaria', 'Superior']; // Valores por defecto
        }
    }
}
