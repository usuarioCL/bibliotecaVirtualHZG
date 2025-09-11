<?php

namespace App\Models;

use CodeIgniter\Model;

class UbicacionModel extends Model
{
    protected $table = 'ubicaciones';
    protected $primaryKey = 'idubicacion';
    protected $allowedFields = ['ubicacion', 'idrecurso'];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todas las ubicaciones de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }

    /**
     * Obtener ubicaciones de un recurso específico
     */
    public function getUbicacionesByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->findAll();
    }
}