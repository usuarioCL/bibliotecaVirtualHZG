<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposancionModel extends Model
{
    protected $table = 'tiposancion';
    protected $primaryKey = 'idtiposancion';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'tiposancion'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'tiposancion' => 'required|max_length[80]|is_unique[tiposancion.tiposancion,idtiposancion,{idtiposancion}]'
    ];
    protected $validationMessages = [
        'tiposancion' => [
            'required' => 'El tipo de sanción es obligatorio',
            'max_length' => 'El tipo de sanción no puede exceder 80 caracteres',
            'is_unique' => 'Este tipo de sanción ya existe'
        ]
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Obtener todos los tipos de sanción ordenados alfabéticamente
     */
    public function getTiposSancionOrdenados()
    {
        return $this->orderBy('tiposancion', 'ASC')->findAll();
    }

    /**
     * Verificar si un tipo de sanción está siendo usado
     */
    public function estaEnUso($idtiposancion)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('sanciones');
        $count = $builder->where('idtiposancion', $idtiposancion)->countAllResults();
        return $count > 0;
    }
}
