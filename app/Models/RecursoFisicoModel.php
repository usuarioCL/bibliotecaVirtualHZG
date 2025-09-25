<?php

namespace App\Models;

use CodeIgniter\Model;

class RecursoFisicoModel extends Model
{
    protected $table = 'recursos_fisicos';
    protected $primaryKey = 'idrecurso';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'idrecurso',
        'portada',
        'encuadernacion'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'idrecurso' => 'required|integer',
        'encuadernacion' => 'max_length[50]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Obtener recurso físico con información completa
     */
    public function obtenerRecursoFisicoCompleto($idrecurso)
    {
        return $this->select('
            rf.*,
            r.titulo,
            r.subtitulo,
            r.anio,
            r.numpaginas,
            r.isbn,
            r.numedicion,
            r.estado,
            r.stock,
            r.nivel,
            e.editorial,
            c.categoria,
            s.subcategoria,
            t.tiporecurso
        ')
        ->from('recursos_fisicos rf')
        ->join('recursos r', 'rf.idrecurso = r.idrecurso')
        ->join('editoriales e', 'r.ideditorial = e.ideditorial', 'left')
        ->join('subcategorias s', 'r.idsubcategoria = s.idsubcategoria', 'left')
        ->join('categorias c', 's.idcategoria = c.idcategoria', 'left')
        ->join('tiporecursos t', 'r.idtiporecurso = t.idtiporecurso', 'left')
        ->where('rf.idrecurso', $idrecurso)
        ->first();
    }
}
