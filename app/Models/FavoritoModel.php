<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoritoModel extends Model
{
    protected $table = 'favoritos';
    protected $primaryKey = 'idfavorito';
    protected $allowedFields = ['idusuario', 'idrecurso'];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todos los favoritos de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }

    /**
     * Obtener favoritos de un usuario con información completa del recurso
     */
    public function getFavoritosByUsuario($idusuario)
    {
        return $this->distinct()
            ->select([
                'f.idfavorito',
                'f.idusuario',
                'f.idrecurso',
                'r.titulo',
                'r.anio', 
                'r.isbn',
                'r.estado',
                'GROUP_CONCAT(DISTINCT CONCAT(COALESCE(a.nomautor, ""), " ", COALESCE(a.apeautor, "")) SEPARATOR ", ") as nomautor',
                'COALESCE(rf.portada, rd.portada) as portada',
                'c.categoria',
                'sc.subcategoria', 
                'e.editorial'
            ])
            ->from('favoritos f')
            ->join('recursos r', 'r.idrecurso = f.idrecurso')
            ->join('detautores da', 'da.idrecurso = r.idrecurso', 'left')
            ->join('autores a', 'a.idautor = da.idautor', 'left')
            ->join('recursos_fisicos rf', 'rf.idrecurso = r.idrecurso', 'left')
            ->join('recursos_digitales rd', 'rd.idrecurso = r.idrecurso', 'left')
            ->join('subcategorias sc', 'sc.idsubcategoria = r.idsubcategoria', 'left')
            ->join('categorias c', 'c.idcategoria = sc.idcategoria', 'left')
            ->join('editoriales e', 'e.ideditorial = r.ideditorial', 'left')
            ->where('f.idusuario', $idusuario)
            ->groupBy([
                'f.idfavorito',
                'f.idusuario', 
                'f.idrecurso',
                'r.titulo',
                'r.anio',
                'r.isbn', 
                'r.estado',
                'rf.portada',
                'rd.portada',
                'c.categoria',
                'sc.subcategoria',
                'e.editorial'
            ])
            ->orderBy('f.idfavorito', 'DESC')
            ->findAll();
    }

    /**
     * Verificar si un recurso está en favoritos del usuario
     */
    public function esFavorito($idusuario, $idrecurso)
    {
        return $this->where('idusuario', $idusuario)
                   ->where('idrecurso', $idrecurso)
                   ->first() !== null;
    }

    /**
     * Agregar recurso a favoritos
     */
    public function agregarFavorito($idusuario, $idrecurso)
    {
        // Verificar si ya existe
        if ($this->esFavorito($idusuario, $idrecurso)) {
            return false; // Ya existe
        }

        return $this->insert([
            'idusuario' => $idusuario,
            'idrecurso' => $idrecurso
        ]);
    }

    /**
     * Quitar recurso de favoritos
     */
    public function quitarFavorito($idusuario, $idrecurso)
    {
        return $this->where('idusuario', $idusuario)
                   ->where('idrecurso', $idrecurso)
                   ->delete();
    }

    /**
     * Alternar estado de favorito (agregar si no existe, quitar si existe)
     */
    public function toggleFavorito($idusuario, $idrecurso)
    {
        if ($this->esFavorito($idusuario, $idrecurso)) {
            $this->quitarFavorito($idusuario, $idrecurso);
            return false; // Quitado
        } else {
            $this->agregarFavorito($idusuario, $idrecurso);
            return true; // Agregado
        }
    }

    /**
     * Contar favoritos de un usuario
     */
    public function contarFavoritosByUsuario($idusuario)
    {
        return $this->where('idusuario', $idusuario)->countAllResults();
    }
}