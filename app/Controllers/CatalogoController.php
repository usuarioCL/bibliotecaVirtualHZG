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
    $categoriaModel = new CategoriaModel();
    $subcategoriaModel = new SubcategoriaModel();
    $recursoModel = new RecursoModel();

    $categorias = $categoriaModel->findAll();
    $subcategorias = $subcategoriaModel->findAll();

    // Traemos libros para cada subcategoría
    $datosSub = [];
    foreach ($subcategorias as $sub) {
        $datosSub[$sub['idsubcategoria']] = [
            'subcategoria' => $sub['subcategoria'],
            'libros' => $recursoModel->where('idsubcategoria', $sub['idsubcategoria'])->findAll()
        ];
    }

    $datos = [
        'categorias' => $categorias,
        'subcategorias' => $datosSub,
        'header' => view('layouts/header'),
        'footer' => view('layouts/footer')
    ];

    return view('Catalogo/catalogo', $datos);
}

    // Para AJAX: traer subcategorías + libros por categoría
    public function getSubcategoriasPorCategoria($idCategoria)
    {
    $subModel       = new SubcategoriaModel();
    $recursoModel   = new RecursoModel();
    $detAutorModel  = new DetautoresModel(); // para obtener autores
    $autorModel     = new AutorModel();

    $subs = $subModel->where('idcategoria', $idCategoria)->findAll();
    $resultado = [];

    foreach ($subs as $sub) {
        $libros = $recursoModel->where('idsubcategoria', $sub['idsubcategoria'])->findAll();

        // agregar autores a cada libro
        foreach ($libros as &$libro) {
            $autores = $detAutorModel->where('idrecurso', $libro['idrecurso'])->findAll();
            $nombresAutores = [];
            foreach ($autores as $a) {
                $autor = $autorModel->find($a['idautor']);
                if ($autor) $nombresAutores[] = $autor['nomautor'] . ' ' . $autor['apeautor'];
            }
            $libro['autores'] = implode(', ', $nombresAutores);
        }

        $resultado[] = [
            'subcategoria' => $sub['subcategoria'],
            'libros' => $libros
        ];
    }

        return $this->response->setJSON($resultado);
    }

}