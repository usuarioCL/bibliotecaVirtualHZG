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
        $personaModel = new \App\Models\personaModel();
        return $personaModel->find($idpersona) !== null;
    }

    /**
     * Obtener matrícula con información completa del estudiante
     */
    public function getMatriculaCompleta($idmatricula)
    {
        return $this->select('
            matriculas.*,
            personas.nombres,
            personas.apellidos, 
            personas.numerodoc,
            personas.tipodoc,
            personas.genero,
            personas.telefono,
            personas.direccion,
            personas.email,
            grupos.grado,
            grupos.seccion,
            grupos.nivel,
            grupos.aniolectivo,
            usuarios.nomuser
        ')
        ->join('personas', 'personas.idpersona = matriculas.idpersona')
        ->join('grupos', 'grupos.idgrupo = matriculas.idgrupo') 
        ->join('usuarios', 'usuarios.idpersona = personas.idpersona', 'left')
        ->where('matriculas.idmatricula', $idmatricula)
        ->first();
    }

    /**
     * Obtener todas las matrículas con información completa
     */
    public function getMatriculasCompletas()
    {
        return $this->select('
            matriculas.*,
            personas.nombres,
            personas.apellidos,
            personas.numerodoc,
            personas.tipodoc,
            personas.email,
            grupos.grado,
            grupos.seccion, 
            grupos.nivel,
            grupos.aniolectivo,
            usuarios.nomuser
        ')
        ->join('personas', 'personas.idpersona = matriculas.idpersona')
        ->join('grupos', 'grupos.idgrupo = matriculas.idgrupo')
        ->join('usuarios', 'usuarios.idpersona = personas.idpersona', 'left')
        ->orderBy('matriculas.idmatricula', 'DESC')
        ->findAll();
    }

    /**
     * Contar matrículas por estado
     */
    public function contarPorEstado()
    {
        return [
            'total' => $this->countAll(),
            'activas' => $this->where('estadomatricula', true)->countAllResults(false),
            'inactivas' => $this->where('estadomatricula', false)->countAllResults()
        ];
    }

    /**
     * Contar estudiantes por nivel educativo
     */
    public function contarPorNivel()
    {
        return $this->select('grupos.nivel, COUNT(*) as cantidad')
            ->join('grupos', 'grupos.idgrupo = matriculas.idgrupo')
            ->where('matriculas.estadomatricula', true)
            ->groupBy('grupos.nivel')
            ->findAll();
    }
}