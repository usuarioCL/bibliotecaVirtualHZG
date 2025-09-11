<?php

namespace App\Models;

use CodeIgniter\Model;

class PrestamoModel extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'idprestamo';
    protected $allowedFields = [
        'idmatricula', 'idusuario', 'idrecurso', 'fechaprestamo', 
        'fechadevolucion', 'fechahoravalidacion', 'fechahoraretorno'
    ];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todos los préstamos de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }

    /**
     * Obtener préstamos de un recurso específico
     */
    public function getPrestamosByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->findAll();
    }
}