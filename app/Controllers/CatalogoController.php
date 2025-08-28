<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\SubcategoriaModel;

class CatalogoController extends BaseController
{
    public function index()
    {
        $categoriaModel = new CategoriaModel();
        $subcategoriaModel = new SubcategoriaModel();

        $categorias = $categoriaModel->findAll();
        $subcategorias = $subcategoriaModel->findAll();

        $subcategorias = [];
        foreach ($categorias as $cat) {
            $subcategorias[$cat['idcategoria']] = $subcategoriaModel
                ->where('idcategoria', $cat['idcategoria'])
                ->findAll();
        }

        $datos = [
            'categorias'    => $categorias,
            'subcategorias' => $subcategorias,
            'header'        => view('layouts/header'),
            'footer'        => view('layouts/footer'),
        ];

        return view('Catalogo/catalogo', $datos);
    }
}