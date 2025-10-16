<?php

namespace App\Models;

use CodeIgniter\Model;

class SancionModel extends Model
{
    protected $table = 'sanciones';
    protected $primaryKey = 'idsancion';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'idtiposancion',
        'idpersona', 
        'detallesancion'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'idtiposancion' => 'required|integer',
        'idpersona' => 'required|integer',
        'detallesancion' => 'permit_empty|max_length[200]'
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
            'max_length' => 'El detalle no puede exceder 200 caracteres'
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
     * Obtener sanciones con información completa (persona y tipo de sanción)
     */
    public function getSancionesCompletas()
    {
        // Subconsulta para obtener la última matrícula de la persona y su grupo
        $subQuery = "SELECT m.idpersona, g.nivel, g.grado, g.seccion
                      FROM matriculas m
                      INNER JOIN grupos g ON g.idgrupo = m.idgrupo
                      WHERE m.idpersona = sanciones.idpersona AND m.estadomatricula = 1
                      ORDER BY m.fechamatricula DESC
                      LIMIT 1";

        return $this->select('
                sanciones.idsancion,
                sanciones.detallesancion,
                personas.idpersona,
                personas.apellidos,
                personas.nombres,
                personas.numerodoc,
                tiposancion.idtiposancion,
                tiposancion.tiposancion,
                mat.nivel as nivel,
                mat.grado as grado,
                mat.seccion as seccion
            ')
            ->join('personas', 'personas.idpersona = sanciones.idpersona')
            ->join('tiposancion', 'tiposancion.idtiposancion = sanciones.idtiposancion')
            ->join("($subQuery) mat", 'mat.idpersona = sanciones.idpersona', 'left', false)
            ->orderBy('sanciones.idsancion', 'DESC')
            ->findAll();
    }

    /**
     * Obtener una sanción específica con información completa
     */
    public function getSancionCompleta($idsancion)
    {
        return $this->select('
                sanciones.*,
                personas.apellidos,
                personas.nombres,
                personas.numerodoc,
                personas.email,
                tiposancion.tiposancion
            ')
            ->join('personas', 'personas.idpersona = sanciones.idpersona')
            ->join('tiposancion', 'tiposancion.idtiposancion = sanciones.idtiposancion')
            ->where('sanciones.idsancion', $idsancion)
            ->first();
    }

    /**
     * Obtener sanciones por persona
     */
    public function getSancionesPorPersona($idpersona)
    {
        return $this->select('
                sanciones.*,
                tiposancion.tiposancion
            ')
            ->join('tiposancion', 'tiposancion.idtiposancion = sanciones.idtiposancion')
            ->where('sanciones.idpersona', $idpersona)
            ->orderBy('sanciones.idsancion', 'DESC')
            ->findAll();
    }

    /**
     * Buscar sanciones por criterios
     */
    public function buscarSanciones($criterio = '')
    {
        if (empty($criterio)) {
            return $this->getSancionesCompletas();
        }

        return $this->select('
                sanciones.idsancion,
                sanciones.detallesancion,
                personas.apellidos,
                personas.nombres,
                personas.numerodoc,
                tiposancion.tiposancion
            ')
            ->join('personas', 'personas.idpersona = sanciones.idpersona')
            ->join('tiposancion', 'tiposancion.idtiposancion = sanciones.idtiposancion')
            ->groupStart()
                ->like('personas.apellidos', $criterio)
                ->orLike('personas.nombres', $criterio)
                ->orLike('personas.numerodoc', $criterio)
                ->orLike('tiposancion.tiposancion', $criterio)
                ->orLike('sanciones.detallesancion', $criterio)
            ->groupEnd()
            ->orderBy('sanciones.idsancion', 'DESC')
            ->findAll();
    }
}
