<?php

namespace App\Models;
use CodeIgniter\Model;

class DetAutoresModel extends Model
{
    protected $table = 'detautores';
    protected $primaryKey = 'iddetautor';
    protected $allowedFields = ['idrecurso', 'idautor'];
}