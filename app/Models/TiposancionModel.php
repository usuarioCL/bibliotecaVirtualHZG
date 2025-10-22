<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposancionModel extends Model
{
    protected $table = 'tiposancion';
    protected $primaryKey = 'idtiposancion';
    protected $allowedFields = [
        'tiposancion'
    ];

    protected $validationRules = [
        'tiposancion' => 'required|max_length[80]|is_unique[tiposancion.tiposancion,idtiposancion,{id}]'
    ];

    protected $validationMessages = [
        'tiposancion' => [
            'required' => 'El nombre del tipo de sanción es obligatorio',
            'max_length' => 'El nombre no puede exceder 80 caracteres',
            'is_unique' => 'Ya existe un tipo de sanción con ese nombre'
        ]
    ];

    /**
     * Obtener todos los tipos de sanción
     */
    public function obtenerTiposActivos()
    {
        return $this->orderBy('tiposancion', 'ASC')->findAll();
    }

    /**
     * Obtener todos los tipos con estadísticas
     */
    public function obtenerTiposConEstadisticas()
    {
        return $this->select('
            ts.*,
            COUNT(s.idsancion) as total_sanciones,
            COUNT(CASE WHEN s.estado_sancion = "activa" THEN 1 END) as sanciones_activas
        ')
        ->join('sanciones s', 's.idtiposancion = ts.idtiposancion', 'left')
        ->groupBy('ts.idtiposancion')
        ->orderBy('ts.tiposancion', 'ASC')
        ->findAll();
    }

    /**
     * Cambiar estado de un tipo de sanción (deshabilitado - sin columna activo)
     */
    public function cambiarEstado($id, $activo)
    {
        return false;
    }
}
