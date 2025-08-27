<?php

namespace App\Models;
use CodeIgniter\Model;

class GrupoModel extends Model
{
    protected $table      = 'grupos';
    protected $primaryKey = 'idgrupo';
    protected $allowedFields = ['anioinicio', 'grado', 'seccion', 'nivel'];

    public function buscar($query)
    {
        return $this->like('grado', $query)->findAll();
    }
}