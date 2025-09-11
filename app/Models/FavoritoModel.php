<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoritoModel extends Model
{
    protected $table = 'favoritos';
    protected $primaryKey = 'idfavorito';
    protected $allowedFields = ['idusuario', 'idrecurso'];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todos los favoritos de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }
}