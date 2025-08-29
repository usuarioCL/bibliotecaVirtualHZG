<?php
namespace App\Models;
use CodeIgniter\Model;

class TiporecursoModel extends Model
{
    protected $table = 'tiporecursos';
    protected $primaryKey = 'idtiporecurso';
    protected $allowedFields = ['tiporecurso'];
}