<?php

namespace App\Models;

use CodeIgniter\Model;

class MatriculaModel extends Model
{
    protected $table      = 'matriculas';
    protected $primaryKey = 'idmatricula';
    protected $allowedFields = [
        'idgrupo',
        'idpersona',
        'fechamatricula',
        'estadomatricula'
    ];

    /**
     * Verifica si una persona está matriculada y activa
     * @param int $idpersona
     * @return bool
     */
    public function personaEstaMatriculada($idpersona)
    {
        $matricula = $this->where([
            'idpersona' => $idpersona,
            'estadomatricula' => true
        ])->first();
        
        return $matricula !== null;
    }

    /**
     * Obtiene la matrícula activa de una persona
     * @param int $idpersona
     * @return array|null
     */
    public function getMatriculaActiva($idpersona)
    {
        return $this->select('matriculas.*, grupos.nivel, grupos.grado, grupos.seccion, grupos.aniolectivo')
                    ->join('grupos', 'grupos.idgrupo = matriculas.idgrupo')
                    ->where([
                        'matriculas.idpersona' => $idpersona,
                        'matriculas.estadomatricula' => true
                    ])
                    ->first();
    }

    /**
     * Verifica si una persona es docente (puede estar matriculada como docente)
     * @param int $idpersona
     * @return bool
     */
    public function esDocente($idpersona)
    {
        // Los docentes pueden tener matrículas especiales o ser identificados de otra manera
        // Por ahora, asumimos que los docentes están en la tabla personas y pueden crear usuarios tipo 'docente'
        $personaModel = new \App\Models\PersonaModel();
        return $personaModel->find($idpersona) !== null;
    }
}