<?php

namespace App\Models;

use CodeIgniter\Model;

class DetAutorModel extends Model
{
    protected $table = 'detautores';
    protected $primaryKey = 'iddetautor';
    protected $allowedFields = ['idautor', 'idrecurso'];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Obtener autores de un recurso específico
     */
    public function getAutoresByRecurso($idrecurso)
    {
        return $this->select('autores.idautor, autores.nomautor, autores.apeautor, autores.nacionalidad')
            ->join('autores', 'autores.idautor = detautores.idautor')
            ->where('detautores.idrecurso', $idrecurso)
            ->findAll();
    }

    /**
     * Obtener recursos de un autor específico
     */
    public function getRecursosByAutor($idautor)
    {
        return $this->select('recursos.idrecurso, recursos.titulo, recursos.anio')
            ->join('recursos', 'recursos.idrecurso = detautores.idrecurso')
            ->where('detautores.idautor', $idautor)
            ->findAll();
    }

    /**
     * Eliminar todas las relaciones de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }

    /**
     * Verificar si ya existe la relación autor-recurso
     */
    public function existeRelacion($idautor, $idrecurso)
    {
        return $this->where('idautor', $idautor)
                    ->where('idrecurso', $idrecurso)
                    ->first() !== null;
    }
}