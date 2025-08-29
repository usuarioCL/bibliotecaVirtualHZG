<?php 
namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\RecursoModel;


class RecursoController extends Controller
{
    // Lista de recursos
    public function index(): string
    {
        $recurso = new RecursoModel();

        $datos['recursos'] = $recurso->orderBy('idrecurso', 'ASC')->paginate(10, 'recursos');
        $datos['pager']    = $recurso->pager;

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('recursos/listar', $datos);
    }

    // Formulario para crear
    public function crear(): string
    {
        $recursoModel = new RecursoModel();

        $query = $recursoModel->query("SHOW COLUMNS FROM recursos LIKE 'estado'");
        $row = $query->getRow();
        $estados = str_replace(["enum('", "')"], "", $row->Type);
        $datos['estados'] = explode("','", $estados);

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('recursos/crear', $datos);
    }
    // Guardar datos del Formulario
    public function guardar()
    {
        $recursoModel = new RecursoModel();

        $datos = [
            'titulo'         => $this->request->getVar('titulo'),
            'anio'           => $this->request->getVar('anio'),
            'numpaginas'     => $this->request->getVar('numpaginas'),
            'encuadernacion' => $this->request->getVar('encuadernacion'),
            'isbn'           => $this->request->getVar('isbn'),
            'numedicion'     => $this->request->getVar('numedicion'),
            'estado'         => $this->request->getVar('estado'),
            'stock'          => $this->request->getVar('stock'),
        ];

        $recursoModel->insert($datos);

        return $this->response->redirect(base_url('recursos'));
    }
    // Formulario para editar
    public function editar($idrecurso = null)
    {
        $recursoModel = new RecursoModel();
        $datos['recurso'] = $recursoModel->find($idrecurso);

        $query = $recursoModel->query("SHOW COLUMNS FROM recursos LIKE 'estado'");
        $row = $query->getRow();
        $estados = str_replace(["enum('", "')"], "", $row->Type);
        $datos['estados'] = explode("','", $estados);

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('recursos/editar', $datos);
    }
    // Actualizar datos
    public function actualizar($idrecurso)
    {
        $recursoModel = new RecursoModel();

        $datos = [
            'titulo'        => $this->request->getVar('titulo'),
            'anio'          => $this->request->getVar('anio'),
            'numpaginas'    => $this->request->getVar('numpaginas'),
            'encuadernacion'=> $this->request->getVar('encuadernacion'),
            'isbn'          => $this->request->getVar('isbn'),
            'numedicion'    => $this->request->getVar('numedicion'),
            'estado'        => $this->request->getVar('estado'),
            'stock'         => $this->request->getVar('stock'),
        ];

        $recursoModel->update($idrecurso, $datos);
        
        return redirect()->to(base_url('recursos'));
    }

    public function eliminar($idrecurso = null)
    {
        $recursoModel = new RecursoModel();
        $recursoModel->delete($idrecurso);
        return $this->response->redirect(base_url('recursos'));
    }

    public function buscarRecursos()
    {
        $recursoModel = new RecursoModel();
        $query = $this->request->getVar('query');

        $datos['recursos'] = $recursoModel->buscarRecursos($query);
         $datos['categorias'] = model('CategoriaModel')->findAll();
        $datos['subcategorias'] = model('SubcategoriaModel')->findAll();
        $datos['editoriales'] = model('EditorialModel')->findAll();
        $datos['tiposrecurso'] = model('TiporecursoModel')->findAll();
        $datos['autores'] = model('AutorModel')->findAll();
        $datos['filtros'] = [];

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('recursos/listarBuscados', $datos);
    }

    public function filtrosBusqueda()
    {
        $recursoModel = new RecursoModel();
        $filtros = $this->request->getVar();

        $datos['recursos'] = $recursoModel->filtrosBusqueda($filtros);

        if ($this->request->isAJAX()) {
            // Devolver solo el HTML de la lista de resultados
            return view('recursos/resultadosBusqueda', $datos);
        }

        $datos['categorias'] = model('CategoriaModel')->findAll();
        $datos['subcategorias'] = model('SubcategoriaModel')->findAll();
        $datos['editoriales'] = model('EditorialModel')->findAll();
        $datos['tiposrecurso'] = model('TiporecursoModel')->findAll();
        $datos['autores'] = model('AutorModel')->findAll();
        $datos['filtros'] = $filtros;

        $datos['navbar'] = view('layouts/navbar');  
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('recursos/listarBuscados', $datos);
    }
}