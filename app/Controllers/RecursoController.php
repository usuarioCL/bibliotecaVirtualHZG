<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RecursoModel;
use App\Models\DetAutorModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class RecursoController extends Controller
{
    // Lista de recursos
    public function index(): string
    {
        $recurso = new RecursoModel();
        $autorModel = new \App\Models\AutorModel();

        $datos['recursos'] = $recurso->orderBy('idrecurso', 'ASC')->paginate(10, 'recursos');
        $datos['pager']    = $recurso->pager;

        // Agregar datos necesarios para el modal de crear
        // Obtener valores ENUM de estado
        $query = $recurso->query("SHOW COLUMNS FROM recursos LIKE 'estado'");
        $row = $query->getRow();
        $estados = str_replace(["enum('", "')"], "", $row->Type);
        $datos['estados'] = explode("','", $estados);

        // Obtener valores ENUM de nivel
        $query = $recurso->query("SHOW COLUMNS FROM recursos LIKE 'nivel'");
        $row = $query->getRow();
        $niveles = str_replace(["enum('", "')"], "", $row->Type);
        $datos['niveles'] = explode("','", $niveles);

        // Obtener datos para los selects del modal
        $datos['autores'] = $autorModel->findAll();
        $datos['categorias'] = model('CategoriaModel')->findAll();
        $datos['subcategorias'] = model('SubcategoriaModel')->findAll();
        $datos['editoriales'] = model('EditorialModel')->findAll();
        $datos['tiposrecurso'] = model('TiporecursoModel')->findAll();

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


        // Si la petición es para modal, devolver solo la vista sin layouts
        if ($this->request->getGet('modal') === 'true') {
            return view('recursos/crear', $datos);
        }


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
            // Nota: 'rutaportada' si se maneja como archivo debería procesarse similar a PDF. Por ahora se deja tal cual llega.
            'rutaportada'    => $this->request->getVar('rutaportada'),
            'estado'         => $this->request->getVar('estado'),
            'stock'          => $this->request->getVar('stock'),
            // urlLibro puede ser actualizado luego si suben PDF
            'urlLibro'       => $this->request->getVar('urlLibro'),
            'nivel'          => $this->request->getVar('nivel'),
            'idsubcategoria' => $this->request->getVar('idsubcategoria'),
            'ideditorial'    => $this->request->getVar('ideditorial'),
            'idtiporecurso'  => $this->request->getVar('idtiporecurso')
        ];

        // 1. Insertar el recurso
        $idRecurso = $recursoModel->insert($datosRecurso);
        
        // 1.1 Manejo de PDF SOLO si el tipo de recurso es digital
        try {
            $idTipo = $this->request->getVar('idtiporecurso');
            $esDigital = false;
            if ($idTipo) {
                $tipo = model('TiporecursoModel')->find($idTipo);
                if ($tipo && isset($tipo['tiporecurso']) && stripos($tipo['tiporecurso'], 'digital') !== false) {
                    $esDigital = true;
                }
            }

            if ($idRecurso && $esDigital) {
                $pdfFile = $this->request->getFile('archivo_pdf');
                if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
                    helper('text');
                    $carpetaRecurso = FCPATH . 'libros' . DIRECTORY_SEPARATOR . $idRecurso . DIRECTORY_SEPARATOR;
                    if (!is_dir($carpetaRecurso)) {
                        @mkdir($carpetaRecurso, 0775, true);
                    }
                    $nombreBase = url_title($datosRecurso['titulo'] ?: 'libro', '-', true);
                    $nombreArchivo = $nombreBase . '-' . $idRecurso . '.pdf';
                    // Mover archivo a carpeta pública
                    $pdfFile->move($carpetaRecurso, $nombreArchivo, true);
                    $rutaRelativa = 'libros/' . $idRecurso . '/' . $nombreArchivo;
                    // Actualizar campo urlLibro con la ruta relativa servible
                    $recursoModel->update($idRecurso, ['urlLibro' => $rutaRelativa]);
                }
            } else {
                // Si no es digital, ignorar cualquier PDF subido
                // Opcional: limpiar urlLibro si vino algo pero no es digital
                if ($idRecurso && !$esDigital) {
                    // Si deseas forzar vaciar urlLibro para no guardar rutas no digitales:
                    // $recursoModel->update($idRecurso, ['urlLibro' => null]);
                }
            }
        } catch (\Throwable $e) {
            // Loguear si es necesario pero no interrumpir el flujo del guardado básico
            log_message('error', 'Error subiendo PDF: ' . $e->getMessage());
        }
        
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
            'nivel'          => $this->request->getVar('nivel'),
            'idsubcategoria' => $this->request->getVar('idsubcategoria'),
            'ideditorial'    => $this->request->getVar('ideditorial'),
            'idtiporecurso'  => $this->request->getVar('idtiporecurso')
        ];

        // 1. Actualizar el recurso (sin tocar urlLibro aún)
        $recursoModel->update($idrecurso, $datosRecurso);

        // 1.0 Manejo de portada (imagen)
        try {
            $portada = $this->request->getFile('rutaportada');
            if ($portada && $portada->isValid() && !$portada->hasMoved()) {
                $mime = $portada->getMimeType();
                if (strpos($mime, 'image/') === 0) {
                    helper('text');
                    $recursoExistente = $recursoModel->find($idrecurso);
                    $tituloSlug = url_title(($recursoExistente['titulo'] ?? 'portada'), '-', true);
                    $ext = strtolower($portada->getExtension());
                    $nombreArchivo = $tituloSlug . '-' . $idrecurso . '.' . $ext;
                    $carpetaPublica = FCPATH . 'img' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR;
                    if (!is_dir($carpetaPublica)) {
                        @mkdir($carpetaPublica, 0775, true);
                    }
                    $portada->move($carpetaPublica, $nombreArchivo, true);
                    $rutaRelativaPortada = 'img/portadas/' . $nombreArchivo;
                    $recursoModel->update($idrecurso, ['rutaportada' => $rutaRelativaPortada]);
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error subiendo portada: ' . $e->getMessage());
        }

        // 1.1 Manejo de PDF SOLO si el tipo de recurso es digital
        try {
            $idTipo = $this->request->getVar('idtiporecurso');
            $esDigital = false;
            if ($idTipo) {
                $tipo = model('TiporecursoModel')->find($idTipo);
                if ($tipo && isset($tipo['tiporecurso']) && stripos($tipo['tiporecurso'], 'digital') !== false) {
                    $esDigital = true;
                }
            }

            if ($esDigital) {
                $pdfFile = $this->request->getFile('archivo_pdf');
                if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
                    helper('text');
                    $carpetaRecurso = FCPATH . 'libros' . DIRECTORY_SEPARATOR . $idrecurso . DIRECTORY_SEPARATOR;
                    if (!is_dir($carpetaRecurso)) {
                        @mkdir($carpetaRecurso, 0775, true);
                    }
                    $recursoExistente = $recursoModel->find($idrecurso);
                    $nombreBase = url_title(($recursoExistente['titulo'] ?? 'libro'), '-', true);
                    $nombreArchivo = $nombreBase . '-' . $idrecurso . '.pdf';
                    $pdfFile->move($carpetaRecurso, $nombreArchivo, true);
                    $rutaRelativa = 'libros/' . $idrecurso . '/' . $nombreArchivo;
                    $recursoModel->update($idrecurso, ['urlLibro' => $rutaRelativa]);
                }
            } else {
                // Si no es digital, no modificar urlLibro a menos que expresamente lo envíen
                $urlManual = $this->request->getVar('urlLibro');
                if ($urlManual !== null && $urlManual !== '') {
                    $recursoModel->update($idrecurso, ['urlLibro' => $urlManual]);
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error actualizando PDF: ' . $e->getMessage());
        }
        
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

    /**
     * Exportar listado de recursos a PDF
     * Requiere dependencia: dompdf/dompdf 
     */
    public function exportarPdf()
    {
        // Preparar datos sin paginación
        $recurso = new RecursoModel();
        $recursos = $recurso->orderBy('idrecurso', 'ASC')->findAll();

        // Cargar vista como HTML
        $html = view('recursos/pdf_list', [
            'recursos' => $recursos,
            'titulo'   => 'Listado de Recursos'
        ]);

        // Configurar Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Enviar al navegador en línea
        $filename = 'recursos-' . date('Ymd-His') . '.pdf';
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($dompdf->output());
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

    public function crearModal(): string
    {
        $recursoModel = new RecursoModel();
        $autorModel = new \App\Models\AutorModel();

        try {
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

            // Forzar que sea tratado como modal
            $_GET['modal'] = 'true';

            return view('recursos/crear', $datos);
            
        } catch (\Exception $e) {
            return json_encode([
                'error' => true,
                'message' => 'Error al cargar los datos: ' . $e->getMessage()
            ]);
        }
    }
    // Funcion para ver el pdf(Sin usar aun 18%)
    public function ver($id){
        $model = new RecursoModel();
        $recurso = $model->find($id);
    
        if (!$recurso) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Recurso no encontrado");
        }
    
        $path = $recurso['urlLibro'] ?? '';
        if (!$path) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("El recurso no tiene PDF asociado");
        }
    
        $pdfUrl = (stripos($path, 'http://') === 0 || stripos($path, 'https://') === 0)
            ? $path
            : base_url($path);
    
        return view('recursos/verPdf', ['pdfUrl' => $pdfUrl]);
    }
}