<?php

namespace App\Models;

use CodeIgniter\Model;

class ComentarioModel extends Model
{
    protected $table = 'comentarios';
    protected $primaryKey = 'idcomentario';
    protected $allowedFields = ['comentario', 'idusuario', 'idrecurso', 'fechahoracomentario'];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todos los comentarios de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }
}