<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AutorModel;
use App\Models\GrupoModel;
use App\Models\CategoriaModel;
use App\Models\RecursoModel;
use App\Models\SubcategoriaModel;
use App\Models\TipoRecursoModel;
use App\Models\EditorialModel;

class BuscarController extends Controller
{
    public function index()
    {
        $query = $this->request->getGet('q');
        $resultados = [];
        // Lógica para manejar la búsqueda
        $grupoModel = new GrupoModel();
        $categoriaModel = new CategoriaModel();
        $recursoModel = new RecursoModel();
        $subcategoriaModel = new SubcategoriaModel();
        $tipoRecursoModel = new TipoRecursoModel();
        $editorialModel = new EditorialModel();

        // Filtros
        $resultados['grupos'] = $grupoModel->buscar($query);
        $resultados['categorias'] = $categoriaModel->buscar($query);
        $resultados['recursos'] = $recursoModel->buscar($query);
        $resultados['subcategorias'] = $subcategoriaModel->buscar($query);
        $resultados['tipos'] = $tipoRecursoModel->buscar($query);
        $resultados['editoriales'] = $editorialModel->buscar($query);

        $datos = [
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer'),
            'resultados' => $resultados,
            'query' => $query
        ];

        return view('busqueda/index', $datos);
    }
}