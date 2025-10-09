<?php

namespace App\Models;

use CodeIgniter\Model;

class HistorialUsuarioModel extends Model
{
    protected $table = 'historial_usuarios';
    protected $primaryKey = 'id_historial';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'accion',
        'usuario_actor',
        'usuario_afectado',
        'tipo_usuario',
        'detalles',
        'user_agent'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'accion' => 'required|max_length[100]',
        'usuario_actor' => 'required|max_length[50]',
        'tipo_usuario' => 'required|in_list[admin,docente,estudiante]'
    ];

    protected $validationMessages = [
        'accion' => [
            'required' => 'La acción es obligatoria',
            'max_length' => 'La acción no puede exceder 100 caracteres'
        ],
        'usuario_actor' => [
            'required' => 'El usuario actor es obligatorio',
            'max_length' => 'El usuario actor no puede exceder 50 caracteres'
        ],
        'tipo_usuario' => [
            'required' => 'El tipo de usuario es obligatorio',
            'in_list' => 'El tipo de usuario debe ser admin, docente o estudiante'
        ]
    ];

    /**
     * Obtener historial con paginación
     */
    public function getHistorialConPaginacion($limite = 10, $offset = 0)
    {
        return $this->select('*')
                    ->orderBy('fecha_accion', 'DESC')
                    ->limit($limite, $offset)
                    ->findAll();
    }

    /**
     * Obtener historial por usuario
     */
    public function getHistorialPorUsuario($usuario, $limite = 10)
    {
        return $this->where('usuario_actor', $usuario)
                    ->orWhere('usuario_afectado', $usuario)
                    ->orderBy('fecha_accion', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Obtener historial por tipo de acción
     */
    public function getHistorialPorAccion($accion, $limite = 10)
    {
        return $this->like('accion', $accion)
                    ->orderBy('fecha_accion', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Obtener historial por fecha
     */
    public function getHistorialPorFecha($fechaInicio, $fechaFin = null)
    {
        $query = $this->where('DATE(fecha_accion)', $fechaInicio);
        
        if ($fechaFin) {
            $query->where('fecha_accion >=', $fechaInicio . ' 00:00:00')
                  ->where('fecha_accion <=', $fechaFin . ' 23:59:59');
        }
        
        return $query->orderBy('fecha_accion', 'DESC')->findAll();
    }

    /**
     * Obtener estadísticas del historial
     */
    public function getEstadisticas()
    {
        $totalAcciones = $this->countAllResults();
        
        // Acciones de hoy
        $accionesHoy = $this->where('DATE(fecha_accion)', date('Y-m-d'))->countAllResults();
        
        // Usuarios únicos que han realizado acciones
        $usuariosActivos = $this->select('DISTINCT usuario_actor')
                                ->countAllResults();
        
        // Acciones por tipo
        $accionesPorTipo = $this->select('accion, COUNT(*) as total')
                                ->groupBy('accion')
                                ->orderBy('total', 'DESC')
                                ->findAll();

        return [
            'total_acciones' => $totalAcciones,
            'acciones_hoy' => $accionesHoy,
            'usuarios_activos' => $usuariosActivos,
            'acciones_por_tipo' => $accionesPorTipo
        ];
    }

    /**
     * Buscar en el historial
     */
    public function buscarHistorial($termino, $filtros = [])
    {
        $query = $this->groupStart()
                      ->like('usuario_actor', $termino)
                      ->orLike('usuario_afectado', $termino)
                      ->orLike('accion', $termino)
                      ->orLike('detalles', $termino)
                      ->groupEnd();

        // Aplicar filtros adicionales
        if (isset($filtros['tipo_accion']) && !empty($filtros['tipo_accion'])) {
            $query->like('accion', $filtros['tipo_accion']);
        }

        if (isset($filtros['tipo_usuario']) && !empty($filtros['tipo_usuario'])) {
            $query->where('tipo_usuario', $filtros['tipo_usuario']);
        }

        if (isset($filtros['fecha_desde']) && !empty($filtros['fecha_desde'])) {
            $query->where('fecha_accion >=', $filtros['fecha_desde'] . ' 00:00:00');
        }

        if (isset($filtros['fecha_hasta']) && !empty($filtros['fecha_hasta'])) {
            $query->where('fecha_accion <=', $filtros['fecha_hasta'] . ' 23:59:59');
        }

        return $query->orderBy('fecha_accion', 'DESC')->findAll();
    }

    /**
     * Registrar una nueva acción en el historial
     */
    public function registrarAccion($datos)
    {
        // Obtener User Agent
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $data = [
            'accion' => $datos['accion'],
            'usuario_actor' => $datos['usuario_actor'],
            'usuario_afectado' => $datos['usuario_afectado'] ?? null,
            'tipo_usuario' => $datos['tipo_usuario'],
            'detalles' => $datos['detalles'] ?? '',
            'user_agent' => $userAgent
        ];

        return $this->insert($data);
    }


    /**
     * Obtener historial reciente (últimas 24 horas)
     */
    public function getHistorialReciente($limite = 20)
    {
        return $this->where('fecha_accion >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
                    ->orderBy('fecha_accion', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Limpiar historial antiguo (más de X días)
     */
    public function limpiarHistorialAntiguo($dias = 365)
    {
        $fechaLimite = date('Y-m-d H:i:s', strtotime("-{$dias} days"));
        
        return $this->where('fecha_accion <', $fechaLimite)->delete();
    }
}
