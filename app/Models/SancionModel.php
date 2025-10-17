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
        'fecha_vencimiento',
        'estado_sancion',
        'usuario_registra',
        'observaciones'
    ];

    protected $validationRules = [
        'idtiposancion' => 'required|integer',
        'idpersona' => 'required|integer',
        'detallesancion' => 'required|max_length[200]',
        'fecha_sancion' => 'required|valid_date',
        'fecha_vencimiento' => 'permit_empty|valid_date',
        'estado_sancion' => 'required|in_list[activa,cumplida,cancelada]',
        'usuario_registra' => 'permit_empty|integer',
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
            'required' => 'La fecha de sanción es obligatoria',
            'valid_date' => 'La fecha de sanción debe ser válida'
        ],
        'fecha_vencimiento' => [
            'valid_date' => 'La fecha de vencimiento debe ser válida'
        ],
        'estado_sancion' => [
            'required' => 'El estado de la sanción es obligatorio',
            'in_list' => 'El estado debe ser: activa, cumplida o cancelada'
        ]
    ];

    /**
     * Obtener sanciones activas con información completa
     */
    public function obtenerSancionesActivas($filtros = [])
    {
        $builder = $this->select('
            sanciones.*,
            ts.tiposancion,
            ts.descripcion as tipo_descripcion,
            CONCAT(p.nombres, " ", p.apellidos) as nombre_completo,
            p.nombres,
            p.apellidos,
            p.numerodoc,
            p.tipodoc,
            p.telefono,
            p.email,
            u.nomuser as usuario_registra_nombre,
            g.grado,
            g.seccion,
            g.nivel,
            (SELECT COUNT(*) FROM sanciones s2 WHERE s2.idpersona = sanciones.idpersona AND s2.estado_sancion = "activa") as total_sanciones_persona
        ')
        ->join('tiposancion ts', 'ts.idtiposancion = sanciones.idtiposancion')
        ->join('personas p', 'p.idpersona = sanciones.idpersona')
        ->join('matriculas m', 'm.idpersona = p.idpersona', 'left')
        ->join('grupos g', 'g.idgrupo = m.idgrupo', 'left')
        ->join('usuarios u', 'u.idusuario = sanciones.usuario_registra', 'left')
        ->where('sanciones.estado_sancion', 'activa');

        // Aplicar filtros
        if (!empty($filtros['tipo_sancion'])) {
            $builder->where('sanciones.idtiposancion', $filtros['tipo_sancion']);
        }

        if (!empty($filtros['nivel'])) {
            $builder->where('g.nivel', $filtros['nivel']);
        }

        if (!empty($filtros['buscar'])) {
            $builder->groupStart()
                ->like('p.nombres', $filtros['buscar'])
                ->orLike('p.apellidos', $filtros['buscar'])
                ->orLike('p.numerodoc', $filtros['buscar'])
                ->orLike('sanciones.detallesancion', $filtros['buscar'])
            ->groupEnd();
        }

        return $builder->orderBy('sanciones.fecha_sancion', 'DESC')->findAll();
    }

    /**
     * Obtener historial de sanciones
     */
    public function obtenerHistorialSanciones($filtros = [])
    {
        $builder = $this->select('
            sanciones.*,
            ts.tiposancion,
            ts.descripcion as tipo_descripcion,
            CONCAT(p.nombres, " ", p.apellidos) as nombre_completo,
            p.nombres,
            p.apellidos,
            p.numerodoc,
            p.tipodoc,
            p.telefono,
            p.email,
            u.nomuser as usuario_registra_nombre,
            g.grado,
            g.seccion,
            g.nivel
        ')
        ->join('tiposancion ts', 'ts.idtiposancion = sanciones.idtiposancion')
        ->join('personas p', 'p.idpersona = sanciones.idpersona')
        ->join('matriculas m', 'm.idpersona = p.idpersona', 'left')
        ->join('grupos g', 'g.idgrupo = m.idgrupo', 'left')
        ->join('usuarios u', 'u.idusuario = sanciones.usuario_registra', 'left');

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

        return $builder->orderBy('sanciones.fecha_sancion', 'DESC')->findAll();
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
            'estado_sancion' => 'cumplida',
            'observaciones' => $motivoLevantamiento,
            'fecha_vencimiento' => date('Y-m-d'), // Fecha actual como fecha de cumplimiento
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
            ts.descripcion as tipo_descripcion,
            CONCAT(p.nombres, " ", p.apellidos) as nombre_completo,
            p.numerodoc,
            p.email,
            p.telefono,
            u.nomuser as usuario_registra_nombre,
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
        ->where('sanciones.idsancion', $id)
        ->first();
    }

    /**
     * Obtener todas las sanciones de una persona específica
     */
    public function obtenerSancionesPorPersona($idpersona)
    {
        return $this->select('
            sanciones.*,
            ts.tiposancion,
            ts.descripcion as tipo_descripcion,
            CONCAT(p.nombres, " ", p.apellidos) as nombre_completo,
            p.nombres,
            p.apellidos,
            p.numerodoc,
            p.tipodoc,
            p.telefono,
            p.email,
            u.nomuser as usuario_registra_nombre,
            g.grado,
            g.seccion,
            g.nivel
        ')
        ->join('tiposancion ts', 'ts.idtiposancion = sanciones.idtiposancion')
        ->join('personas p', 'p.idpersona = sanciones.idpersona')
        ->join('matriculas m', 'm.idpersona = p.idpersona', 'left')
        ->join('grupos g', 'g.idgrupo = m.idgrupo', 'left')
        ->join('usuarios u', 'u.idusuario = sanciones.usuario_registra', 'left')
        ->where('sanciones.idpersona', $idpersona)
        ->orderBy('sanciones.fecha_sancion', 'DESC')
        ->findAll();
    }
}
