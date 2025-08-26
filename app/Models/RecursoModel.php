<?php
namespace App\Models;
use CodeIgniter\Model;

class RecursoModel extends Model
{
    protected $table = 'recursos';
    protected $primaryKey = 'idrecurso';
    protected $allowedFields = [
        'titulo', 'año', 'numeroPaginas', 'encuadernacion', 'isbn', 'numeroEdicion',
        'rutaPortada', 'rutaIndice', 'estado', 'stock', 'idsubcategoria', 'ideditorial',
        'idtiporecurso', 'urlLibro'
    ];

    public function buscar($query)
    {
        return $this->like('titulo', $query)
                    ->orLike('isbn', $query)
                    ->findAll();
    }
}