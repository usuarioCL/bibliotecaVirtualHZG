<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\SubcategoriaModel;
use App\Models\RecursoModel;
use App\Models\AutorModel;
use App\Models\DetautoresModel; // para obtener autores

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
            
        } catch (Exception $e) {
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
            $detAutorModel = new DetautoresModel();
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
        } catch (Exception $e) {
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
            
        } catch (Exception $e) {
            log_message('error', 'Error en getSubcategoriasPorCategoria: ' . $e->getMessage());
            $this->response->setContentType('application/json');
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Error interno del servidor'
            ])->setStatusCode(500);
        }
    }

}