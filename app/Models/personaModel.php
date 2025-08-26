<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table      = 'personas';
    protected $primaryKey = 'idpersona';
    protected $allowedFields = [
        'apellido',
        'nombre',
        'tipodoc',
        'numerodoc',
        'telefono',
        'direccion',
        'email',
        'genero'
    ];
}