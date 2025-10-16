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
        // Leer directamente de la vista SQL creada en MySQL
        $db = \Config\Database::connect();
        return $db->table('vista_sanciones_activas')
                  ->orderBy('idsancion', 'DESC')
                  ->get()
                  ->getResultArray();
    }

    /**
     * Obtener una sanción específica con información completa
     */
    public function getSancionCompleta($idsancion)
    {
        $db = \Config\Database::connect();
        return $db->table('vista_sanciones_activas')
                  ->where('idsancion', $idsancion)
                  ->get()
                  ->getRowArray();
    }

    /**
     * Obtener estadísticas de sanciones
     */
    public function getEstadisticasSanciones()
    {
        $db = \Config\Database::connect();
        
        // Total de sanciones
        $total = $db->table('vista_sanciones_activas')->countAllResults();
        
        // Suspensiones (tipos 3 y 4)
        $suspensiones = $db->table('vista_sanciones_activas')
                          ->whereIn('idtiposancion', [3, 4])
                          ->countAllResults();
        
        // Amonestaciones (tipos 1 y 2)
        $amonestaciones = $db->table('vista_sanciones_activas')
                           ->whereIn('idtiposancion', [1, 2])
                           ->countAllResults();
        
        // Estudiantes únicos afectados
        $estudiantesAfectados = $db->table('vista_sanciones_activas')
                                 ->select('idpersona')
                                 ->distinct()
                                 ->countAllResults();
        
        return [
            'total' => $total,
            'suspensiones' => $suspensiones,
            'amonestaciones' => $amonestaciones,
            'estudiantes_afectados' => $estudiantesAfectados
        ];
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
