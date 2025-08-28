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
}
