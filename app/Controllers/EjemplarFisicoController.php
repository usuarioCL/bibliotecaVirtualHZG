<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\EjemplarFisicoModel;
use App\Models\RecursoFisicoModel;

class EjemplarFisicoController extends BaseController
{
    protected $ejemplarModel;
    protected $recursoFisicoModel;

    public function __construct()
    {
        $this->ejemplarModel = new EjemplarFisicoModel();
        $this->recursoFisicoModel = new RecursoFisicoModel();
    }

    /**
     * Mostrar lista de ejemplares de un recurso
     */
    public function index($idrecurso = null)
    {
        if ($idrecurso === null) {
            return redirect()->to('/recursos-fisicos')->with('error', 'ID de recurso no especificado');
        }

        $data = [
            'ejemplares' => $this->ejemplarModel->obtenerEjemplaresCompletos($idrecurso),
            'recurso' => $this->recursoFisicoModel->obtenerRecursoFisicoCompleto($idrecurso),
            'estadisticas' => $this->ejemplarModel->obtenerEstadisticasPorRecurso($idrecurso)
        ];

        // Si es una petición AJAX, devolver solo el contenido
        if ($this->request->isAJAX()) {
            return view('ejemplares_fisicos/listar_ajax', $data);
        }

        // Si es una petición normal, devolver con layouts completos
        $data['navbar'] = view('layouts/navbar');
        $data['header'] = view('layouts/header');
        $data['footer'] = view('layouts/footer');

        return view('ejemplares_fisicos/listar', $data);
    }

    /**
     * Devuelve solo el contenido del modal para mostrar ejemplares.
     */
    public function modal($idrecurso)
    {
        if ($idrecurso === null) {
            return '<div class="alert alert-danger">ID de recurso no especificado.</div>';
        }

        try {
            // Verificar si la tabla ejemplares_fisicos existe
            $db = \Config\Database::connect();
            $tables = $db->listTables();
            
            if (!in_array('ejemplares_fisicos', $tables)) {
                return '<div class="alert alert-warning">
                    <h5>⚠️ Tabla de ejemplares no encontrada</h5>
                    <p>La tabla <code>ejemplares_fisicos</code> no existe en la base de datos.</p>
                    <p>Para usar esta funcionalidad, necesitas ejecutar el archivo SQL:</p>
                    <code>app/Database/ejemplares_fisicos.sql</code>
                </div>';
            }

            $data = [
                'ejemplares' => $this->ejemplarModel->obtenerEjemplaresCompletos($idrecurso),
                'recurso' => $this->recursoFisicoModel->obtenerRecursoFisicoCompleto($idrecurso),
                'estadisticas' => $this->ejemplarModel->obtenerEstadisticasPorRecurso($idrecurso)
            ];

            return view('ejemplares_fisicos/modal_content', $data);
        } catch (\Exception $e) {
            return '<div class="alert alert-danger">
                <h5>❌ Error al cargar los ejemplares</h5>
                <p>' . $e->getMessage() . '</p>
                <p><strong>Posibles causas:</strong></p>
                <ul>
                    <li>La tabla <code>ejemplares_fisicos</code> no existe</li>
                    <li>Los procedimientos almacenados no están creados</li>
                    <li>Error en la estructura de la base de datos</li>
                </ul>
                <p><strong>Solución:</strong> Ejecuta el archivo <code>app/Database/ejemplares_fisicos.sql</code></p>
            </div>';
        }
    }

    /**
     * Crear nuevos ejemplares para un recurso
     */
    public function crearEjemplares()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        $idrecurso = $this->request->getPost('idrecurso');
        $cantidad = $this->request->getPost('cantidad');

        // Validaciones
        if (empty($idrecurso) || empty($cantidad)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'ID de recurso y cantidad son requeridos'
            ]);
        }

        if (!is_numeric($cantidad) || $cantidad <= 0 || $cantidad > 100) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'La cantidad debe ser un número entre 1 y 100'
            ]);
        }

        try {
            $this->ejemplarModel->crearEjemplaresParaRecurso($idrecurso, $cantidad);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Se crearon {$cantidad} ejemplares exitosamente"
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear ejemplares: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Actualizar estado de un ejemplar
     */
    public function actualizarEstado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        $idejemplar = $this->request->getPost('idejemplar');
        $nuevo_estado = $this->request->getPost('estado');
        $observaciones = $this->request->getPost('observaciones');

        // Validaciones
        if (empty($idejemplar) || empty($nuevo_estado)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'ID de ejemplar y estado son requeridos'
            ]);
        }

        $estados_validos = ['disponible', 'prestado', 'dañado', 'perdido', 'mantenimiento'];
        if (!in_array($nuevo_estado, $estados_validos)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Estado no válido'
            ]);
        }

        try {
            $this->ejemplarModel->actualizarEstadoEjemplar($idejemplar, $nuevo_estado, $observaciones);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado del ejemplar actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Buscar ejemplares
     */
    public function buscar()
    {
        $termino = $this->request->getGet('q');
        
        if (empty($termino)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Término de búsqueda requerido']);
        }

        try {
            $ejemplares = $this->ejemplarModel->buscarEjemplares($termino);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $ejemplares
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener ejemplar por código
     */
    public function obtenerPorCodigo($codigo)
    {
        try {
            $ejemplar = $this->ejemplarModel->obtenerEjemplarPorCodigo($codigo);
            
            if (!$ejemplar) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ejemplar no encontrado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $ejemplar
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener ejemplar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar ejemplar (soft delete)
     */
    public function eliminar($idejemplar)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $this->ejemplarModel->eliminarEjemplar($idejemplar);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ejemplar eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar ejemplar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Restaurar ejemplar eliminado
     */
    public function restaurar($idejemplar)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        try {
            $this->ejemplarModel->restaurarEjemplar($idejemplar);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ejemplar restaurado exitosamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al restaurar ejemplar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas de ejemplares por recurso
     */
    public function estadisticas($idrecurso)
    {
        try {
            $estadisticas = $this->ejemplarModel->obtenerEstadisticasPorRecurso($idrecurso);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }
}
