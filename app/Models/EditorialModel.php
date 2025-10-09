<?php

namespace App\Models;

use CodeIgniter\Model;

class EditorialModel extends Model
{
    protected $table = 'editoriales';
    protected $primaryKey = 'ideditorial';
    protected $allowedFields = ['editorial'];
    
    protected $validationRules = [
        'editorial' => 'required|min_length[2]|max_length[100]|is_unique[editoriales.editorial]'
    ];
    
    protected $validationMessages = [
        'editorial' => [
            'required' => 'El nombre de la editorial es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres',
            'is_unique' => 'Esta editorial ya existe'
        ]
    ];

    /**
     * Obtener editoriales con conteo de recursos
     */
    public function getEditorialesConRecursos()
    {
        return $this->select('editoriales.*, COUNT(recursos.idrecurso) as total_recursos')
                    ->join('recursos', 'recursos.ideditorial = editoriales.ideditorial', 'left')
                    ->groupBy('editoriales.ideditorial')
                    ->orderBy('editoriales.editorial', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener editoriales populares (con más recursos)
     */
    public function getEditorialesPopulares($limite = 10)
    {
        return $this->select('editoriales.editorial, COUNT(recursos.idrecurso) as total_recursos')
                    ->join('recursos', 'recursos.ideditorial = editoriales.ideditorial', 'left')
                    ->groupBy('editoriales.ideditorial')
                    ->orderBy('total_recursos', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Buscar editoriales por nombre
     */
    public function buscarEditoriales($termino)
    {
        return $this->like('editorial', $termino)
                    ->orderBy('editorial', 'ASC')
                    ->findAll();
    }

    /**
     * Verificar si una editorial puede ser eliminada
     */
    public function puedeEliminar($id)
    {
        $recursoModel = new \App\Models\RecursoModel();
        $recursosAsociados = $recursoModel->where('ideditorial', $id)->countAllResults();
        
        return [
            'puede_eliminar' => $recursosAsociados === 0,
            'recursos_asociados' => $recursosAsociados
        ];
    }

    /**
     * Obtener estadísticas de editoriales
     */
    public function getEstadisticas()
    {
        $totalEditoriales = $this->countAllResults();
        
        // Contar editoriales que tienen al menos un recurso
        $editorialesConRecursos = $this->select('editoriales.ideditorial')
                                     ->join('recursos', 'recursos.ideditorial = editoriales.ideditorial', 'inner')
                                     ->groupBy('editoriales.ideditorial')
                                     ->countAllResults();

        return [
            'total_editoriales' => $totalEditoriales,
            'editoriales_con_recursos' => $editorialesConRecursos,
            'editoriales_sin_recursos' => $totalEditoriales - $editorialesConRecursos
        ];
    }
}