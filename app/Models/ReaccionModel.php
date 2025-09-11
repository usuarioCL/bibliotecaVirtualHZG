<?php

namespace App\Models;

use CodeIgniter\Model;

class ReaccionModel extends Model
{
    protected $table = 'reacciones';
    protected $primaryKey = 'idreaccion';
    protected $allowedFields = ['tiporeaccion', 'idusuario', 'idrecurso'];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todas las reacciones de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }
}