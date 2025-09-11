<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudModel extends Model
{
    protected $table = 'solicitud';
    protected $primaryKey = 'idsolicitud';
    protected $allowedFields = ['validado', 'idprestamo'];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todas las solicitudes relacionadas con préstamos de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        // Primero obtenemos los IDs de préstamos relacionados con el recurso
        $prestamosQuery = $this->db->table('prestamos')
            ->select('idprestamo')
            ->where('idrecurso', $idrecurso)
            ->get();
        
        $prestamos = $prestamosQuery->getResultArray();
        
        if (empty($prestamos)) {
            return 0; // No hay préstamos, por lo tanto no hay solicitudes que eliminar
        }
        
        $prestamoIds = array_column($prestamos, 'idprestamo');
        
        // Eliminar solicitudes que referencian estos préstamos
        return $this->whereIn('idprestamo', $prestamoIds)->delete();
    }

    /**
     * Eliminar solicitudes por ID de préstamo específico
     */
    public function deleteByPrestamo($idprestamo)
    {
        return $this->where('idprestamo', $idprestamo)->delete();
    }
}