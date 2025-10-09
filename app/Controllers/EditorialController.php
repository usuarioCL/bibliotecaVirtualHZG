<?php

namespace App\Controllers;

use App\Models\EditorialModel;
use App\Models\RecursoModel;
use CodeIgniter\HTTP\ResponseInterface;

class EditorialController extends BaseController
{
    protected $editorialModel;
    protected $recursoModel;

    public function __construct()
    {
        $this->editorialModel = new EditorialModel();
        $this->recursoModel = new RecursoModel();
    }

    /**
     * Listar todas las editoriales
     */
    public function index()
    {
        // Verificar si es petición AJAX
        if ($this->request->isAJAX()) {
            return $this->getEditorialesAjax();
        }

        $data = [
            'title' => 'Gestión de Editoriales',
            'editoriales' => $this->editorialModel->orderBy('editorial', 'ASC')->findAll()
        ];

        return view('Administrador/editoriales/index', $data);
    }

    /**
     * Vista AJAX para el panel de administración
     */
    public function ajaxIndex()
    {
        $data = [
            'title' => 'Gestión de Editoriales'
        ];

        return view('Administrador/editoriales/ajax_index', $data);
    }


    /**
     * Vista AJAX de detalles para el panel de administración
     */
    public function ajaxDetalles($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin')->with('error', 'ID de editorial no válido');
        }

        $editorial = $this->editorialModel->find($id);
        if (!$editorial) {
            return redirect()->to('/admin')->with('error', 'Editorial no encontrada');
        }

        // Obtener recursos asociados
        $recursos = $this->recursoModel
            ->select('recursos.*, subcategorias.subcategoria, categorias.categoria')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->where('recursos.ideditorial', $id)
            ->orderBy('recursos.titulo', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Detalles de Editorial',
            'editorial' => $editorial,
            'recursos' => $recursos
        ];

        return view('Administrador/editoriales/ajax_detalles', $data);
    }

    /**
     * Obtener editoriales via AJAX
     */
    public function getEditorialesAjax()
    {
        $editoriales = $this->editorialModel
            ->select('editoriales.*, COUNT(recursos.idrecurso) as total_recursos')
            ->join('recursos', 'recursos.ideditorial = editoriales.ideditorial', 'left')
            ->groupBy('editoriales.ideditorial')
            ->orderBy('editoriales.editorial', 'ASC')
            ->findAll();

        // Log para depuración
        log_message('debug', 'Editoriales obtenidas: ' . json_encode($editoriales));

        return $this->response->setJSON([
            'success' => true,
            'data' => $editoriales
        ]);
    }

    /**
     * Crear nueva editorial
     */
    public function crear()
    {
        if ($this->request->isAJAX()) {
            return $this->crearEditorialAjax();
        }

        $data = [
            'title' => 'Nueva Editorial',
            'editorial' => null
        ];

        return view('Administrador/editoriales/formulario', $data);
    }

    /**
     * Crear editorial via AJAX
     */
    public function crearEditorialAjax()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'editorial' => 'required|min_length[2]|max_length[100]|is_unique[editoriales.editorial]'
        ], [
            'editorial' => [
                'required' => 'El nombre de la editorial es obligatorio',
                'min_length' => 'El nombre debe tener al menos 2 caracteres',
                'max_length' => 'El nombre no puede exceder 100 caracteres',
                'is_unique' => 'Esta editorial ya existe'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validation->getErrors()
            ]);
        }

        $data = [
            'editorial' => trim($this->request->getPost('editorial'))
        ];

        if ($this->editorialModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Editorial creada exitosamente',
                'data' => [
                    'ideditorial' => $this->editorialModel->getInsertID(),
                    'editorial' => $data['editorial']
                ]
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al crear la editorial'
        ]);
    }

    /**
     * Editar editorial existente
     */
    public function editar($id = null)
    {
        if (!$id) {
            return redirect()->to('/editoriales')->with('error', 'ID de editorial no válido');
        }

        $editorial = $this->editorialModel->find($id);
        if (!$editorial) {
            return redirect()->to('/editoriales')->with('error', 'Editorial no encontrada');
        }

        if ($this->request->isAJAX()) {
            return $this->editarEditorialAjax($id);
        }

        $data = [
            'title' => 'Editar Editorial',
            'editorial' => $editorial
        ];

        return view('Administrador/editoriales/formulario', $data);
    }

    /**
     * Editar editorial via AJAX
     */
    public function editarEditorialAjax($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'editorial' => "required|min_length[2]|max_length[100]|is_unique[editoriales.editorial,ideditorial,{$id}]"
        ], [
            'editorial' => [
                'required' => 'El nombre de la editorial es obligatorio',
                'min_length' => 'El nombre debe tener al menos 2 caracteres',
                'max_length' => 'El nombre no puede exceder 100 caracteres',
                'is_unique' => 'Esta editorial ya existe'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validation->getErrors()
            ]);
        }

        $data = [
            'editorial' => trim($this->request->getPost('editorial'))
        ];

        if ($this->editorialModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Editorial actualizada exitosamente'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al actualizar la editorial'
        ]);
    }

    /**
     * Eliminar editorial
     */
    public function eliminar($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de editorial no válido'
            ]);
        }

        // Verificar si la editorial tiene recursos asociados
        $recursosAsociados = $this->recursoModel->where('ideditorial', $id)->countAllResults();
        
        if ($recursosAsociados > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "No se puede eliminar la editorial porque tiene {$recursosAsociados} recurso(s) asociado(s). Primero elimine o reasigne los recursos."
            ]);
        }

        if ($this->editorialModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Editorial eliminada exitosamente'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al eliminar la editorial'
        ]);
    }

    /**
     * Ver detalles de editorial con recursos asociados
     */
    public function detalles($id = null)
    {
        if (!$id) {
            return redirect()->to('/editoriales')->with('error', 'ID de editorial no válido');
        }

        $editorial = $this->editorialModel->find($id);
        if (!$editorial) {
            return redirect()->to('/editoriales')->with('error', 'Editorial no encontrada');
        }

        // Obtener recursos asociados
        $recursos = $this->recursoModel
            ->select('recursos.*, subcategorias.subcategoria, categorias.categoria')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->where('recursos.ideditorial', $id)
            ->orderBy('recursos.titulo', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Detalles de Editorial',
            'editorial' => $editorial,
            'recursos' => $recursos
        ];

        return view('Administrador/editoriales/detalles', $data);
    }

    /**
     * Buscar editoriales
     */
    public function buscar()
    {
        $query = $this->request->getGet('q');
        
        if (empty($query)) {
            return $this->response->setJSON([
                'success' => true,
                'data' => []
            ]);
        }

        $editoriales = $this->editorialModel
            ->like('editorial', $query)
            ->orderBy('editorial', 'ASC')
            ->findAll(10); // Limitar a 10 resultados

        return $this->response->setJSON([
            'success' => true,
            'data' => $editoriales
        ]);
    }

    /**
     * Obtener estadísticas de editoriales
     */
    public function estadisticas()
    {
        try {
            // Obtener estadísticas básicas usando el modelo
            $estadisticas = $this->editorialModel->getEstadisticas();
            
            // Obtener editoriales populares
            $editorialesPopulares = $this->editorialModel->getEditorialesPopulares(5);

            // Log para depuración
            log_message('debug', 'Estadísticas obtenidas: ' . json_encode($estadisticas));

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'total_editoriales' => $estadisticas['total_editoriales'],
                    'editoriales_con_recursos' => $estadisticas['editoriales_con_recursos'],
                    'editoriales_sin_recursos' => $estadisticas['editoriales_sin_recursos'],
                    'editoriales_populares' => $editorialesPopulares
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en estadísticas de editoriales: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }
}
