<?php
namespace App\Models;
use CodeIgniter\Model;

class SubcategoriaModel extends Model
{
    protected $table = 'subcategorias';
    protected $primaryKey = 'idsubcategoria';
    protected $allowedFields = ['subcategoria', 'idcategoria'];

    public function buscar($query)
    {
        return $this->like('subcategoria', $query)->findAll();
    }
}