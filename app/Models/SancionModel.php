<?php

namespace App\Models;

use CodeIgniter\Model;

class SancionModel extends Model
{
    protected $table = 'sanciones';
    protected $primaryKey = 'idsancion';
    protected $allowedFields = [
        'idtiposancion',
        'idpersona',
        'detallesancion',
        'fecha_sancion',
        'fecha_inicio',
        'fecha_vencimiento',
        'estado_sancion',
        'duracion_dias',
        'usuario_registra',
        'usuario_levanta',
        'fecha_levantamiento',
        'motivo_levantamiento',
        'observaciones'
    ];

    protected $validationRules = [
        'idtiposancion' => 'required|integer',
        'idpersona' => 'required|integer',
        'detallesancion' => 'required|max_length[200]',
        'fecha_sancion' => 'permit_empty|valid_date',
        'fecha_inicio' => 'permit_empty|valid_date',
        'fecha_vencimiento' => 'permit_empty|valid_date',
        'estado_sancion' => 'permit_empty|in_list[activa,cumplida,cancelada,suspendida]',
        'duracion_dias' => 'permit_empty|integer',
        'usuario_registra' => 'permit_empty|integer',
        'usuario_levanta' => 'permit_empty|integer',
        'observaciones' => 'permit_empty'
    ];

    protected $validationMessages = [
        'idtiposancion' => [
            'required' => 'El tipo de sanción es obligatorio',
            'integer' => 'El tipo de sanción debe ser un número válido'
        ],
        'idpersona' => [
            'required' => 'La persona es obligatoria',
            'integer' => 'La persona debe ser un número válido'
        ],
        'detallesancion' => [
            'required' => 'Los detalles de la sanción son obligatorios',
            'max_length' => 'Los detalles no pueden exceder 200 caracteres'
        ],
        'fecha_sancion' => [
            'valid_date' => 'La fecha de sanción debe ser válida'
        ],
        'fecha_inicio' => [
            'valid_date' => 'La fecha de inicio debe ser válida'
        ],
        'fecha_vencimiento' => [
            'valid_date' => 'La fecha de vencimiento debe ser válida'
        ],
        'estado_sancion' => [
            'in_list' => 'El estado debe ser: activa, cumplida, cancelada o suspendida'
        ]
    ];

    /**
     * Obtener sanciones activas agrupadas por persona
     */
    public function obtenerSancionesActivas($filtros = [])
    {
        $db = \Config\Database::connect();
        
        // Construir la consulta base
        $sql = "
            SELECT 
                p.idpersona,
                CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo,
                p.nombres,
                p.apellidos,
                p.numerodoc,
                p.tipodoc,
                p.telefono,
                p.email,
                g.grado,
                g.seccion,
                g.nivel,
                COUNT(s.idsancion) as total_sanciones_persona,
                MAX(s.fecha_sancion) as fecha_sancion_reciente,
                MIN(s.fecha_vencimiento) as fecha_vencimiento_proxima,
                GROUP_CONCAT(DISTINCT ts.tiposancion ORDER BY ts.tiposancion SEPARATOR ', ') as tipos_sanciones,
                MAX(s.idsancion) as idsancion_reciente,
                MAX(s.updated_at) as ultima_actualizacion
            FROM sanciones s
            INNER JOIN personas p ON p.idpersona = s.idpersona
            LEFT JOIN matriculas m ON m.idpersona = p.idpersona
            LEFT JOIN grupos g ON g.idgrupo = m.idgrupo
            LEFT JOIN tiposancion ts ON ts.idtiposancion = s.idtiposancion
            WHERE s.estado_sancion = 'activa'
        ";
        
        // Aplicar filtros
        $whereConditions = [];
        $params = [];
        
        if (!empty($filtros['tipo_sancion'])) {
            $whereConditions[] = "s.idtiposancion = ?";
            $params[] = $filtros['tipo_sancion'];
        }
        
        if (!empty($filtros['nivel'])) {
            $whereConditions[] = "g.nivel = ?";
            $params[] = $filtros['nivel'];
        }
        
        if (!empty($filtros['buscar'])) {
            $whereConditions[] = "(p.nombres LIKE ? OR p.apellidos LIKE ? OR p.numerodoc LIKE ?)";
            $buscar = '%' . $filtros['buscar'] . '%';
            $params[] = $buscar;
            $params[] = $buscar;
            $params[] = $buscar;
        }
        
        if (!empty($whereConditions)) {
            $sql .= " AND " . implode(" AND ", $whereConditions);
        }
        
        $sql .= " GROUP BY p.idpersona, p.nombres, p.apellidos, p.numerodoc, p.tipodoc, p.telefono, p.email, g.grado, g.seccion, g.nivel";
        $sql .= " ORDER BY fecha_sancion_reciente DESC";
        
        $query = $db->query($sql, $params);
        return $query->getResultArray();
    }

    /**
     * Obtener historial de sanciones (excluye las activas)
     */
    public function obtenerHistorialSanciones($filtros = [])
    {
        $builder = $this->select('
            sanciones.*,
            ts.tiposancion,
            CONCAT(p.nombres, " ", p.apellidos) as nombre_completo,
            p.nombres,
            p.apellidos,
            p.numerodoc,
            p.tipodoc,
            p.telefono,
            p.email,
            u.nomuser as usuario_registra_nombre,
            ul.nomuser as usuario_levanta_nombre,
            g.grado,
            g.seccion,
            g.nivel
        ')
        ->join('tiposancion ts', 'ts.idtiposancion = sanciones.idtiposancion')
        ->join('personas p', 'p.idpersona = sanciones.idpersona')
        ->join('matriculas m', 'm.idpersona = p.idpersona', 'left')
        ->join('grupos g', 'g.idgrupo = m.idgrupo', 'left')
        ->join('usuarios u', 'u.idusuario = sanciones.usuario_registra', 'left')
        ->join('usuarios ul', 'ul.idusuario = sanciones.usuario_levanta', 'left')
        ->whereIn('sanciones.estado_sancion', ['cumplida', 'cancelada', 'suspendida']);

        // Aplicar filtros
        if (!empty($filtros['estado'])) {
            $builder->where('sanciones.estado_sancion', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde'])) {
            $builder->where('sanciones.fecha_sancion >=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('sanciones.fecha_sancion <=', $filtros['fecha_hasta']);
        }

        if (!empty($filtros['buscar'])) {
            $builder->groupStart()
                ->like('p.nombres', $filtros['buscar'])
                ->orLike('p.apellidos', $filtros['buscar'])
                ->orLike('p.numerodoc', $filtros['buscar'])
                ->orLike('sanciones.detallesancion', $filtros['buscar'])
            ->groupEnd();
        }

        // Ordenar por la última acción: primero por fecha de levantamiento, luego por actualización, finalmente por creación
        return $builder
            ->orderBy('sanciones.updated_at', 'DESC')
            ->findAll();
    }

    /**
     * Obtener estadísticas de sanciones
     */
    public function obtenerEstadisticas()
    {
        $total = $this->countAllResults();
        $activas = $this->where('estado_sancion', 'activa')->countAllResults(false);
        $cumplidas = $this->where('estado_sancion', 'cumplida')->countAllResults(false);
        $canceladas = $this->where('estado_sancion', 'cancelada')->countAllResults(false);

        // Sanciones por tipo
        $porTipo = $this->select('ts.tiposancion, COUNT(*) as total')
            ->join('tiposancion ts', 'ts.idtiposancion = sanciones.idtiposancion')
            ->groupBy('sanciones.idtiposancion')
            ->findAll();

        return [
            'total' => $total,
            'activas' => $activas,
            'cumplidas' => $cumplidas,
            'canceladas' => $canceladas,
            'por_tipo' => $porTipo
        ];
    }

    /**
     * Obtener sanciones próximas a vencer
     */
    public function obtenerSancionesProximasAVencer($dias = 7)
    {
        return $this->select('
            sanciones.*,
            ts.tiposancion,
            CONCAT(p.nombres, " ", p.apellidos) as nombre_completo,
            p.numerodoc,
            DATEDIFF(sanciones.fecha_vencimiento, CURDATE()) as dias_restantes
        ')
        ->join('tiposancion ts', 'ts.idtiposancion = sanciones.idtiposancion')
        ->join('personas p', 'p.idpersona = sanciones.idpersona')
        ->where('sanciones.estado_sancion', 'activa')
        ->where('sanciones.fecha_vencimiento IS NOT NULL')
        ->where('sanciones.fecha_vencimiento <=', date('Y-m-d', strtotime("+{$dias} days")))
        ->orderBy('sanciones.fecha_vencimiento', 'ASC')
        ->findAll();
    }

    /**
     * Cambiar estado de una sanción
     */
    public function cambiarEstado($id, $nuevoEstado, $observaciones = null)
    {
        $data = ['estado_sancion' => $nuevoEstado];
        
        if ($observaciones) {
            $data['observaciones'] = $observaciones;
        }

        return $this->update($id, $data);
    }

    /**
     * Levantar sanción antes de tiempo
     */
    public function levantarSancion($id, $motivoLevantamiento, $usuarioLevanta = null)
    {
        $data = [
            'estado_sancion' => 'cancelada',
            'fecha_levantamiento' => date('Y-m-d H:i:s'),
            'motivo_levantamiento' => $motivoLevantamiento,
            'usuario_levanta' => $usuarioLevanta,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->update($id, $data);
    }

    /**
     * Verificar si una sanción puede ser levantada
     */
    public function puedeLevantarSancion($id)
    {
        $sancion = $this->find($id);
        
        if (!$sancion) {
            return false;
        }

        // Solo se pueden levantar sanciones activas
        return $sancion['estado_sancion'] === 'activa';
    }

    /**
     * Obtener detalles completos de una sanción
     */
    public function obtenerDetallesCompletos($id)
    {
        return $this->select('
            sanciones.*,
            ts.tiposancion,
            CONCAT(p.nombres, " ", p.apellidos) as nombre_completo,
            p.nombres,
            p.apellidos,
            p.numerodoc,
            p.tipodoc,
            p.email,
            p.telefono,
            u.nomuser as usuario_registra_nombre,
            ul.nomuser as usuario_levanta_nombre,
            g.grado,
            g.seccion,
            g.nivel,
            g.aniolectivo
        ')
        ->join('tiposancion ts', 'ts.idtiposancion = sanciones.idtiposancion')
        ->join('personas p', 'p.idpersona = sanciones.idpersona')
        ->join('matriculas m', 'm.idpersona = p.idpersona', 'left')
        ->join('grupos g', 'g.idgrupo = m.idgrupo', 'left')
        ->join('usuarios u', 'u.idusuario = sanciones.usuario_registra', 'left')
        ->join('usuarios ul', 'ul.idusuario = sanciones.usuario_levanta', 'left')
        ->where('sanciones.idsancion', $id)
        ->first();
    }

    /**
     * Obtener todas las sanciones de una persona específica
     * @param int $idpersona ID de la persona
     * @param string|null $estado Filtrar por estado (activa, cumplida, cancelada, suspendida) o null para todas
     */
    public function obtenerSancionesPorPersona($idpersona, $estado = null)
    {
        $builder = $this->select('
            sanciones.*,
            ts.tiposancion,
            CONCAT(p.nombres, " ", p.apellidos) as nombre_completo,
            p.nombres,
            p.apellidos,
            p.numerodoc,
            p.tipodoc,
            p.telefono,
            p.email,
            u.nomuser as usuario_registra_nombre,
            ul.nomuser as usuario_levanta_nombre,
            g.grado,
            g.seccion,
            g.nivel
        ')
        ->join('tiposancion ts', 'ts.idtiposancion = sanciones.idtiposancion')
        ->join('personas p', 'p.idpersona = sanciones.idpersona')
        ->join('matriculas m', 'm.idpersona = p.idpersona', 'left')
        ->join('grupos g', 'g.idgrupo = m.idgrupo', 'left')
        ->join('usuarios u', 'u.idusuario = sanciones.usuario_registra', 'left')
        ->join('usuarios ul', 'ul.idusuario = sanciones.usuario_levanta', 'left')
        ->where('sanciones.idpersona', $idpersona);
        
        // Filtrar por estado si se especifica
        if ($estado !== null) {
            $builder->where('sanciones.estado_sancion', $estado);
        }
        
        return $builder->orderBy('sanciones.fecha_sancion', 'DESC')->findAll();
    }
}
