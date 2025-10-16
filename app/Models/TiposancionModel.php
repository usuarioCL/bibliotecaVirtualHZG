<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposancionModel extends Model
{
    protected $table = 'tiposancion';
    protected $primaryKey = 'idtiposancion';
    protected $allowedFields = [
        'tiposancion',
        'descripcion',
        'activo'
    ];

    protected $validationRules = [
        'tiposancion' => 'required|max_length[80]|is_unique[tiposancion.tiposancion,idtiposancion,{id}]',
        'descripcion' => 'permit_empty',
        'activo' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'tiposancion' => [
            'required' => 'El nombre del tipo de sanción es obligatorio',
            'max_length' => 'El nombre no puede exceder 80 caracteres',
            'is_unique' => 'Ya existe un tipo de sanción con ese nombre'
        ],
        'activo' => [
            'in_list' => 'El estado debe ser activo o inactivo'
        ]
    ];

    /**
     * Obtener tipos de sanción activos
     */
    public function obtenerTiposActivos()
    {
        return $this->where('activo', true)
                   ->orderBy('tiposancion', 'ASC')
                   ->findAll();
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
     * Cambiar estado de un tipo de sanción
     */
    public function cambiarEstado($id, $activo)
    {
        return $this->update($id, ['activo' => $activo]);
    }
}
