<?php

namespace App\Models;

use CodeIgniter\Model;

class RecursoModel extends Model
{
    protected $table            = 'recursos';
    protected $primaryKey       = 'idrecurso';

    protected $allowedFields    = [
        'titulo',
        'anio',
        'numpaginas',
        'encuadernacion',
        'isbn',
        'numedicion',
        'rutaportada',
        'estado',
        'stock',
        'urlLibro',
        'idsubcategoria',
        'ideditorial',
        'idtiporecurso'
    ];

    public function buscarRecursos($query)
    {
        return $this->select('recursos.*, autores.nomautor, subcategorias.subcategoria')
            ->join('detautores', 'detautores.idrecurso = recursos.idrecurso', 'left')
            ->join('autores', 'autores.idautor = detautores.idautor', 'left')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            // Si quieres mostrar la categoría principal, descomenta la siguiente línea y ajusta el select:
            // ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->groupStart()
                ->like('recursos.titulo', $query)
                ->orLike('autores.nomautor', $query)
                ->orLike('subcategorias.subcategoria', $query)
            ->groupEnd()
            ->findAll();
    }
}
