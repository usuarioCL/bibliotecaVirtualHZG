<?php

namespace App\Models;

use CodeIgniter\Model;

class PrestamoModel extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'idprestamo';
    protected $allowedFields = [
        'idmatricula', 'idusuario', 'idrecurso', 'fechaprestamo', 
        'fechadevolucion', 'fechahoravalidacion', 'fechahoraretorno'
    ];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todos los préstamos de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }

    /**
     * Obtener préstamos de un recurso específico
     */
    public function getPrestamosByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->findAll();
    }

    /**
     * Obtener préstamos activos de un usuario (por matrícula)
     */
    public function getPrestamosActivosByUsuario($idmatricula)
    {
        // Consulta más simple para evitar problemas
        $db = \Config\Database::connect();
        
        $sql = "SELECT p.*, r.titulo, r.anio, 
                       CONCAT(COALESCE(a.nomautor, ''), ' ', COALESCE(a.apeautor, '')) as nomautor,
                       COALESCE(rf.portada, rd.portada) as portada
                FROM prestamos p
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN detautores da ON da.idrecurso = r.idrecurso
                LEFT JOIN autores a ON a.idautor = da.idautor
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                LEFT JOIN recursos_digitales rd ON rd.idrecurso = r.idrecurso
                WHERE p.idmatricula = ? 
                AND p.fechahoraretorno IS NULL
                ORDER BY p.fechaprestamo DESC";
        
        $query = $db->query($sql, [$idmatricula]);
        return $query->getResultArray();
    }

    /**
     * Obtener historial completo de préstamos de un usuario
     */
    public function getHistorialPrestamosByUsuario($idmatricula, $limit = null)
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT p.*, r.titulo, r.anio, r.isbn,
                       CONCAT(COALESCE(a.nomautor, ''), ' ', COALESCE(a.apeautor, '')) as nomautor,
                       COALESCE(rf.portada, rd.portada) as portada
                FROM prestamos p
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN detautores da ON da.idrecurso = r.idrecurso
                LEFT JOIN autores a ON a.idautor = da.idautor
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                LEFT JOIN recursos_digitales rd ON rd.idrecurso = r.idrecurso
                WHERE p.idmatricula = ?
                ORDER BY p.fechaprestamo DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $query = $db->query($sql, [$idmatricula]);
        return $query->getResultArray();
    }

    /**
     * Obtener matrícula del usuario logueado
     */
    public function getMatriculaByUsuario($idusuario)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('matriculas m')
                     ->select('m.idmatricula')
                     ->join('usuarios u', 'u.idpersona = m.idpersona')
                     ->where('u.idusuario', $idusuario)
                     ->where('m.estadomatricula', true);
        
        $result = $builder->get()->getRow();
        return $result ? $result->idmatricula : null;
    }
}