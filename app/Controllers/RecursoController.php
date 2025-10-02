<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RecursoModel;
use App\Models\DetAutorModel;
use App\Models\AutorModel;
use App\Models\CategoriaModel;
use App\Models\SubcategoriaModel;
use App\Models\EditorialModel;
use App\Models\TiporecursoModel;
use App\Models\UbicacionModel;
use App\Models\PrestamoModel;
use App\Models\SolicitudModel;
use App\Models\ComentarioModel;
use App\Models\ReaccionModel;
use App\Models\CompartidoModel;
use App\Models\FavoritoModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class RecursoController extends Controller
{
    public function index(): string
    {
        $recurso = new RecursoModel();
        $autorModel = new AutorModel();

        // Obtener todos los recursos con información completa (incluyendo datos físicos/digitales)
        $datos['recursos'] = $recurso->obtenerRecursosCompletos();

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

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');
        return view('recursos/listar', $datos);
    }

    public function crear(): string
    {
        $recursoModel = new RecursoModel();
        $autorModel = new AutorModel();

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

        try {
            // Determinar si es recurso digital o físico
            $idTipo = $this->request->getVar('idtiporecurso');
            $esDigital = false;
            if ($idTipo) {
                $tipo = model('TiporecursoModel')->find($idTipo);
                if ($tipo && isset($tipo['tiporecurso']) && stripos($tipo['tiporecurso'], 'digital') !== false) {
                    $esDigital = true;
                }
            }

            // Datos para la tabla recursos (estructura actual)
            $datosRecurso = [
                'titulo'         => $this->request->getVar('titulo'),
                'anio'           => $this->request->getVar('anio'),
                'numpaginas'     => $this->request->getVar('numpaginas'),
                'isbn'           => $this->request->getVar('isbn'),
                'numedicion'     => $this->request->getVar('numedicion'),
                'nivel'          => $this->request->getVar('nivel'),
                'idsubcategoria' => $this->request->getVar('idsubcategoria'),
                'ideditorial'    => $this->request->getVar('ideditorial'),
                'idtiporecurso'  => $this->request->getVar('idtiporecurso')
            ];

            // Solo agregar stock y estado para recursos físicos
            if (!$esDigital) {
                $datosRecurso['estado'] = $this->request->getVar('estado') ?: 'disponible';
                $datosRecurso['stock'] = $this->request->getVar('stock') ?: 1;
            } else {
                // Para recursos digitales, establecer valores por defecto
                $datosRecurso['estado'] = 'disponible';
                $datosRecurso['stock'] = 0; // Los recursos digitales no tienen stock físico
            }

            // 1. Insertar el recurso
            $idRecurso = $recursoModel->insert($datosRecurso);
            
            if (!$idRecurso) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al guardar el recurso'
                ]);
            }

            // Procesar archivos (portada y archivo digital)
            $portadaPath = null;
            $archivoPath = null;
            
            // Procesar portada
            try {
                $imagenFile = $this->request->getFile('portada');
                if ($imagenFile && $imagenFile->isValid() && !$imagenFile->hasMoved()) {
                    helper('text');
                    
                    // Determinar subcarpeta según tipo de recurso
                    $subcarpeta = $esDigital ? 'digital' : 'fisico';
                    $carpetaRecurso = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . $subcarpeta . DIRECTORY_SEPARATOR;
                    
                    if (!is_dir($carpetaRecurso)) {
                        @mkdir($carpetaRecurso, 0775, true);
                    }
                    $nombreBase = url_title($datosRecurso['titulo'] ?: 'portada', '-', true);
                    $extension = $imagenFile->getExtension();
                    $nombreArchivo = $nombreBase . '-' . $idRecurso . '.' . $extension;
                    $imagenFile->move($carpetaRecurso, $nombreArchivo, true);
                    $portadaPath = 'uploads/portadas/' . $subcarpeta . '/' . $nombreArchivo;
                }
            } catch (\Throwable $e) {
                log_message('error', 'Error subiendo portada: ' . $e->getMessage());
            }

            // Procesar archivo digital
            try {
                $archivoFile = $this->request->getFile('archivo');
                if ($archivoFile && $archivoFile->isValid() && !$archivoFile->hasMoved()) {
                    helper('text');
                    $carpetaRecurso = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'digitales' . DIRECTORY_SEPARATOR . 'archivos' . DIRECTORY_SEPARATOR;
                    if (!is_dir($carpetaRecurso)) {
                        @mkdir($carpetaRecurso, 0775, true);
                    }
                    $nombreBase = url_title($datosRecurso['titulo'] ?: 'archivo', '-', true);
                    $extension = $archivoFile->getExtension();
                    $nombreArchivo = $nombreBase . '-' . $idRecurso . '.' . $extension;
                    $archivoFile->move($carpetaRecurso, $nombreArchivo, true);
                    $archivoPath = 'uploads/digitales/archivos/' . $nombreArchivo;
                }
            } catch (\Throwable $e) {
                log_message('error', 'Error subiendo archivo digital: ' . $e->getMessage());
            }

            // Usar la variable $esDigital ya determinada arriba

            // Insertar en tabla específica según el tipo de recurso
            if ($esDigital) {
                // Insertar en recursos_digitales
                $recursoDigitalModel = new \App\Models\RecursoDigitalModel();
                $recursoDigitalModel->insert([
                    'idrecurso' => $idRecurso,
                    'portada' => $portadaPath,
                    'archivo' => $archivoPath
                ]);
            } else {
                // Insertar en recursos_fisicos
                $encuadernacion = $this->request->getVar('encuadernacion');
                $recursoFisicoModel = new \App\Models\RecursoFisicoModel();
                $recursoFisicoModel->insert([
                    'idrecurso' => $idRecurso,
                    'portada' => $portadaPath,
                    'encuadernacion' => $encuadernacion
                ]);
            }
            
            // 2. Insertar la relación autor-recurso en detautores
            $idAutores = $this->request->getVar('idautor');
            if ($idAutores && $idRecurso) {
                if (!is_array($idAutores)) {
                    $idAutores = [$idAutores];
                }
                foreach ($idAutores as $idAutor) {
                    if ($idAutor) {
                        $detAutorModel->insert([
                            'idautor' => $idAutor,
                            'idrecurso' => $idRecurso
                        ]);
                    }
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Recurso registrado exitosamente',
                'titulo' => $datosRecurso['titulo']
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al procesar el recurso: ' . $e->getMessage()
            ]);
        }
    }

    public function editar($idrecurso = null)
    {
        $recursoModel = new RecursoModel();
        $detAutorModel = new DetAutorModel();
        $autorModel = new AutorModel();
        $categoriaModel = new CategoriaModel();
        $subcategoriaModel = new SubcategoriaModel();
        $editorialModel = new EditorialModel();
        $tiporecursoModel = new TiporecursoModel();
        
        $datos['recurso'] = $recursoModel->find($idrecurso);

        if (!$datos['recurso']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Recurso no encontrado');
        }

        // Obtener el autor actual del recurso
        $autorActual = $detAutorModel->where('idrecurso', $idrecurso)->first();
        $datos['autorActual'] = $autorActual ? $autorActual['idautor'] : null;

        // Obtener categoría actual basada en subcategoría
        $categoriaActual = null;
        if ($datos['recurso']['idsubcategoria']) {
            $subcategoria = $subcategoriaModel->find($datos['recurso']['idsubcategoria']);
            $categoriaActual = $subcategoria ? $subcategoria['idcategoria'] : null;
        }
        $datos['categoriaActual'] = $categoriaActual;

        // Obtener datos para los selects
        $datos['autores'] = $autorModel->orderBy('apeautor', 'ASC')->findAll();
        $datos['categorias'] = $categoriaModel->orderBy('categoria', 'ASC')->findAll();
        $datos['subcategorias'] = $subcategoriaModel->orderBy('subcategoria', 'ASC')->findAll();
        $datos['editoriales'] = $editorialModel->orderBy('editorial', 'ASC')->findAll();
        $datos['tiposrecurso'] = $tiporecursoModel->orderBy('tiporecurso', 'ASC')->findAll();
        $datos['estados'] = ['disponible', 'prestado', 'perdido'];
        $datos['niveles'] = ['primaria', 'secundaria', 'superior', 'general'];

        // Si es petición AJAX, solo devolver la vista sin layout
        if ($this->request->isAJAX()) {
            return view('recursos/editar', $datos);
        }

        // Si no es AJAX, incluir layout completo
        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('recursos/editar', $datos);
    }

    // Mostrar modal de edición por AJAX
    public function modalEditar($idrecurso = null)
    {
        $recursoModel = new RecursoModel();
        $detAutorModel = new DetAutorModel();
        $autorModel = new AutorModel();
        $categoriaModel = new CategoriaModel();
        $subcategoriaModel = new SubcategoriaModel();
        $editorialModel = new EditorialModel();
        $tiporecursoModel = new TiporecursoModel();
        
        // Obtener el recurso con información completa
        $recurso = $recursoModel->obtenerRecursosCompletos();
        $recurso = array_filter($recurso, function($r) use ($idrecurso) {
            return $r['idrecurso'] == $idrecurso;
        });
        $recurso = reset($recurso); // Obtener el primer (y único) elemento
        
        if (!$recurso) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Recurso no encontrado');
        }

        // Obtener el autor actual del recurso
        $autorActual = $detAutorModel->where('idrecurso', $idrecurso)->first();
        $autorActual = $autorActual ? $autorActual['idautor'] : null;

        // Obtener categoría actual basada en subcategoría
        $categoriaActual = null;
        if ($recurso['idsubcategoria']) {
            $subcategoria = $subcategoriaModel->find($recurso['idsubcategoria']);
            $categoriaActual = $subcategoria ? $subcategoria['idcategoria'] : null;
        }

        // Obtener datos para los selects
        $datos = [
            'recurso' => $recurso,
            'autorActual' => $autorActual,
            'categoriaActual' => $categoriaActual,
            'autores' => $autorModel->orderBy('apeautor', 'ASC')->findAll(),
            'categorias' => $categoriaModel->orderBy('categoria', 'ASC')->findAll(),
            'subcategorias' => $subcategoriaModel->orderBy('subcategoria', 'ASC')->findAll(),
            'editoriales' => $editorialModel->orderBy('editorial', 'ASC')->findAll(),
            'tiposrecurso' => $tiporecursoModel->orderBy('tiporecurso', 'ASC')->findAll(),
            'estados' => ['disponible', 'prestado', 'perdido'],
            'niveles' => ['primaria', 'secundaria', 'superior', 'general']
        ];

        return view('recursos/modal_editar', $datos);
    }

public function actualizar($idrecurso)
{
    $recursoModel = new RecursoModel();
    $detAutorModel = new DetAutorModel();
    

    // Determinar si es recurso digital o físico
    $idTipo = $this->request->getVar('idtiporecurso');
    $esDigital = false;
    if ($idTipo) {
        $tipo = model('TiporecursoModel')->find($idTipo);
        if ($tipo && isset($tipo['tiporecurso']) && stripos($tipo['tiporecurso'], 'digital') !== false) {
            $esDigital = true;
        }
    }

    // Datos para actualizar en recursos
    $datosRecurso = [
        'titulo'         => $this->request->getVar('titulo'),
        'anio'           => $this->request->getVar('anio'),
        'numpaginas'     => $this->request->getVar('numpaginas'),
        'isbn'           => $this->request->getVar('isbn'),
        'numedicion'     => $this->request->getVar('numedicion'),
        'rutaportada'    => $this->request->getVar('rutaportada'),
        'nivel'          => $this->request->getVar('nivel'),
        'idsubcategoria' => $this->request->getVar('idsubcategoria'),
        'ideditorial'    => $this->request->getVar('ideditorial'),
        'idtiporecurso'  => $this->request->getVar('idtiporecurso')
    ];

    // Solo actualizar stock y estado para recursos físicos
    if (!$esDigital) {
        $datosRecurso['estado'] = $this->request->getVar('estado') ?: 'disponible';
        $datosRecurso['stock'] = $this->request->getVar('stock') ?: 1;
    } else {
        // Para recursos digitales, mantener valores por defecto
        $datosRecurso['estado'] = 'disponible';
        $datosRecurso['stock'] = 0; // Los recursos digitales no tienen stock físico
    }

    // 1. Actualizar el recurso (sin tocar aún portada ni PDF)
    $recursoModel->update($idrecurso, $datosRecurso);

    // 1.1 Manejo de portada (imagen)
    $rutaPortadaActualizada = null;
    try {
        $portada = $this->request->getFile('rutaportada');
        
        
        if ($portada && $portada->isValid() && !$portada->hasMoved()) {
            $mime = $portada->getMimeType();
            if (strpos($mime, 'image/') === 0) {
                helper('text');
                
                
                // IMPORTANTE: Eliminar imagen anterior ANTES de subir la nueva
                $this->eliminarImagenAnterior($idrecurso);
                
                $recursoExistente = $recursoModel->find($idrecurso);
                
                $tituloSlug = url_title(($recursoExistente['titulo'] ?? 'portada'), '-', true);
                $ext = strtolower($portada->getExtension());
                $nombreArchivo = $tituloSlug . '-' . $idrecurso . '.' . $ext;
                // Determinar carpeta según tipo de recurso
                $tipoRecurso = $this->request->getVar('idtiporecurso');
                $esDigitalPortada = false;
                if ($tipoRecurso) {
                    $tipo = model('TiporecursoModel')->find($tipoRecurso);
                    if ($tipo && isset($tipo['tiporecurso']) && stripos($tipo['tiporecurso'], 'digital') !== false) {
                        $esDigitalPortada = true;
                    }
                }
                
                $subcarpeta = $esDigitalPortada ? 'digital' : 'fisico';
                $carpetaPublica = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . $subcarpeta . DIRECTORY_SEPARATOR;

                if (!is_dir($carpetaPublica)) {
                    @mkdir($carpetaPublica, 0775, true);
                }

                $portada->move($carpetaPublica, $nombreArchivo, true);
                $rutaRelativaPortada = 'uploads/portadas/' . $subcarpeta . '/' . $nombreArchivo;
                $recursoModel->update($idrecurso, ['rutaportada' => $rutaRelativaPortada]);
                $rutaPortadaActualizada = $rutaRelativaPortada;
            }
        }
    } catch (\Throwable $e) {
        log_message('error', 'Error subiendo portada: ' . $e->getMessage());
    }

    // 1.2 Manejo de PDF si el recurso es digital
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
            // Si no es digital, permitir modificación manual si se envía
            $urlManual = $this->request->getVar('urlLibro');
            if (!empty($urlManual)) {
                $recursoModel->update($idrecurso, ['urlLibro' => $urlManual]);
            }
        }
    } catch (\Throwable $e) {
        log_message('error', 'Error actualizando PDF: ' . $e->getMessage());
    }

    // 1.3 Actualizar tabla específica según el tipo de recurso
    try {
        if ($esDigital) {
            // Actualizar recursos_digitales
            $recursoDigitalModel = new \App\Models\RecursoDigitalModel();
            $datosDigital = [];
            
            // Actualizar portada en recursos_digitales si se subió una nueva imagen
            if ($rutaPortadaActualizada !== null) {
                $datosDigital['portada'] = $rutaPortadaActualizada;
                
                // Limpiar portada de recursos_fisicos si existe (evitar duplicados)
                $recursoFisicoModel = new \App\Models\RecursoFisicoModel();
                $recursoFisicoExistente = $recursoFisicoModel->find($idrecurso);
                if ($recursoFisicoExistente) {
                    $recursoFisicoModel->update($idrecurso, ['portada' => null]);
                }
            }
            
            // Solo actualizar si hay archivo nuevo
            $archivoPdf = $this->request->getFile('archivo_pdf');
            if ($archivoPdf && $archivoPdf->isValid() && !$archivoPdf->hasMoved()) {
                // Procesar archivo PDF
                $carpetaRecurso = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'digitales' . DIRECTORY_SEPARATOR . 'archivos' . DIRECTORY_SEPARATOR;
                if (!is_dir($carpetaRecurso)) {
                    @mkdir($carpetaRecurso, 0775, true);
                }
                
                $recursoExistente = $recursoModel->find($idrecurso);
                $nombreBase = url_title(($recursoExistente['titulo'] ?? 'libro'), '-', true);
                $nombreArchivo = $nombreBase . '-' . $idrecurso . '.pdf';
                $archivoPdf->move($carpetaRecurso, $nombreArchivo, true);
                $datosDigital['archivo'] = 'uploads/digitales/archivos/' . $nombreArchivo;
            }
            
            // Actualizar si hay datos que cambiar
            if (!empty($datosDigital)) {
                $recursoDigitalModel->update($idrecurso, $datosDigital);
            }
        } else {
            // Actualizar recursos_fisicos
            $recursoFisicoModel = new \App\Models\RecursoFisicoModel();
            $datosFisico = [];
            
            // Actualizar encuadernación si se proporciona
            $encuadernacion = $this->request->getVar('encuadernacion');
            if ($encuadernacion !== null) {
                $datosFisico['encuadernacion'] = $encuadernacion;
            }
            
            // Actualizar portada en recursos_fisicos si se subió una nueva imagen
            if ($rutaPortadaActualizada !== null) {
                $datosFisico['portada'] = $rutaPortadaActualizada;
                
                // Limpiar portada de recursos_digitales si existe (evitar duplicados)
                $recursoDigitalModel = new \App\Models\RecursoDigitalModel();
                $recursoDigitalExistente = $recursoDigitalModel->find($idrecurso);
                if ($recursoDigitalExistente) {
                    $recursoDigitalModel->update($idrecurso, ['portada' => null]);
                }
            }
            
            // Actualizar si hay datos que cambiar
            if (!empty($datosFisico)) {
                $recursoFisicoModel->update($idrecurso, $datosFisico);
            }
        }
    } catch (\Throwable $e) {
        log_message('error', 'Error actualizando tabla específica: ' . $e->getMessage());
    }

    // 2. Actualizar relación autor-recurso
    try {
        $idAutor = $this->request->getVar('idautor');

        if ($idAutor) {
            // Eliminar relaciones anteriores
            $detAutorModel->where('idrecurso', $idrecurso)->delete();

            // Insertar nueva relación
            $detAutorModel->insert([
                'idautor' => $idAutor,
                'idrecurso' => $idrecurso
            ]);
        }

        $respuesta = [
            'status' => 'success',
            'message' => 'Recurso actualizado exitosamente',
            'titulo' => $datosRecurso['titulo']
        ];
        
        // Agregar nueva ruta de imagen si se actualizó
        if ($rutaPortadaActualizada !== null) {
            $respuesta['nuevaRutaImagen'] = $rutaPortadaActualizada;
        }
        
        return $this->response->setJSON($respuesta);
    } catch (\Throwable $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Error al procesar la actualización: ' . $e->getMessage()
        ]);
    }
}


    public function eliminar($idrecurso = null)
    {
        $recursoModel = new RecursoModel();
        $detAutorModel = new DetAutorModel();
        $ubicacionModel = new UbicacionModel();
        $prestamoModel = new PrestamoModel();
        $solicitudModel = new SolicitudModel();
        $comentarioModel = new ComentarioModel();
        $reaccionModel = new ReaccionModel();
        $compartidoModel = new CompartidoModel();
        $favoritoModel = new FavoritoModel();
        
        // Log para debug
        log_message('info', 'Eliminando recurso ID: ' . $idrecurso);
        
        try {
            // Verificar que el recurso existe
            $recurso = $recursoModel->find($idrecurso);
            if (!$recurso) {
                if ($this->request->isAJAX()) {
                    return $this->response
                        ->setContentType('application/json')
                        ->setJSON([
                            'success' => false,
                            'message' => 'El recurso no existe'
                        ]);
                }
                return $this->response->redirect(base_url('recursos'));
            }
            
            // Eliminar registros relacionados en orden correcto
            // 1. Eliminar favoritos
            $favoritoModel->deleteByRecurso($idrecurso);
            
            // 2. Eliminar compartidos
            $compartidoModel->deleteByRecurso($idrecurso);
            
            // 3. Eliminar reacciones
            $reaccionModel->deleteByRecurso($idrecurso);
            
            // 4. Eliminar comentarios
            $comentarioModel->deleteByRecurso($idrecurso);
            
            // 5. Eliminar solicitudes (ANTES de eliminar préstamos)
            $solicitudModel->deleteByRecurso($idrecurso);
            
            // 6. Eliminar préstamos
            $prestamoModel->deleteByRecurso($idrecurso);
            
            // 7. Eliminar ubicaciones
            $ubicacionModel->deleteByRecurso($idrecurso);
            
            // 8. Eliminar relaciones autor-recurso
            $detAutorModel->deleteByRecurso($idrecurso);
            
            // 9. Eliminar de tabla específica (físico o digital)
            $recursoFisicoModel = new \App\Models\RecursoFisicoModel();
            $recursoDigitalModel = new \App\Models\RecursoDigitalModel();
            
            // Verificar si existe en recursos_fisicos
            $recursoFisico = $recursoFisicoModel->find($idrecurso);
            if ($recursoFisico) {
                // Eliminar archivo de portada si existe
                if (!empty($recursoFisico['portada'])) {
                    $rutaPortada = FCPATH . $recursoFisico['portada'];
                    if (file_exists($rutaPortada)) {
                        unlink($rutaPortada);
                    }
                }
                $recursoFisicoModel->delete($idrecurso);
            }
            
            // Verificar si existe en recursos_digitales
            $recursoDigital = $recursoDigitalModel->find($idrecurso);
            if ($recursoDigital) {
                // Eliminar archivo digital si existe
                if (!empty($recursoDigital['archivo'])) {
                    $rutaArchivo = FCPATH . $recursoDigital['archivo'];
                    if (file_exists($rutaArchivo)) {
                        unlink($rutaArchivo);
                    }
                }
                // Eliminar portada si existe
                if (!empty($recursoDigital['portada'])) {
                    $rutaPortada = FCPATH . $recursoDigital['portada'];
                    if (file_exists($rutaPortada)) {
                        unlink($rutaPortada);
                    }
                }
                $recursoDigitalModel->delete($idrecurso);
            }
            
            // 9. Finalmente eliminar el recurso principal
            $recursoModel->delete($idrecurso);
            
            // Si es una petición AJAX, devolver JSON
            if ($this->request->isAJAX()) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON([
                        'success' => true,
                        'message' => 'Recurso eliminado correctamente'
                    ]);
            }
            
            // Si no es AJAX, redirigir (por compatibilidad)
            return $this->response->redirect(base_url('recursos'));
            
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar recurso: ' . $e->getMessage());
            
            // Si es una petición AJAX, devolver error JSON
            if ($this->request->isAJAX()) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON([
                        'success' => false,
                        'message' => 'Error al eliminar el recurso: ' . $e->getMessage()
                    ]);
            }
            
            // Si no es AJAX, redirigir con error
            session()->setFlashdata('error', 'Error al eliminar el recurso');
            return $this->response->redirect(base_url('recursos'));
        }
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

    /**
     * Método temporal para limpiar rutas de imágenes incorrectas
     */
    public function limpiarRutasImagenes()
    {
        $recursoModel = new RecursoModel();
        $recursoFisicoModel = new \App\Models\RecursoFisicoModel();
        $recursoDigitalModel = new \App\Models\RecursoDigitalModel();
        
        $actualizados = 0;
        $eliminados = 0;
        
        try {
            // Obtener todos los recursos con sus portadas
            $recursos = $recursoModel->select('recursos.*, rf.portada as portada_fisica, rd.portada as portada_digital')
                ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
                ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left')
                ->findAll();
            
            foreach ($recursos as $recurso) {
                $idrecurso = $recurso['idrecurso'];
                $portadaFisica = $recurso['portada_fisica'] ?? '';
                $portadaDigital = $recurso['portada_digital'] ?? '';
                
                // Verificar portada física
                if (!empty($portadaFisica)) {
                    $archivoFisico = FCPATH . $portadaFisica;
                    if (!file_exists($archivoFisico)) {
                        // Archivo no existe, limpiar la referencia
                        $recursoFisicoModel->update($idrecurso, ['portada' => null]);
                        $eliminados++;
                    }
                }
                
                // Verificar portada digital
                if (!empty($portadaDigital)) {
                    $archivoDigital = FCPATH . $portadaDigital;
                    if (!file_exists($archivoDigital)) {
                        // Archivo no existe, limpiar la referencia
                        $recursoDigitalModel->update($idrecurso, ['portada' => null]);
                        $eliminados++;
                    }
                }
                
                // Actualizar rutaportada en recursos basándose en archivos existentes
                $nuevaRuta = null;
                if (!empty($portadaFisica) && file_exists(FCPATH . $portadaFisica)) {
                    $nuevaRuta = $portadaFisica;
                } elseif (!empty($portadaDigital) && file_exists(FCPATH . $portadaDigital)) {
                    $nuevaRuta = $portadaDigital;
                }
                
                // Solo contar como actualizado si se cambió algo
                if ($nuevaRuta !== null) {
                    $actualizados++;
                }
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Se actualizaron $actualizados rutas y se eliminaron $eliminados referencias incorrectas"
            ]);
            
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar TODAS las imágenes anteriores del recurso (cualquier extensión)
     */
    private function eliminarImagenAnterior($idrecurso)
    {
        try {
            if (ENVIRONMENT === 'development') {
                log_message('debug', "🗑️ Eliminando imágenes anteriores para recurso: $idrecurso");
            }
            
            $archivosEliminados = 0;
            
            // Buscar y eliminar en carpeta física
            $carpetaFisica = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . 'fisico' . DIRECTORY_SEPARATOR;
            if (is_dir($carpetaFisica)) {
                $archivosEliminados += $this->eliminarArchivosRecurso($carpetaFisica, $idrecurso, 'física');
            }
            
            // Buscar y eliminar en carpeta digital
            $carpetaDigital = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . 'digital' . DIRECTORY_SEPARATOR;
            if (is_dir($carpetaDigital)) {
                $archivosEliminados += $this->eliminarArchivosRecurso($carpetaDigital, $idrecurso, 'digital');
            }
            
            if (ENVIRONMENT === 'development') {
                log_message('debug', "✅ Eliminación completada. Archivos eliminados: $archivosEliminados");
            }
            
        } catch (\Throwable $e) {
            log_message('error', 'Error eliminando imagen anterior: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar archivos de un recurso específico en una carpeta
     */
    private function eliminarArchivosRecurso($carpeta, $idrecurso, $tipo)
    {
        $eliminados = 0;
        
        try {
            // Buscar archivos que terminen con -ID.extension (más específico)
            $patron = '*-' . $idrecurso . '.{jpg,jpeg,png,gif,webp}';
            $archivos = glob($carpeta . $patron, GLOB_BRACE);
            
            if (ENVIRONMENT === 'development') {
                log_message('debug', "🔍 Buscando en $tipo: $patron");
                log_message('debug', "📁 Archivos encontrados: " . count($archivos));
            }
            
            foreach ($archivos as $archivo) {
                if (is_file($archivo)) {
                    $nombreArchivo = basename($archivo);
                    if (@unlink($archivo)) {
                        $eliminados++;
                        if (ENVIRONMENT === 'development') {
                            log_message('debug', "✅ Eliminado ($tipo): $nombreArchivo");
                        }
                    } else {
                        if (ENVIRONMENT === 'development') {
                            log_message('debug', "❌ No se pudo eliminar ($tipo): $nombreArchivo");
                        }
                    }
                }
            }
            
        } catch (\Throwable $e) {
            log_message('error', 'Error eliminando archivos en carpeta ' . $tipo . ': ' . $e->getMessage());
        }
        
        return $eliminados;
    }

    /**
     * Método temporal para limpiar archivos duplicados existentes
     */
    public function limpiarDuplicados()
    {
        try {
            $eliminados = 0;
            
            // Limpiar carpeta física
            $carpetaFisica = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . 'fisico' . DIRECTORY_SEPARATOR;
            if (is_dir($carpetaFisica)) {
                $eliminados += $this->limpiarDuplicadosEnCarpeta($carpetaFisica, 'física');
            }
            
            // Limpiar carpeta digital
            $carpetaDigital = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . 'digital' . DIRECTORY_SEPARATOR;
            if (is_dir($carpetaDigital)) {
                $eliminados += $this->limpiarDuplicadosEnCarpeta($carpetaDigital, 'digital');
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Se eliminaron $eliminados archivos duplicados"
            ]);
            
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Limpiar duplicados en una carpeta específica
     */
    private function limpiarDuplicadosEnCarpeta($carpeta, $tipo)
    {
        $eliminados = 0;
        $recursos = [];
        
        try {
            // Obtener todos los archivos
            $archivos = glob($carpeta . '*.*');
            
            // Agrupar por ID de recurso
            foreach ($archivos as $archivo) {
                $nombre = basename($archivo);
                // Extraer ID del recurso (formato: nombre-ID.extension)
                if (preg_match('/^(.+)-(\d+)\.(jpg|jpeg|png|gif)$/i', $nombre, $matches)) {
                    $idRecurso = $matches[2];
                    $extension = strtolower($matches[3]);
                    
                    if (!isset($recursos[$idRecurso])) {
                        $recursos[$idRecurso] = [];
                    }
                    
                    $recursos[$idRecurso][] = [
                        'archivo' => $archivo,
                        'nombre' => $nombre,
                        'extension' => $extension,
                        'tiempo' => filemtime($archivo)
                    ];
                }
            }
            
            // Para cada recurso con múltiples archivos, mantener solo el más reciente
            foreach ($recursos as $idRecurso => $archivosRecurso) {
                if (count($archivosRecurso) > 1) {
                    // Ordenar por tiempo de modificación (más reciente primero)
                    usort($archivosRecurso, function($a, $b) {
                        return $b['tiempo'] - $a['tiempo'];
                    });
                    
                    // Eliminar todos excepto el más reciente
                    for ($i = 1; $i < count($archivosRecurso); $i++) {
                        if (@unlink($archivosRecurso[$i]['archivo'])) {
                            $eliminados++;
                            // Duplicado eliminado
                        }
                    }
                    
                    // Archivo más reciente mantenido
                }
            }
            
        } catch (\Throwable $e) {
            log_message('error', 'Error limpiando duplicados en carpeta ' . $tipo . ': ' . $e->getMessage());
        }
        
        return $eliminados;
    }

    /**
     * Método simple para actualizar rutas de imágenes usando SQL directo
     */
    public function actualizarRutasImagenes()
    {
        try {
            $db = \Config\Database::connect();
            $actualizados = 0;
            $debug = [];
            
            // Obtener archivos físicos
            $carpetaFisica = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . 'fisico' . DIRECTORY_SEPARATOR;
            $archivosFisicos = glob($carpetaFisica . '*.*');
            
            foreach ($archivosFisicos as $archivo) {
                $nombre = basename($archivo);
                if (preg_match('/^(.+)-(\d+)\.(jpg|jpeg|png|gif)$/i', $nombre, $matches)) {
                    $idRecurso = $matches[2];
                    $rutaNueva = 'uploads/portadas/fisico/' . $nombre;
                    
                    // Verificar que el recurso existe antes de actualizar
                    $recursoExiste = $db->query("SELECT idrecurso FROM recursos WHERE idrecurso = ?", [$idRecurso])->getRow();
                    if ($recursoExiste) {
                        // Actualizar o insertar en recursos_fisicos
                        $existe = $db->query("SELECT idrecurso FROM recursos_fisicos WHERE idrecurso = ?", [$idRecurso])->getRow();
                        if ($existe) {
                            $db->query("UPDATE recursos_fisicos SET portada = ? WHERE idrecurso = ?", [$rutaNueva, $idRecurso]);
                        } else {
                            $db->query("INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (?, ?, 'Tapa blanda')", [$idRecurso, $rutaNueva]);
                        }
                        $actualizados++;
                        $debug[] = "Físico: $nombre -> ID $idRecurso";
                    } else {
                        $debug[] = "⚠️ Recurso ID $idRecurso no existe en BD (archivo: $nombre)";
                    }
                }
            }
            
            // Obtener archivos digitales
            $carpetaDigital = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . 'digital' . DIRECTORY_SEPARATOR;
            $archivosDigitales = glob($carpetaDigital . '*.*');
            
            foreach ($archivosDigitales as $archivo) {
                $nombre = basename($archivo);
                if (preg_match('/^(.+)-(\d+)\.(jpg|jpeg|png|gif)$/i', $nombre, $matches)) {
                    $idRecurso = $matches[2];
                    $rutaNueva = 'uploads/portadas/digital/' . $nombre;
                    
                    // Verificar que el recurso existe antes de actualizar
                    $recursoExiste = $db->query("SELECT idrecurso FROM recursos WHERE idrecurso = ?", [$idRecurso])->getRow();
                    if ($recursoExiste) {
                        // Actualizar o insertar en recursos_digitales
                        $existe = $db->query("SELECT idrecurso FROM recursos_digitales WHERE idrecurso = ?", [$idRecurso])->getRow();
                        if ($existe) {
                            $db->query("UPDATE recursos_digitales SET portada = ? WHERE idrecurso = ?", [$rutaNueva, $idRecurso]);
                        } else {
                            $db->query("INSERT INTO recursos_digitales (idrecurso, portada) VALUES (?, ?)", [$idRecurso, $rutaNueva]);
                        }
                        
                        // Limpiar recursos_fisicos
                        $db->query("UPDATE recursos_fisicos SET portada = NULL WHERE idrecurso = ?", [$idRecurso]);
                        
                        $actualizados++;
                        $debug[] = "Digital: $nombre -> ID $idRecurso";
                    } else {
                        $debug[] = "⚠️ Recurso ID $idRecurso no existe en BD (archivo: $nombre)";
                    }
                }
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Se actualizaron $actualizados rutas de imágenes",
                'actualizados' => $actualizados,
                'debug' => $debug
            ]);
            
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Sincronizar rutas de imágenes en la base de datos con archivos existentes
     */
    public function sincronizarImagenes()
    {
        try {
            $recursoModel = new RecursoModel();
            $recursoFisicoModel = new \App\Models\RecursoFisicoModel();
            $recursoDigitalModel = new \App\Models\RecursoDigitalModel();
            
            $actualizados = 0;
            $errores = [];
            $debug = [];
            
            // Obtener todos los recursos
            $recursos = $recursoModel->findAll();
            $debug[] = "Total recursos encontrados: " . count($recursos);
            
            foreach ($recursos as $recurso) {
                $idrecurso = $recurso['idrecurso'];
                $rutaEncontrada = null;
                $tipoEncontrado = null;
                
                // Buscar archivo en carpeta física
                $carpetaFisica = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . 'fisico' . DIRECTORY_SEPARATOR;
                $archivosFisicos = glob($carpetaFisica . '*-' . $idrecurso . '.*');
                $debug[] = "Recurso $idrecurso - Archivos físicos: " . count($archivosFisicos);
                
                if (!empty($archivosFisicos)) {
                    $archivo = $archivosFisicos[0]; // Tomar el primero (ya no hay duplicados)
                    $rutaEncontrada = 'uploads/portadas/fisico/' . basename($archivo);
                    $tipoEncontrado = 'fisico';
                    $debug[] = "Recurso $idrecurso - Encontrado físico: " . $rutaEncontrada;
                }
                
                // Si no se encontró en físico, buscar en digital
                if (!$rutaEncontrada) {
                    $carpetaDigital = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'portadas' . DIRECTORY_SEPARATOR . 'digital' . DIRECTORY_SEPARATOR;
                    $archivosDigitales = glob($carpetaDigital . '*-' . $idrecurso . '.*');
                    $debug[] = "Recurso $idrecurso - Archivos digitales: " . count($archivosDigitales);
                    
                    if (!empty($archivosDigitales)) {
                        $archivo = $archivosDigitales[0];
                        $rutaEncontrada = 'uploads/portadas/digital/' . basename($archivo);
                        $tipoEncontrado = 'digital';
                        $debug[] = "Recurso $idrecurso - Encontrado digital: " . $rutaEncontrada;
                    }
                }
                
                // Actualizar base de datos si se encontró archivo
                if ($rutaEncontrada) {
                    // Verificar si la ruta actual es diferente
                    $rutaActual = $recurso['rutaportada'] ?? '';
                    if ($rutaActual !== $rutaEncontrada) {
                        // Actualizar tabla recursos
                        $recursoModel->update($idrecurso, ['rutaportada' => $rutaEncontrada]);
                        $debug[] = "Recurso $idrecurso - Actualizada ruta principal: $rutaEncontrada";
                    }
                    
                    // Actualizar tabla específica según el tipo
                    if ($tipoEncontrado === 'fisico') {
                        // Actualizar recursos_fisicos
                        $recursoFisicoExistente = $recursoFisicoModel->find($idrecurso);
                        if ($recursoFisicoExistente) {
                            if (($recursoFisicoExistente['portada'] ?? '') !== $rutaEncontrada) {
                                $recursoFisicoModel->update($idrecurso, ['portada' => $rutaEncontrada]);
                                $debug[] = "Recurso $idrecurso - Actualizada portada física";
                            }
                        } else {
                            // Crear registro si no existe
                            $recursoFisicoModel->insert([
                                'idrecurso' => $idrecurso,
                                'portada' => $rutaEncontrada,
                                'encuadernacion' => 'Tapa blanda'
                            ]);
                            $debug[] = "Recurso $idrecurso - Creado registro físico";
                        }
                        
                        // Limpiar recursos_digitales (solo si tiene portada)
                        $recursoDigitalExistente = $recursoDigitalModel->find($idrecurso);
                        if ($recursoDigitalExistente && !empty($recursoDigitalExistente['portada'])) {
                            $recursoDigitalModel->update($idrecurso, ['portada' => null]);
                            $debug[] = "Recurso $idrecurso - Limpiada portada digital";
                        }
                        
                    } else { // digital
                        // Actualizar recursos_digitales
                        $recursoDigitalExistente = $recursoDigitalModel->find($idrecurso);
                        if ($recursoDigitalExistente) {
                            if (($recursoDigitalExistente['portada'] ?? '') !== $rutaEncontrada) {
                                $recursoDigitalModel->update($idrecurso, ['portada' => $rutaEncontrada]);
                                $debug[] = "Recurso $idrecurso - Actualizada portada digital";
                            }
                        } else {
                            // Crear registro si no existe
                            $recursoDigitalModel->insert([
                                'idrecurso' => $idrecurso,
                                'portada' => $rutaEncontrada,
                                'archivo' => null
                            ]);
                            $debug[] = "Recurso $idrecurso - Creado registro digital";
                        }
                        
                        // Limpiar recursos_fisicos (solo si tiene portada)
                        $recursoFisicoExistente = $recursoFisicoModel->find($idrecurso);
                        if ($recursoFisicoExistente && !empty($recursoFisicoExistente['portada'])) {
                            $recursoFisicoModel->update($idrecurso, ['portada' => null]);
                            $debug[] = "Recurso $idrecurso - Limpiada portada física";
                        }
                    }
                    
                    $actualizados++;
                    
                } else {
                    $debug[] = "Recurso $idrecurso - No se encontró imagen";
                }
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Se sincronizaron $actualizados imágenes correctamente",
                'actualizados' => $actualizados,
                'total_recursos' => count($recursos),
                'debug' => $debug
            ]);
            
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error sincronizando imágenes: ' . $e->getMessage()
            ]);
        }
    }
}