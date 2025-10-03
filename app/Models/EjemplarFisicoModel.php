<?php

namespace App\Models;

use CodeIgniter\Model;

class EjemplarFisicoModel extends Model
{
    protected $table = 'ejemplares_fisicos';
    protected $primaryKey = 'idejemplar';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'idrecurso',
        'codigo_ejemplar',
        'estado_ejemplar',
        'ubicacion',
        'observaciones',
        'fecha_ingreso',
        'fecha_ultima_revision',
        'activo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'idrecurso' => 'required|integer',
        'codigo_ejemplar' => 'required|max_length[20]|is_unique[ejemplares_fisicos.codigo_ejemplar,idejemplar,{idejemplar}]',
        'estado_ejemplar' => 'required|in_list[disponible,prestado,dañado,perdido,mantenimiento]',
        'ubicacion' => 'permit_empty|max_length[100]',
        'observaciones' => 'permit_empty',
        'fecha_ingreso' => 'permit_empty|valid_date',
        'fecha_ultima_revision' => 'permit_empty|valid_date',
        'activo' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'codigo_ejemplar' => [
            'is_unique' => 'El código del ejemplar ya existe'
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
     * Obtener todos los ejemplares de un recurso específico
     */
    public function obtenerEjemplaresPorRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)
                   ->where('activo', true)
                   ->orderBy('codigo_ejemplar', 'ASC')
                   ->findAll();
    }

    /**
     * Obtener ejemplares disponibles de un recurso
     */
    public function obtenerEjemplaresDisponibles($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)
                   ->where('estado_ejemplar', 'disponible')
                   ->where('activo', true)
                   ->orderBy('codigo_ejemplar', 'ASC')
                   ->findAll();
    }

    /**
     * Obtener ejemplar por código
     */
    public function obtenerEjemplarPorCodigo($codigo_ejemplar)
    {
        return $this->where('codigo_ejemplar', $codigo_ejemplar)
                   ->where('activo', true)
                   ->first();
    }

    /**
     * Crear ejemplares para un recurso usando el procedimiento almacenado
     */
    public function crearEjemplaresParaRecurso($idrecurso, $cantidad)
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Llamar al procedimiento almacenado
            $sql = "CALL CrearEjemplaresParaRecurso(?, ?)";
            $db->query($sql, [$idrecurso, $cantidad]);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error al crear ejemplares');
            }
            
            return true;
        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    /**
     * Obtener vista completa de ejemplares con información del recurso
     */
    public function obtenerEjemplaresCompletos($idrecurso = null)
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT * FROM vista_ejemplares_completos";
        $params = [];
        
        if ($idrecurso !== null) {
            $sql .= " WHERE idrecurso = ?";
            $params[] = $idrecurso;
        }
        
        $sql .= " ORDER BY codigo_ejemplar ASC";
        
        $query = $db->query($sql, $params);
        return $query->getResultArray();
    }

    /**
     * Actualizar estado de un ejemplar
     */
    public function actualizarEstadoEjemplar($idejemplar, $nuevo_estado, $observaciones = null)
    {
        $data = [
            'estado_ejemplar' => $nuevo_estado,
            'fecha_ultima_revision' => date('Y-m-d')
        ];
        
        if ($observaciones !== null) {
            $data['observaciones'] = $observaciones;
        }
        
        return $this->update($idejemplar, $data);
    }

    /**
     * Obtener estadísticas de ejemplares por recurso
     */
    public function obtenerEstadisticasPorRecurso($idrecurso)
    {
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT 
                estado_ejemplar,
                COUNT(*) as cantidad
            FROM ejemplares_fisicos 
            WHERE idrecurso = ? AND activo = TRUE
            GROUP BY estado_ejemplar
        ";
        
        $query = $db->query($sql, [$idrecurso]);
        $resultados = $query->getResultArray();
        
        // Formatear resultados
        $estadisticas = [
            'total' => 0,
            'disponible' => 0,
            'prestado' => 0,
            'dañado' => 0,
            'perdido' => 0,
            'mantenimiento' => 0
        ];
        
        foreach ($resultados as $row) {
            $estadisticas[$row['estado_ejemplar']] = (int)$row['cantidad'];
            $estadisticas['total'] += (int)$row['cantidad'];
        }
        
        return $estadisticas;
    }

    /**
     * Buscar ejemplares por código o título
     */
    public function buscarEjemplares($termino)
    {
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT * FROM vista_ejemplares_completos 
            WHERE codigo_ejemplar LIKE ? 
            OR titulo LIKE ?
            ORDER BY codigo_ejemplar ASC
        ";
        
        $termino_busqueda = "%{$termino}%";
        $query = $db->query($sql, [$termino_busqueda, $termino_busqueda]);
        
        return $query->getResultArray();
    }

    /**
     * Eliminar ejemplar (soft delete)
     */
    public function eliminarEjemplar($idejemplar)
    {
        return $this->update($idejemplar, ['activo' => false]);
    }

    /**
     * Restaurar ejemplar eliminado
     */
    public function restaurarEjemplar($idejemplar)
    {
        return $this->update($idejemplar, ['activo' => true]);
    }
}
