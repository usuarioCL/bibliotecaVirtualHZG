<?php

namespace App\Models;
use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'idcategoria';
    protected $allowedFields = ['categoria'];

    public function buscar($query)
    {
        return $this->like('categoria', $query)->findAll();
    }
}

