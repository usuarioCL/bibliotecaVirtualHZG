<?php

namespace App\Models;

use CodeIgniter\Model;

class SubcategoriaModel extends Model
{
    protected $table = 'subcategorias';
    protected $primaryKey = 'id';
    protected $allowedFields = ['subcategoria', 'categoria_id'];
}