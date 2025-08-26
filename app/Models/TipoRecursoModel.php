<?php
namespace App\Models;
use CodeIgniter\Model;

class TipoRecursoModel extends Model
{
    protected $table = 'tiporecursos';
    protected $primaryKey = 'idtiporecurso';
    protected $allowedFields = ['tiporecurso'];

    public function buscar($query)
    {
        return $this->like('tiporecurso', $query)->findAll();
    }
}