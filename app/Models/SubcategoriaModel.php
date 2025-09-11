<?php

namespace App\Models;

use CodeIgniter\Model;

class SubcategoriaModel extends Model
{
    protected $table = 'subcategorias';
    protected $primaryKey = 'idsubcategoria';
    protected $returnType = 'array';
    protected $allowedFields = ['subcategoria', 'idcategoria'];
}