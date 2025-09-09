<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RecursoModel;
use App\Models\DetAutorModel;

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

    public function crear(): string
    {
        $recursoModel = new RecursoModel();
        $autorModel = new \App\Models\AutorModel();

        // Obtener valores ENUM de estado
        $query = $recursoModel->query("SHOW COLUMNS FROM recursos LIKE 'estado'");
        $row = $query->getRow();
        $estados = str_replace(["enum('", "')"], "", $row->Type);
        $datos['estados'] = explode("','", $estados);

        // Obtener valores ENUM de nivel
        $query = $recursoModel->query("SHOW COLUMNS FROM recursos LIKE 'nivel'");
        $row = $query->getRow();
        $niveles = str_replace(["enum('", "')"], "", $row->Type);
        $datos['niveles'] = explode("','", $niveles);

        // Obtener datos para los selects
        $datos['autores'] = $autorModel->findAll();
        $datos['categorias'] = model('CategoriaModel')->findAll();
        $datos['subcategorias'] = model('SubcategoriaModel')->findAll();
        $datos['editoriales'] = model('EditorialModel')->findAll();
        $datos['tiposrecurso'] = model('TiporecursoModel')->findAll();

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('recursos/crear', $datos);
    }
    
    // Guardar datos del Formulario
    public function guardar()
    {
        $recursoModel = new RecursoModel();
        $detAutorModel = new DetAutorModel();

        // Datos para la tabla recursos (SIN idautor)
        $datosRecurso = [
            'titulo'         => $this->request->getVar('titulo'),
            'anio'           => $this->request->getVar('anio'),
            'numpaginas'     => $this->request->getVar('numpaginas'),
            'encuadernacion' => $this->request->getVar('encuadernacion'),
            'isbn'           => $this->request->getVar('isbn'),
            'numedicion'     => $this->request->getVar('numedicion'),
            'rutaportada'    => $this->request->getVar('rutaportada'),
            'estado'         => $this->request->getVar('estado'),
            'stock'          => $this->request->getVar('stock'),
            'urlLibro'       => $this->request->getVar('urlLibro'),
            'nivel'          => $this->request->getVar('nivel'),
            'idsubcategoria' => $this->request->getVar('idsubcategoria'),
            'ideditorial'    => $this->request->getVar('ideditorial'),
            'idtiporecurso'  => $this->request->getVar('idtiporecurso')
        ];

        // 1. Insertar el recurso
        $idRecurso = $recursoModel->insert($datosRecurso);
        
        // 2. Insertar la relación autor-recurso en detautores
        $idAutor = $this->request->getVar('idautor');
        if ($idAutor && $idRecurso) {
            $detAutorModel->insert([
                'idautor' => $idAutor,
                'idrecurso' => $idRecurso
            ]);
        }

        return $this->response->redirect(base_url('recursos'));
    }

    // Formulario para editar
    public function editar($idrecurso = null)
    {
        $recursoModel = new RecursoModel();
        $detAutorModel = new DetAutorModel();
        $datos['recurso'] = $recursoModel->find($idrecurso);

        if (!$datos['recurso']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Recurso no encontrado');
        }

        // Obtener el autor actual del recurso
        $autorActual = $detAutorModel->getAutoresByRecurso($idrecurso);
        $datos['autorActual'] = !empty($autorActual) ? $autorActual[0]['idautor'] : null;

        // Obtener valores ENUM
        $query = $recursoModel->query("SHOW COLUMNS FROM recursos LIKE 'estado'");
        $row = $query->getRow();
        $estados = str_replace(["enum('", "')"], "", $row->Type);
        $datos['estados'] = explode("','", $estados);

        $query = $recursoModel->query("SHOW COLUMNS FROM recursos LIKE 'nivel'");
        $row = $query->getRow();
        $niveles = str_replace(["enum('", "')"], "", $row->Type);
        $datos['niveles'] = explode("','", $niveles);

        // Obtener datos para los selects
        $datos['autores'] = model('AutorModel')->findAll();
        $datos['categorias'] = model('CategoriaModel')->findAll();
        $datos['subcategorias'] = model('SubcategoriaModel')->findAll();
        $datos['editoriales'] = model('EditorialModel')->findAll();
        $datos['tiposrecurso'] = model('TiporecursoModel')->findAll();

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('recursos/editar', $datos);
    }

    // Actualizar datos
    public function actualizar($idrecurso)
    {
        $recursoModel = new RecursoModel();
        $detAutorModel = new DetAutorModel();

        // Datos para actualizar en recursos
        $datosRecurso = [
            'titulo'         => $this->request->getVar('titulo'),
            'anio'           => $this->request->getVar('anio'),
            'numpaginas'     => $this->request->getVar('numpaginas'),
            'encuadernacion' => $this->request->getVar('encuadernacion'),
            'isbn'           => $this->request->getVar('isbn'),
            'numedicion'     => $this->request->getVar('numedicion'),
            'rutaportada'    => $this->request->getVar('rutaportada'),
            'estado'         => $this->request->getVar('estado'),
            'stock'          => $this->request->getVar('stock'),
            'urlLibro'       => $this->request->getVar('urlLibro'),
            'nivel'          => $this->request->getVar('nivel'),
            'idsubcategoria' => $this->request->getVar('idsubcategoria'),
            'ideditorial'    => $this->request->getVar('ideditorial'),
            'idtiporecurso'  => $this->request->getVar('idtiporecurso')
        ];

        // 1. Actualizar el recurso
        $recursoModel->update($idrecurso, $datosRecurso);
        
        // 2. Actualizar la relación autor-recurso
        $idAutor = $this->request->getVar('idautor');
        if ($idAutor) {
            // Eliminar relaciones anteriores
            $detAutorModel->deleteByRecurso($idrecurso);
            
            // Insertar nueva relación
            $detAutorModel->insert([
                'idautor' => $idAutor,
                'idrecurso' => $idrecurso
            ]);
        }
        
        return redirect()->to(base_url('recursos'));
    }

    public function eliminar($idrecurso = null)
    {
        $recursoModel = new RecursoModel();
        $detAutorModel = new DetAutorModel();
        
        // Eliminar primero las relaciones en detautores
        $detAutorModel->deleteByRecurso($idrecurso);
        
        // Luego eliminar el recurso
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

    public function detalles($id)
    {
        $recursoModel = new RecursoModel();
        $recurso = $recursoModel->obtenerDetallesCompletos($id);
        
        if (!$recurso) {
            return '<div class="alert alert-danger">Libro no encontrado.</div>';
        }

        $datos['recurso'] = $recurso;
        
        if ($this->request->isAJAX()) {
            return view('recursos/detallesModal', $datos);
        }
        
        return view('recursos/detalles', $datos);
    }
}