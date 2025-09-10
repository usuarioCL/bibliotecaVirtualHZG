<?php

namespace App\Models;

use CodeIgniter\Model;

class GrupoModel extends Model
{
    protected $table      = 'grupos';
    protected $primaryKey = 'idgrupo';
    protected $allowedFields = [
        'aniolectivo',
        'grado',
        'seccion',
        'nivel'
    ];

    /**
     * Obtiene todos los grupos activos del año lectivo actual
     * @param int|null $anio
     * @return array
     */
    public function getGruposActivos($anio = null)
    {
        if ($anio === null) {
            $anio = date('Y');
        }
        
        return $this->where('aniolectivo', $anio)->findAll();
    }

    /**
     * Obtiene un grupo específico por ID
     * @param int $idgrupo
     * @return array|null
     */
    public function getGrupoCompleto($idgrupo)
    {
        return $this->find($idgrupo);
    }

    /**
     * Obtiene grupos por nivel educativo
     * @param string $nivel
     * @param int|null $anio
     * @return array
     */
    public function getGruposPorNivel($nivel, $anio = null)
    {
        if ($anio === null) {
            $anio = date('Y');
        }
        
        return $this->where([
            'nivel' => $nivel,
            'aniolectivo' => $anio
        ])->findAll();
    }
}