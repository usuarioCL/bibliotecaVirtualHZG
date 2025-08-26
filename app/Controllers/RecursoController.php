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

class RecursoController extends Controller
{
    public function buscar()
    {
        $query = $this->request->getGet('q');
        $recursoModel = new RecursoModel();
        $recursos = $recursoModel->buscarPorTituloAutorOCategoria($query);

        $datos = [
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer'),
            'recursos' => $recursos,
            'query' => $query
        ];

        return view('recurso/index', $datos);
    }
}