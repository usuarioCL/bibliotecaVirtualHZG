<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\SubcategoriaModel;
use App\Models\RecursoModel;
use App\Models\AutorModel;
use App\Models\DetAutorModel; // para obtener autores
use App\Models\PrestamoModel;
use App\Models\FavoritoModel;

class CatalogoController extends BaseController
{
    public function index()
    {
        // Si es una petición AJAX, manejar según el parámetro
        if ($this->request->isAJAX()) {
            log_message('info', 'Petición AJAX recibida en index()');
            return $this->getAllSubcategorias();
        }

        $categoriaModel = new CategoriaModel();
        $subcategoriaModel = new SubcategoriaModel();
        $recursoModel = new RecursoModel();

        $categorias = $categoriaModel->findAll();
        $subcategorias = $subcategoriaModel->findAll();

        // Traemos libros para cada subcategoría
        $datosSub = [];
        foreach ($subcategorias as $sub) {
            $libros = $recursoModel->where('idsubcategoria', $sub['idsubcategoria'])->findAll();
            
            // agregar autores a cada libro
            foreach ($libros as &$libro) {
                $libro['autores'] = $this->obtenerAutoresLibro($libro['idrecurso']);
            }
            
            $datosSub[] = [
                'subcategoria' => $sub['subcategoria'],
                'libros' => $libros
            ];
        }

        $datos = [
            'categorias' => $categorias,
            'subcategorias' => $datosSub,
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer'),
            'navbar' => view('layouts/navbar')
        ];

        return view('Catalogo/catalogo', $datos);
    }

    // Método para obtener todas las subcategorías (para el botón "Todos")
    public function getAllSubcategorias()
    {
        try {
            log_message('info', 'Método getAllSubcategorias() llamado');
            
            $subcategoriaModel = new SubcategoriaModel();
            $recursoModel = new RecursoModel();

            $subcategorias = $subcategoriaModel->findAll();
            $resultado = [];

            log_message('info', 'Subcategorías encontradas: ' . count($subcategorias));

            foreach ($subcategorias as $sub) {
                $libros = $recursoModel->where('idsubcategoria', $sub['idsubcategoria'])->findAll();
                
                log_message('info', "Subcategoría {$sub['subcategoria']}: " . count($libros) . " libros");

                // agregar autores a cada libro
                foreach ($libros as &$libro) {
                    $libro['autores'] = $this->obtenerAutoresLibro($libro['idrecurso']);
                }

                $resultado[] = [
                    'subcategoria' => $sub['subcategoria'],
                    'libros' => $libros
                ];
            }

            log_message('info', 'Resultado final preparado con ' . count($resultado) . ' subcategorías');
            
            $this->response->setContentType('application/json');
            return $this->response->setJSON($resultado);
    
        } catch (\Exception $e) {
            log_message('error', 'Error en getAllSubcategorias: ' . $e->getMessage());
            $this->response->setContentType('application/json');
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Error interno del servidor'
            ])->setStatusCode(500);
        }
    }

    // Método helper para obtener autores de un libro
    private function obtenerAutoresLibro($idRecurso)
    {
        try {
            $detAutorModel = new DetAutorModel();
            $autorModel = new AutorModel();

            $autores = $detAutorModel->where('idrecurso', $idRecurso)->findAll();
            $nombresAutores = [];
            
            foreach ($autores as $a) {
                $autor = $autorModel->find($a['idautor']);
                if ($autor) {
                    $nombresAutores[] = $autor['nomautor'] . ' ' . $autor['apeautor'];
                }
            }
            
            return implode(', ', $nombresAutores) ?: 'Sin autores';
        } catch (\Exception $e) {
            log_message('error', "Error obteniendo autores para libro {$idRecurso}: " . $e->getMessage());
            return 'Error obteniendo autores';
        }
    }

    // Para AJAX: traer subcategorías + libros por categoría
    public function getSubcategoriasPorCategoria($idCategoria)
    {
        try {
            log_message('info', "getSubcategoriasPorCategoria llamado con ID: {$idCategoria}");
            
            // Configurar headers para JSON
            $this->response->setContentType('application/json');
            
            $subModel = new SubcategoriaModel();
            $recursoModel = new RecursoModel();

            $subs = $subModel->where('idcategoria', $idCategoria)->findAll();
            $resultado = [];

            log_message('info', "Subcategorías para categoría {$idCategoria}: " . count($subs));

            foreach ($subs as $sub) {
                $libros = $recursoModel->where('idsubcategoria', $sub['idsubcategoria'])->findAll();

                // agregar autores a cada libro
                foreach ($libros as &$libro) {
                    $libro['autores'] = $this->obtenerAutoresLibro($libro['idrecurso']);
                }

                $resultado[] = [
                    'subcategoria' => $sub['subcategoria'],
                    'libros' => $libros
                ];
                
                log_message('info', "Subcategoría {$sub['subcategoria']}: " . count($libros) . " libros");
            }

            log_message('info', 'Resultado enviado con ' . count($resultado) . ' subcategorías');
            return $this->response->setJSON($resultado);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getSubcategoriasPorCategoria: ' . $e->getMessage());
            $this->response->setContentType('application/json');
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Error interno del servidor'
            ])->setStatusCode(500);
        }
    }

    /**
     * Vista de Mis Préstamos
     */
    public function misPrestamos()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $prestamoModel = new PrestamoModel();
        $nomuser = session()->get('usuario');
        
        // Obtener ID del usuario desde el nombre
        $usuarioModel = new \App\Models\usuarioModel();
        $usuario = $usuarioModel->where('nomuser', $nomuser)->first();
        $idusuario = $usuario ? $usuario['idusuario'] : null;
        
        // Obtener matrícula del usuario
        $idmatricula = $idusuario ? $prestamoModel->getMatriculaByUsuario($idusuario) : null;
        
        $prestamosActivos = [];
        $historialPrestamos = [];
        
        if ($idmatricula) {
            // Debug: verificar datos
            log_message('info', "Usuario: $nomuser, ID: $idusuario, Matrícula: $idmatricula");
            
            // Primero, verificar si hay préstamos básicos
            $prestamosBasicos = $prestamoModel->where('idmatricula', $idmatricula)->findAll();
            log_message('info', 'Préstamos básicos encontrados: ' . count($prestamosBasicos));
            
            // Obtener préstamos activos con joins
            $prestamosActivos = $prestamoModel->getPrestamosActivosByUsuario($idmatricula);
            
            // Debug: mostrar préstamos encontrados
            log_message('info', 'Préstamos activos encontrados: ' . count($prestamosActivos));
            
            // Los datos ya vienen procesados desde la consulta SQL
            
            // Obtener historial completo
            $historialPrestamos = $prestamoModel->getHistorialPrestamosByUsuario($idmatricula, 20);
        } else {
            // Debug: mostrar por qué no hay matrícula
            log_message('info', "No se encontró matrícula. Usuario: $nomuser, ID Usuario: $idusuario");
        }

        // Si no hay préstamos, crear datos de ejemplo para testing
        if (empty($prestamosActivos) && session()->get('nivel') === 'admin') {
            $prestamosActivos = [
                [
                    'idprestamo' => 999,
                    'titulo' => 'Libro de Prueba',
                    'nomautor' => 'Autor Test',
                    'fechaprestamo' => date('Y-m-d H:i:s'),
                    'fechadevolucion' => date('Y-m-d H:i:s', strtotime('+15 days')),
                    'fechahoraretorno' => null,
                    'portada' => null
                ]
            ];
        }

        $datos = [
            'prestamosActivos' => $prestamosActivos,
            'historialPrestamos' => $historialPrestamos,
            'contadorActivos' => count($prestamosActivos),
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer'),
            'navbar' => view('layouts/navbar')
        ];

        return view('Catalogo/misPrestamos', $datos);
    }

    /**
     * Vista de Favoritos
     */
    public function favoritos()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $favoritoModel = new FavoritoModel();
        $nomuser = session()->get('usuario');
        
        // Obtener ID del usuario desde el nombre
        $usuarioModel = new \App\Models\usuarioModel();
        $usuario = $usuarioModel->where('nomuser', $nomuser)->first();
        $idusuario = $usuario ? $usuario['idusuario'] : null;
        
        $favoritos = [];
        $contadorFavoritos = 0;
        
        if ($idusuario) {
            // Obtener favoritos del usuario
            $favoritos = $favoritoModel->getFavoritosByUsuario($idusuario);
            $contadorFavoritos = count($favoritos);
            
            // Debug
            log_message('info', "Usuario: $nomuser, ID: $idusuario, Favoritos: $contadorFavoritos");
        } else {
            log_message('info', "No se encontró usuario: $nomuser");
        }

        // Si no hay favoritos, crear datos de ejemplo para testing (solo para admin)
        if (empty($favoritos) && session()->get('nivel') === 'admin') {
            $favoritos = [
                [
                    'idfavorito' => 999,
                    'idrecurso' => 1,
                    'titulo' => 'Libro Favorito de Prueba',
                    'nomautor' => 'Autor Test',
                    'anio' => 2023,
                    'categoria' => 'Literatura',
                    'subcategoria' => 'Novela',
                    'editorial' => 'Editorial Test',
                    'estado' => 'disponible',
                    'portada' => null
                ]
            ];
            $contadorFavoritos = 1;
        }

        $datos = [
            'favoritos' => $favoritos,
            'contadorFavoritos' => $contadorFavoritos,
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer'),
            'navbar' => view('layouts/navbar')
        ];

        return view('Catalogo/favoritos', $datos);
    }

    /**
     * Método temporal para insertar datos de prueba
     */
    public function insertarDatosPrueba()
    {
        if (session()->get('nivel') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();
        
        try {
            // Insertar préstamos de prueba para el usuario con matrícula 3 (estu1)
            $sql = "INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion, fechahoravalidacion) VALUES
                    (3, 1, 1, '2025-09-15 10:00:00', '2025-10-15 10:00:00', '2025-09-15 10:30:00'),
                    (3, 1, 2, '2025-09-01 11:00:00', '2025-09-20 11:00:00', '2025-09-01 11:30:00')
                    ON DUPLICATE KEY UPDATE idprestamo = idprestamo";
            
            $db->query($sql);
            
            return redirect()->to('/catalogo/mis-prestamos')->with('message', 'Datos de prueba insertados');
        } catch (\Exception $e) {
            log_message('error', 'Error insertando datos de prueba: ' . $e->getMessage());
            return redirect()->to('/catalogo/mis-prestamos')->with('error', 'Error al insertar datos');
        }
    }

    /**
     * Agregar/quitar favorito via AJAX
     */
    public function toggleFavorito()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud inválida']);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Debes iniciar sesión']);
        }

        $input = $this->request->getJSON();
        $idrecurso = $input->idrecurso ?? null;

        if (!$idrecurso) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID de recurso requerido']);
        }

        $favoritoModel = new FavoritoModel();
        $nomuser = session()->get('usuario');
        
        // Obtener ID del usuario
        $usuarioModel = new \App\Models\usuarioModel();
        $usuario = $usuarioModel->where('nomuser', $nomuser)->first();
        $idusuario = $usuario ? $usuario['idusuario'] : null;

        if (!$idusuario) {
            return $this->response->setJSON(['success' => false, 'message' => 'Usuario no encontrado']);
        }

        try {
            $agregado = $favoritoModel->toggleFavorito($idusuario, $idrecurso);
            $mensaje = $agregado ? 'Agregado a favoritos' : 'Quitado de favoritos';
            
            return $this->response->setJSON([
                'success' => true, 
                'message' => $mensaje,
                'agregado' => $agregado
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en toggleFavorito: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error interno']);
        }
    }

    /**
     * Quitar favorito específico via AJAX
     */
    public function quitarFavorito()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solicitud inválida']);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Debes iniciar sesión']);
        }

        $input = $this->request->getJSON();
        $idfavorito = $input->idfavorito ?? null;

        if (!$idfavorito) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID de favorito requerido']);
        }

        $favoritoModel = new FavoritoModel();

        try {
            $result = $favoritoModel->delete($idfavorito);
            
            if ($result) {
                return $this->response->setJSON(['success' => true, 'message' => 'Favorito eliminado']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'No se pudo eliminar']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en quitarFavorito: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error interno']);
        }
    }

}