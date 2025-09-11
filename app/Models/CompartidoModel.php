<?php

namespace App\Models;

use CodeIgniter\Model;

class CompartidoModel extends Model
{
    protected $table = 'compartidos';
    protected $primaryKey = 'idcompartido';
    protected $allowedFields = ['idusuario', 'idrecurso'];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todos los compartidos de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }
}