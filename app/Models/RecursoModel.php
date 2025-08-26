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

    public function buscarPorTituloAutorOCategoria($query)
    {
        $builder = $this->db->table($this->table);
        $builder->select('recursos.*, categorias.categoria, autores.nomautor, autores.apeautor');
        $builder->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left');
        $builder->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left');
        $builder->join('autores', 'autores.idautor = recursos.idautor', 'left');
        $builder->groupStart()
            ->like('recursos.titulo', $query)
            ->orLike('autores.nomautor', $query)
            ->orLike('autores.apeautor', $query)
            ->orLike('categorias.categoria', $query)
        ->groupEnd();
        return $builder->get()->getResultArray();
    }
}